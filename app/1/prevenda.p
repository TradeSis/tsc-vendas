def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field precod like prevenda.precod
    field etbcod like prevenda.etbcod
    field pagina  AS INT.

def temp-table ttprevenda  no-undo serialize-name "prevenda"  /* JSON SAIDA */
    field precod        like prevenda.precod
    field etbcod        like prevenda.etbcod
    field dtinclu       like prevenda.dtinclu
    field clicod        like prevenda.clicod
    field vencod        like prevenda.vencod
    field dtfechamento  like prevenda.dtfechamento
    field clinom        like clien.clinom
    field cpfCnpj       like clien.ciccgc
    field funape        like func.funape
    field contador      as int
index prevenda is unique primary etbcod asc precod desc.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vprecod like ttentrada.precod.
DEF VAR contador AS INT.
DEF VAR varPagina AS INT.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

contador = 0.
varPagina = ttentrada.pagina + 10.

vprecod = 0.
if avail ttentrada
then do:
    vprecod = ttentrada.precod.
    if vprecod = ? then vprecod = 0.
end.

IF ttentrada.precod <> ? OR ttentrada.precod = ?
THEN DO:
    for each prevenda where prevenda.etbcod = ttentrada.etbcod and
        (if vprecod = 0
         then true /* TODOS */
         else prevenda.precod = vprecod)
         and
         (prevenda.dtinclu >= today - 1)
         no-lock.

        contador = contador + 1.

        IF contador > ttentrada.pagina and contador <= varPagina THEN DO:
            create ttprevenda.
            ttprevenda.precod       = prevenda.precod.
            ttprevenda.etbcod       = prevenda.etbcod.
            ttprevenda.clicod       = prevenda.clicod.
            ttprevenda.dtinclu      = prevenda.dtinclu.
            ttprevenda.vencod       = prevenda.vencod.
            ttprevenda.dtfechamento = prevenda.dtfechamento.
            ttprevenda.contador     = contador.

                //Clien            
                find clien where clien.clicod = prevenda.clicod no-lock no-error.
                if avail clien
                then do:
                    ttprevenda.clinom = clien.clinom.
                    ttprevenda.cpfCnpj = clien.ciccgc.
                end.
                else do:
                    ttprevenda.cpfCnpj = ?.
                    ttprevenda.clinom = ?.
                end.

                //Func            
                find func where func.etbcod = prevenda.etbcod and func.funcod = prevenda.vencod no-lock no-error.
                if avail func
                then do:
                    ttprevenda.funape = func.funape.
                end.
                else ttprevenda.funape = ?.
        END.

    end.
END.


find first ttprevenda no-error.

if not avail ttprevenda
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "prevenda nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttprevenda:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).


