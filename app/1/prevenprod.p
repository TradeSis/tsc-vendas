def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field precod like prevenprod.precod
    field etbcod like prevenprod.etbcod
    field pagina  AS INT.

def temp-table ttprevenprod  no-undo serialize-name "prevenprod"  /* JSON SAIDA */
    field precod        like prevenprod.precod
    field etbcod        like prevenprod.etbcod
    field movseq        like prevenprod.movseq
    field procod        like prevenprod.procod
    field movpc         like prevenprod.movpc
    field quantidade    like prevenprod.movqtm
    field total         like prevenprod.movtot
    field vencod        like prevenprod.vencod
    field pronom        like produ.pronom
    field precoVenda    as decimal
    field precoProm     as decimal
    field contador      as int
index prevenprod is unique primary
    etbcod asc
    precod desc
    movseq asc.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

DEF VAR contador AS INT.
DEF VAR vpreco AS decimal.
DEF VAR vprecopromocional AS decimal.
DEF VAR vpagina AS INT.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

contador = 0.
vpagina = ttentrada.pagina + 10.


IF ttentrada.precod <> ? AND ttentrada.etbcod <> ? 
THEN DO:
    for each prevenprod where 
        prevenprod.precod = ttentrada.precod and
        prevenprod.etbcod = ttentrada.etbcod
        no-lock.

        contador = contador + 1.

        IF contador > ttentrada.pagina and contador <= vpagina THEN DO:
            create ttprevenprod.
            ttprevenprod.etbcod     = prevenprod.etbcod.
            ttprevenprod.precod     = prevenprod.precod.
            ttprevenprod.movseq     = prevenprod.movseq.
            ttprevenprod.procod     = prevenprod.procod.
            ttprevenprod.movpc      = prevenprod.movpc.
            ttprevenprod.quantidade     = prevenprod.movqtm.
            ttprevenprod.total     = prevenprod.movtot.
            ttprevenprod.vencod     = prevenprod.vencod.
            ttprevenprod.contador   = contador.

            
                find produ where produ.procod = prevenprod.procod no-lock no-error.
                if avail produ
                then do:
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
                end.
                
            ttprevenprod.pronom     = produ.pronom.
            ttprevenprod.precoVenda = vpreco.
            ttprevenprod.precoProm = vprecopromocional.

        END.

    end.
END.


find first ttprevenprod no-error.

if not avail ttprevenprod
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "prevenprod nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttprevenprod:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).


