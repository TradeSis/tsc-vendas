def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field fincod like fincotaclplan.fincod
    field fcccod like fincotaclplan.fcccod.
    

def temp-table ttfincotaclplan  no-undo serialize-name "fincotaclplan"  /* JSON SAIDA */
    like fincotaclplan
    FIELD finnom LIKE finan.finnom
    FIELD id_recid      AS INT64.    

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vfincod like ttentrada.fincod.


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

IF ttentrada.fincod = ? AND ttentrada.fcccod <> ?
THEN DO:
    FOR EACH fincotaclplan WHERE fincotaclplan.fcccod = ttentrada.fcccod
                                 NO-LOCK:
        find finan where finan.fincod = fincotaclplan.fincod no-lock.
        
         create ttfincotaclplan.
         ttfincotaclplan.fincod = fincotaclplan.fincod.
         ttfincotaclplan.fcccod = fincotaclplan.fcccod.
         ttfincotaclplan.finnom = finan.finnom.
        
         ttfincotaclplan.id_recid = RECID(fincotaclplan).
    END.
END.


find first ttfincotaclplan no-error.
if not avail ttfincotaclplan
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Plano nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfincotaclplan:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).



