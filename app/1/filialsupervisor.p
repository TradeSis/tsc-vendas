def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field supcod like supervisor.supcod.

def temp-table ttfilialsupervisor  no-undo serialize-name "filialsupervisor"  /* JSON SAIDA */
    field supcod like supervisor.supcod
    field supnom like supervisor.supnom
    field etbcod like estab.etbcod
    field etbnom like estab.etbnom
    field munic like estab.munic
    FIELD id_recid      AS INT64.    

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


FIND supervisor WHERE supervisor.supcod = ttentrada.supcod NO-LOCK.
    FOR EACH estab WHERE estab.supcod = supervisor.supcod NO-LOCK:
        create ttfilialsupervisor.
        ttfilialsupervisor.supcod = supervisor.supcod.
        ttfilialsupervisor.supnom = supervisor.supnom.
        ttfilialsupervisor.etbcod = estab.etbcod.
        ttfilialsupervisor.etbnom = estab.etbnom.
        ttfilialsupervisor.munic =  estab.munic.
         
         
        ttfilialsupervisor.id_recid = RECID(supervisor).     
    END.
    
     
find first ttfilialsupervisor no-error.
if not avail ttfilialsupervisor
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Filial Supervisor nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfilialsupervisor:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).
