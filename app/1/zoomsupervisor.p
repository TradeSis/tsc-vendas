def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */


def temp-table ttentrada no-undo serialize-name "supervisor"   /* JSON ENTRADA */
    field supcod  like supervisor.supcod
    field pagina  AS INT.

def temp-table ttsupervisor  no-undo serialize-name "supervisor"  /* JSON SAIDA */
    like supervisor.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field retorno      as char.

def VAR vsupcod like ttentrada.supcod.
DEF VAR contador AS INT.
DEF VAR varPagina AS INT.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

contador = 0.
varPagina = ttentrada.pagina + 10.

vsupcod = ?.
if avail ttentrada
then do:
    vsupcod = ttentrada.supcod.
end.

IF ttentrada.supcod <> ? OR (ttentrada.supcod = ?)
THEN DO:
    for each supervisor where
        (if vsupcod = ?
         then true /* TODOS */
         else supervisor.supcod = vsupcod) 
         no-lock.
         
         contador = contador + 1.
        IF contador > ttentrada.pagina and contador <= varPagina THEN DO:
            create ttsupervisor.
            ttsupervisor.supcod    = supervisor.supcod.
            ttsupervisor.supnom    = supervisor.supnom.
           
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
