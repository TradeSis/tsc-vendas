def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field procod like geaparam.procod.

def temp-table ttgeaparam  no-undo serialize-name "geaparam"  /* JSON SAIDA */
    like geaparam
    field pronom like produ.pronom.  

def temp-table ttgeafinan  no-undo serialize-name "geafinan"  /* JSON SAIDA */
    like geafinan
    FIELD finnom LIKE finan.finnom
    field finnpc like finan.finnpc.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

DEF DATASET dsConsultaPlanosGE  SERIALIZE-NAME "JSON" 
   FOR ttgeaparam, ttgeafinan
   DATA-RELATION for1 FOR ttgeaparam, ttgeafinan    RELATION-FIELDS(ttgeaparam.procod,ttgeafinan.procod) NESTED.

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


FOR EACH geaparam WHERE
    (IF ttentrada.procod = ?
    THEN TRUE 
    else geaparam.procod = ttentrada.procod)
    no-lock.
       
    create ttgeaparam.
    BUFFER-COPY geaparam TO ttgeaparam.

    find produ of geaparam no-lock no-error.
    if avail produ then ttgeaparam.pronom = produ.pronom.
                                                                                         

    for each geafinan of geaparam NO-LOCK.
        find finan of geafinan NO-LOCK.
        create ttgeafinan.
        BUFFER-COPY geafinan TO ttgeafinan.
        
        ttgeafinan.finnom = finan.finnom. 
        ttgeafinan.finnpc = finan.finnpc.
        /*
        create ttgeafinan.
        ttgeafinan.finnom = "AA". //finan.finnom. 
        ttgeafinan.finnpc = 11. // finan.finnpc.
        */
    end.
END.


find first ttgeaparam no-error.
if not avail ttgeaparam
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus =  "Parametro nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

//hsaida  = TEMP-TABLE ttgeaparam:handle.
hsaida =  DATASET dsConsultaPlanosGE:HANDLE.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).


