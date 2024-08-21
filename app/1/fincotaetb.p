def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field id_recid AS INT64.
    

def temp-table ttfincotaetb  no-undo serialize-name "fincotaetb"  /* JSON SAIDA */
    like fincotaetb
    FIELD finnom LIKE finan.finnom
    FIELD id_recid      AS INT64.    

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

DEF VAR prec AS INT.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


prec = ttentrada.id_recid.
find fincotacllib where recid(fincotacllib) = prec no-lock.
find fincotacluster of fincotacllib no-lock.

find estab of fincotacllib no-lock.

for each fincotaclplan of fincotacluster no-lock.
    for each fincotaetb where
            fincotaetb.etbcod = fincotacllib.etbcod and
            fincotaetb.fincod = fincotaclplan.fincod and
            fincotaetb.dtivig = fincotacllib.dtivig
            no-lock.
        
        find finan where finan.fincod = fincotaetb.fincod no-lock.
        
        create ttfincotaetb.
        ttfincotaetb.Etbcod = fincotaetb.Etbcod.
        ttfincotaetb.fincod = fincotaetb.fincod.  
        ttfincotaetb.DtIVig = fincotaetb.DtIVig.
        ttfincotaetb.DtFVig = fincotaetb.DtFVig.
        ttfincotaetb.Ativo = fincotaetb.Ativo.
        ttfincotaetb.CotasLib = fincotaetb.CotasLib.
        ttfincotaetb.CotasUso = fincotaetb.CotasUso.
        ttfincotaetb.finnom = finan.finnom.
        
        ttfincotaetb.id_recid = RECID(fincotaetb).
    end.
end.

/*
vEtbcod = 0.
if avail ttentrada
then do:
    vEtbcod = ttentrada.Etbcod.
    if vEtbcod = ? then vEtbcod = 0.
end.

IF ttentrada.Etbcod <> ? OR (ttentrada.Etbcod = ?)
THEN DO:
    for each fincotaetb where
        (if vEtbcod = 0
         then true /* TODOS */
         else fincotaetb.Etbcod = vEtbcod)
         no-lock.
         
         find finan where finan.fincod = fincotaetb.fincod no-lock.

        create ttfincotaetb.
        ttfincotaetb.Etbcod = fincotaetb.Etbcod.
        ttfincotaetb.fincod = fincotaetb.fincod.  
        ttfincotaetb.DtIVig = fincotaetb.DtIVig.
        ttfincotaetb.DtFVig = fincotaetb.DtFVig.
        ttfincotaetb.Ativo = fincotaetb.Ativo.
        ttfincotaetb.CotasLib = fincotaetb.CotasLib.
        ttfincotaetb.CotasUso = fincotaetb.CotasUso.
        ttfincotaetb.finnom = finan.finnom.
        
        ttfincotaetb.id_recid = RECID(fincotaetb).
    end.
END.
 */


find first ttfincotaetb no-error.

if not avail ttfincotaetb
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Registro nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfincotaetb:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).


