def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field fcccod like fincotasuplib.fcccod
    field supcod like fincotasuplib.supcod
    field DtIVig like fincotasuplib.DtIVig.

def temp-table ttfincotasuplib  no-undo serialize-name "fincotasuplib"  /* JSON SAIDA */
    like fincotasuplib
    FIELD supnom LIKE supervisor.supnom
    FIELD cotasuso      AS INT
    FIELD saldo      AS INT
    FIELD id_recid      AS INT64.    

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vfcccod like ttentrada.fcccod.
def VAR vsupnom AS CHAR.
def var vcotasuso like fincotasup.cotasuso.

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


if ttentrada.fcccod <> ? AND ttentrada.supcod = ? AND ttentrada.DtIVig = ?
THEN DO:
    for each fincotasuplib where fincotasuplib.fcccod = ttentrada.fcccod  and
    fincotasuplib.dtivig <= today and
   (fincotasuplib.dtfvig >= today or fincotasuplib.dtfvig = ?)no-lock.
    
         RUN criaCotasSupervisor.
    end.
END.

IF ttentrada.fcccod <> ? AND ttentrada.supcod <> ? AND ttentrada.DtIVig <> ?
THEN DO:
    for each fincotasuplib WHERE fincotasuplib.fcccod = ttentrada.fcccod AND 
                                 fincotasuplib.supcod = ttentrada.supcod AND 
                                 fincotasuplib.DtIVig = ttentrada.DtIVig
                                 no-lock.
         
         RUN criaCotasSupervisor.
    end.
END.


find first ttfincotasuplib no-error.
if not avail ttfincotasuplib
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Supervisor nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfincotasuplib:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).

PROCEDURE criaCotasSupervisor.
     FIND supervisor WHERE supervisor.supcod =  fincotasuplib.supcod NO-LOCK .

     FIND fincotacluster WHERE fincotacluster.fcccod = fincotasuplib.fcccod NO-LOCK.
     find fincotacluster of fincotasuplib no-lock.    
     
        vcotasuso = 0.
        for each estab of supervisor no-lock.
            for each fincotaclplan of fincotacluster no-lock.
                for each fincotasup where 
                    fincotasup.supcod = fincotasuplib.supcod and
                    fincotasup.etbcod = estab.etbcod and
                    fincotasup.fincod = fincotaclplan.fincod and
                    fincotasup.dtivig = fincotasuplib.dtivig and
                    fincotasup.dtfvig = fincotasuplib.dtfvig 
                    no-lock.
                    vcotasuso = vcotasuso + fincotasup.cotasuso.
                end.
            end.
        end.

        create ttfincotasuplib.
        ttfincotasuplib.supcod = fincotasuplib.supcod.
        ttfincotasuplib.fcccod = fincotasuplib.fcccod.
        ttfincotasuplib.DtFVig = fincotasuplib.DtFVig.
        ttfincotasuplib.CotasLib = fincotasuplib.CotasLib.
        ttfincotasuplib.DtIVig = fincotasuplib.DtIVig.
        ttfincotasuplib.supnom = supervisor.supnom.
        ttfincotasuplib.cotasuso = vcotasuso.
        ttfincotasuplib.saldo = fincotasuplib.CotasLib - vcotasuso.

        ttfincotasuplib.id_recid = RECID(fincotasuplib).
END.


