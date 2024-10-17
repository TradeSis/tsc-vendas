def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fincotasuplib"   /* JSON ENTRADA */
    field supcod     like fincotasuplib.supcod
    field fcccod     like fincotasuplib.fcccod
    field DtFVig     like fincotasuplib.DtFVig
    field CotasLib   like fincotasuplib.CotasLib
    field DtIVig     like fincotasuplib.DtIVig.

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

if ttentrada.fcccod = ? AND ttentrada.supcod = ? AND ttentrada.DtIVig = ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada Invalidos".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find fincotasuplib where fincotasuplib.fcccod = ttentrada.fcccod AND
                        fincotasuplib.supcod = ttentrada.supcod AND
                        fincotasuplib.DtIVig = ttentrada.DtIVig
                        no-lock no-error.
if not avail fincotasuplib
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Supervisor nao cadastrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

do on error undo:
    find fincotasuplib where fincotasuplib.fcccod = ttentrada.fcccod AND
                        fincotasuplib.supcod = ttentrada.supcod AND
                        fincotasuplib.DtIVig = ttentrada.DtIVig
                        exclusive no-error.

    fincotasuplib.CotasLib = ttentrada.CotasLib.
    fincotasuplib.DtFVig = ttentrada.DtFVig.
end.


create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Supervisor alterada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
