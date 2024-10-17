def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fincotaetb"   /* JSON ENTRADA */
    field Etbcod     like fincotaetb.Etbcod
    field fincod     like fincotaetb.fincod
    field DtIVig     like fincotaetb.DtIVig
    FIELD Ativo      like fincotaetb.ativo.

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

if ttentrada.Etbcod = ? AND ttentrada.fincod = ? AND ttentrada.DtIVig = ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada Invalidos".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find fincotaetb where fincotaetb.Etbcod = ttentrada.Etbcod AND
                      fincotaetb.fincod = ttentrada.fincod AND
                      fincotaetb.DtIVig = ttentrada.DtIVig
                      no-lock no-error.
if not avail fincotaetb
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Utilizacao nao cadastrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

do on error undo:
    find fincotaetb where fincotaetb.Etbcod = ttentrada.Etbcod AND
                      fincotaetb.fincod = ttentrada.fincod AND
                      fincotaetb.DtIVig = ttentrada.DtIVig
                      exclusive no-error.

    fincotaetb.ativo = ttentrada.Ativo.

end.


create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Utilizacao alterada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
