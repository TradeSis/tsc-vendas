def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field precod     like prevenprod.precod
    field etbcod     like prevenprod.etbcod
    field procod     like prevenprod.procod
    field movpc      like prevenprod.movpc
    field movqtm     like prevenprod.movqtm
    field movtot     like prevenprod.movtot
    field precoori   like prevenprod.precoori
    field vencod     like prevenprod.vencod.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def var vmovseq as INT.

vmovseq = 1.    

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




do on error undo:
    find prevenprod where 
    prevenprod.precod = ttentrada.precod and 
    prevenprod.etbcod = ttentrada.etbcod and
    prevenprod.procod = ttentrada.procod 
    no-error.
    if avail prevenprod
    then do:

        update 
        prevenprod.movqtm = ttentrada.movqtm
        prevenprod.movtot = ttentrada.movtot.
    
    end.
    else do:

        find last prevenprod where prevenprod.precod = ttentrada.precod and prevenprod.etbcod = ttentrada.etbcod no-lock no-error.
        if avail prevenprod
        then do:
            vmovseq = prevenprod.movseq + 1.
        end.
    
        create prevenprod.
        prevenprod.precod   = ttentrada.precod.
        prevenprod.etbcod   = ttentrada.etbcod.
        prevenprod.procod   = ttentrada.procod.
        prevenprod.movseq   = vmovseq.
        prevenprod.movpc    = ttentrada.movpc.
        prevenprod.movqtm   = ttentrada.movqtm.
        prevenprod.movtot   = ttentrada.movtot.
        prevenprod.precoori = ttentrada.precoori.
        prevenprod.vencod   = ttentrada.vencod.

    end.

end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "prevenprod salvo".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
