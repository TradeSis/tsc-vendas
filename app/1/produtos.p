def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field procod like produ.procod
    field etbcod like estoq.etbcod
    field buscaProduto  AS CHAR
    field pagina  AS INT.

def temp-table ttprodu  no-undo serialize-name "produ"  /* JSON SAIDA */
    field procod        like produ.procod
    field pronom        like produ.pronom
    field precoVenda    as decimal
    field precoProm     as decimal
    field contador     as int.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vprocod like ttentrada.procod.
DEF VAR contador AS INT.
DEF VAR vpreco AS decimal.
DEF VAR vprecopromocional AS decimal.
DEF VAR vpagina AS INT.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

contador = 0.
vpagina = ttentrada.pagina + 10.



vprocod = 0.
if avail ttentrada
then do:
    vprocod = ttentrada.procod.
    if vprocod = ? then vprocod = 0.
end.

IF ttentrada.procod <> ? OR (ttentrada.procod = ? AND ttentrada.buscaProduto = ?)
THEN DO:
    for each produ where
        (if vprocod = 0
         then true /* TODOS */
         else produ.procod = vprocod)
         no-lock.

        contador = contador + 1.
        IF contador > ttentrada.pagina and contador <= vpagina THEN DO:
            RUN criaprodu.
        END.

    end.
END.

IF ttentrada.buscaProduto <> ?
THEN DO:
      vprocod = INT(ttentrada.buscaProduto) no-error.  
      for each produ WHERE 
        produ.pronom MATCHES "*" + ttentrada.buscaProduto + "*"
        no-lock.
        
        contador = contador + 1.
        IF contador > ttentrada.pagina and contador <= vpagina THEN DO:
            RUN criaprodu.
        END.

    end.
END.

find first ttprodu no-error.

if not avail ttprodu
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Produto nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttprodu:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).

PROCEDURE criaprodu.

    create ttprodu.
    ttprodu.procod = produ.procod.
    ttprodu.pronom = produ.pronom.

    vpreco = 0.
    vprecopromocional = 0.
    
    find estoq where estoq.procod = produ.procod and estoq.etbcod = ttentrada.etbcod no-lock no-error.

    if avail estoq
    then do:
        vpreco = estoq.estvenda.
        if estoq.estprodat <> ? /* ate que data vale o preco promocional */
        then do:
            if estoq.estprodat >= today
            then do:
                if estoq.estbaldat <> ?
                then do:
                    if estoq.estbaldat <= today /* a partir de que data comeca a valer o preco */
                    then vprecopromocional = estoq.estproper.
                end.
                else vprecopromocional = estoq.estproper.
            end.
        end.
    end.

    ttprodu.precoVenda = vpreco.
    ttprodu.precoProm = vprecopromocional.

    ttprodu.contador = contador.

END.
