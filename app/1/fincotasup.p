def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field id_recid AS INT64.
    

def temp-table ttfincotasup  no-undo serialize-name "fincotasup"  /* JSON SAIDA */
    like fincotasup
    FIELD finnom LIKE finan.finnom
    FIELD id_recid      AS INT64.    

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

DEF VAR prec AS INT.

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

prec = ttentrada.id_recid.
find fincotacllib where recid(fincotacllib) = prec no-lock.
find fincotacluster of fincotacllib no-lock.

find estab of fincotacllib no-lock.

for each fincotaclplan of fincotacluster no-lock.
    for each fincotasup where
            fincotasup.etbcod = fincotacllib.etbcod and
            fincotasup.fincod = fincotaclplan.fincod and
            fincotasup.dtivig = fincotacllib.dtivig
            no-lock.
        
        find finan where finan.fincod = fincotasup.fincod no-lock.
        
        create ttfincotasup.
        ttfincotasup.Etbcod = fincotasup.Etbcod.
        ttfincotasup.fincod = fincotasup.fincod.  
        ttfincotasup.DtIVig = fincotasup.DtIVig.
        ttfincotasup.DtFVig = fincotasup.DtFVig.
        ttfincotasup.Ativo = fincotasup.Ativo.
        ttfincotasup.CotasLib = fincotasup.CotasLib.
        ttfincotasup.CotasUso = fincotasup.CotasUso.
        ttfincotasup.finnom = finan.finnom.
        
        ttfincotasup.id_recid = RECID(fincotasup).
    end.
end.



find first ttfincotasup no-error.

if not avail ttfincotasup
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Registro nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfincotasup:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).


