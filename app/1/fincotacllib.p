def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field fcccod like fincotacllib.fcccod
    field Etbcod like fincotacllib.Etbcod
    field DtIVig like fincotacllib.DtIVig.

def temp-table ttfincotacllib  no-undo serialize-name "fincotacllib"  /* JSON SAIDA */
    like fincotacllib
    FIELD munic         LIKE estab.munic
    FIELD cotasuso      AS INT
    FIELD id_recid      AS INT64.    

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def var vcotasuso like fincotaetb.cotasuso.

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

IF ttentrada.fcccod <> ? AND ttentrada.Etbcod = ? AND ttentrada.DtIVig = ?
THEN DO:
    FOR EACH fincotacllib WHERE fincotacllib.fcccod = ttentrada.fcccod NO-LOCK:
        RUN criaCotasFiliais.
    END.
END.

IF ttentrada.fcccod <> ? AND ttentrada.Etbcod <> ? AND ttentrada.DtIVig <> ?
THEN DO:
    FOR EACH fincotacllib WHERE fincotacllib.fcccod = ttentrada.fcccod AND
                                fincotacllib.Etbcod = ttentrada.Etbcod AND
                                fincotacllib.DtIVig = ttentrada.DtIVig
                                NO-LOCK:
        RUN criaCotasFiliais.
    END.
END.


find first ttfincotacllib no-error.
if not avail ttfincotacllib
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfincotacllib:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).

PROCEDURE criaCotasFiliais.
    FIND estab WHERE estab.etbcod =  fincotacllib.Etbcod NO-LOCK.
                
        FIND fincotacluster WHERE fincotacluster.fcccod = fincotacllib.fcccod NO-LOCK.
         find fincotacluster of fincotacllib no-lock.    
         
            vcotasuso = 0.
            for each fincotaclplan of fincotacluster no-lock.
                for each fincotaetb where 
                    fincotaetb.etbcod = fincotacllib.etbcod and
                    fincotaetb.fincod = fincotaclplan.fincod and
                    fincotaetb.dtivig = fincotacllib.dtivig and
                    fincotaetb.dtfvig = fincotacllib.dtfvig 
                    no-lock.
                    vcotasuso = vcotasuso + fincotaetb.cotasuso.
                end.
            end.
     
        create ttfincotacllib.
        ttfincotacllib.Etbcod = fincotacllib.Etbcod.
        ttfincotacllib.fcccod = fincotacllib.fcccod.
        ttfincotacllib.DtFVig = fincotacllib.DtFVig.
        ttfincotacllib.CotasLib = fincotacllib.CotasLib.
        ttfincotacllib.DtIVig = fincotacllib.DtIVig.
        ttfincotacllib.munic = estab.munic.
        ttfincotacllib.cotasuso = vcotasuso.

        ttfincotacllib.id_recid = RECID(fincotacllib).

END.

