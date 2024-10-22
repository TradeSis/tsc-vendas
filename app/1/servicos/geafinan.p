def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field procod   like geafinan.procod
    field fincod   like finan.fincod.

def temp-table ttgeafinan  no-undo serialize-name "geafinan"  /* JSON SAIDA */
    like geafinan
    FIELD finnom LIKE finan.finnom
    field finnpc like finan.finnpc
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

IF ttentrada.procod <> ? and ttentrada.fincod = ?
then do:
    for each geafinan WHERE geafinan.procod = ttentrada.procod NO-LOCK.
        create ttgeafinan.
        BUFFER-COPY geafinan TO ttgeafinan.

        find finan of geafinan no-lock no-error.
        if avail finan 
        THEN DO:
        ttgeafinan.finnom = finan.finnom.
        ttgeafinan.finnpc = finan.finnpc.
        END.
        
        
        ttgeafinan.id_recid = RECID(geafinan).
    end.
end.
else do:
    FOR EACH geafinan WHERE geafinan.procod = ttentrada.procod and 
                            geafinan.fincod = ttentrada.fincod 
                            no-lock.
        
        create ttgeafinan.
        BUFFER-COPY geafinan TO ttgeafinan.

        find finan of geafinan no-lock no-error.
        if avail finan 
        THEN DO:
        ttgeafinan.finnom = finan.finnom.
        ttgeafinan.finnpc = finan.finnpc.
        END.
        
        ttgeafinan.id_recid = RECID(geafinan).
    END.
end.


find first ttgeafinan no-error.
if not avail ttgeafinan
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus =  "Plano nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttgeafinan:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).


