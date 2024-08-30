def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fincotacllib"   /* JSON ENTRADA */
    field Etbcod     like fincotacllib.Etbcod
    field fcccod     like fincotacllib.fcccod
    field DtFVig     like fincotacllib.DtFVig
    field CotasLib   like fincotacllib.CotasLib
    field DtIVig     like fincotacllib.DtIVig.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

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

find fincotacllib where fincotacllib.fcccod = ttentrada.fcccod AND
                        fincotacllib.Etbcod = ttentrada.Etbcod AND
                        fincotacllib.DtIVig = ttentrada.DtIVig
                        no-lock no-error.
if avail fincotacllib
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Filial ja cadastrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find estab where estab.etbcod = ttentrada.Etbcod NO-LOCK NO-ERROR.
    if not avail estab
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "estab nao encontrado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.

do on error undo:
  create fincotacllib.
  fincotacllib.Etbcod = ttentrada.Etbcod.
  fincotacllib.fcccod = ttentrada.fcccod.
  fincotacllib.DtFVig = ttentrada.DtFVig.
  fincotacllib.CotasLib = ttentrada.CotasLib.
  fincotacllib.DtIVig = ttentrada.DtIVig.
end.
       FIND fincotacluster WHERE fincotacluster.fcccod = ttentrada.fcccod NO-LOCK.
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
                fincotaetb.CotasLib = 0 .
            end.                                  
            fincotaetb.Ativo    = yes.
            
            fincotaetb.DtFVig   = fincotacllib.DtFVig .
            
        end. 


create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Filial criada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
