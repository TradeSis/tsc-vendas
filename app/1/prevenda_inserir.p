def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field etbcod     like prevenda.etbcod
    field clicod     like prevenda.clicod
    field vencod     like prevenda.vencod.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus           as int serialize-name "status"
    field precod            as int serialize-name "precod"
    field descricaoStatus   as char.

def var vprecod as INT.

vprecod = 1.

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
    find last prevenda where prevenda.etbcod = ttentrada.etbcod no-lock no-error.
	if avail prevenda
	then do:
		vprecod = prevenda.precod + 1.
	end.
	
	
	create prevenda.
	prevenda.etbcod = ttentrada.etbcod.
	prevenda.clicod = ttentrada.clicod.
	prevenda.vencod = ttentrada.vencod.
	prevenda.precod = vprecod.
	prevenda.dtinclu = today.

    if ttentrada.clicod = ? or ttentrada.clicod = 0
    then do:
        prevenda.identificador = "vendedor " + STRING(ttentrada.vencod) + " loja " + STRING(ttentrada.etbcod) + " " + STRING(today) .
    end.
end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Prevenda criado com sucesso".
ttsaida.precod = vprecod.
hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
