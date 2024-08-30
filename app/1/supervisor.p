def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */


def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field supcod  like supervisor.supcod
    field pagina  AS INT
    field tempaginacao as log.

def temp-table ttsupervisor  no-undo serialize-name "supervisor"  /* JSON SAIDA */
    like supervisor
    field id_recid as int64.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field retorno      as char.

DEF VAR contador AS INT.
DEF VAR varPagina AS INT.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


contador = 0.
varPagina = ttentrada.pagina + 10.


IF ttentrada.supcod <> ?
then do:
    find supervisor where supervisor.supcod = ttentrada.supcod no-lock no-error.
    if avail supervisor
    then do:
        create ttsupervisor.
        ttsupervisor.supcod    = supervisor.supcod.
        ttsupervisor.supnom    = supervisor.supnom.
        ttsupervisor.id_recid = recid(supervisor).
    end.
end. 
ELSE DO:
    for each supervisor no-lock.
        if ttentrada.tempaginacao = true
        then do:    
            contador = contador + 1.
            IF contador > ttentrada.pagina and contador <= varPagina THEN DO:
                create ttsupervisor.
                ttsupervisor.supcod    = supervisor.supcod.
                ttsupervisor.supnom    = supervisor.supnom.
                ttsupervisor.id_recid = recid(supervisor).
            end.
        end.
        else do:
            create ttsupervisor.
                ttsupervisor.supcod    = supervisor.supcod.
                ttsupervisor.supnom    = supervisor.supnom.
                ttsupervisor.id_recid = recid(supervisor).
        end.    
    end.
END.
    


find first ttsupervisor no-error.

if not avail ttsupervisor
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.retorno = "supervisor nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttsupervisor:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).

/* export LONG VAR*/
DEF VAR vMEMPTR AS MEMPTR  NO-UNDO.
DEF VAR vloop   AS INT     NO-UNDO.
if length(vlcsaida) > 30000
then do:
    COPY-LOB FROM vlcsaida TO vMEMPTR.
    DO vLOOP = 1 TO LENGTH(vlcsaida): 
        put unformatted GET-STRING(vMEMPTR, vLOOP, 1). 
    END.
end.
else do:
    put unformatted string(vlcSaida).
end.
