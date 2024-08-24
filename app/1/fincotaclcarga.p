def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "carga"   /* JSON ENTRADA */
    field varquivo     as char.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def buffer bfincotaetb for fincotaetb.
def var vetbcod as int.
def var vfcccod as char.
def var vdtini as date.
def var vdtfim as date.
def var vconta as int.
    
def var xtime as int.

def var vcotas as int.
def var vpasta as char format "x(40)".


def var caminhoarquivo as char.
vpasta = "/admcom/tmp/import/". /* helio 03042024 912 */

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.

find first ttentrada no-error.
if not avail ttentrada
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada nao encontrados".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

caminhoarquivo = vpasta + ttentrada.varquivo.

if search(caminhoarquivo) = ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "arquivo nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.            
def var vokestab as log.
def var vokfinan as log.
vokfinan = yes.
vokestab = no.
vconta = 0.

input from value(caminhoarquivo).
repeat on error undo, leave.
    vconta = vconta + 1.
    if vconta = 1 then next.
    
    import delimiter ";" vetbcod vfcccod vdtini vdtfim vcotas no-error.
    if vfcccod = "" or vfcccod = ? then next.
    find fincotacluster where fincotacluster.fcccod = vfcccod no-lock no-error.
    if not avail fincotacluster
    then do:
        vokfinan = no.
    end.
    if vetbcod <> 0
    then do:
        find estab where estab.etbcod = vetbcod no-lock no-error.
        if avail estab
        then do:
            vokestab = yes. 
        end.    
    end.
    else do:
        vokestab = yes.
    end.
end.
input close.

if vokestab = no or vokfinan = no
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "erro no arquivo, provavelmente formatacao".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.
 
vconta = 0.
input from value(caminhoarquivo).
repeat on error undo, leave.
    vconta = vconta + 1.
    if vconta = 1 then next.
    
    import delimiter ";" vetbcod vfcccod vdtini vdtfim vcotas no-error.

    find fincotacluster where fincotacluster.fcccod = vfcccod no-lock no-error.
    if not avail fincotacluster then next.
    
    if vfcccod = "" or vfcccod = ? then next.
    
    find fincotacllib where fincotacllib.fcccod = vfcccod and
                            fincotacllib.etbcod = vetbcod and
                             fincotacllib.dtivig = vdtini 
        exclusive no-wait no-error.
    if not avail fincotacllib
    then do:
        create fincotacllib.
        fincotacllib.fcccod = vfcccod. 
        fincotacllib.etbcod = vetbcod.
        fincotacllib.dtivig = vdtini.
    end.

        
        fincotacllib.dtfvig = vdtfim.

        fincotacllib.cotaslib = vcotas.
        /* atualiza fincotaetb */
        for each fincotaclplan of fincotacluster no-lock.
            find fincotaetb where
                fincotaetb.etbcod = fincotacllib.etbcod and
                fincotaetb.fincod = fincotaclplan.fincod and
                fincotaetb.dtivig = fincotacllib.dtivig 
                no-error.
            if not avail fincotaetb
            then do:
                create fincotaetb.
                fincotaetb.etbcod = fincotacllib.etbcod.
                fincotaetb.fincod = fincotaclplan.fincod.
                fincotaetb.dtivig = fincotacllib.dtivig. 
            end.                                  
                fincotaetb.Ativo    = yes.
            
            fincotaetb.DtFVig   = fincotacllib.DtFVig .
            fincotaetb.CotasLib = 0 . /*fincotacllib.CotasLib. // Controle por Cluster */
        end.                            

    
end.
input close.


create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Carga realizada".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
