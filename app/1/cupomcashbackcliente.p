
def input param vlcentrada as longchar.
def input param vtmp       as char.

def var vlcsaida   as longchar.
def var vsaida as char.

def var lokjson as log.
def var hentrada as handle.
def var hsaida   as handle.
/*
{
    "cliente": [
        {
            "codigoCliente": 1513,
            "cpfCNPJ": "",
            "situacao" "TODOS" // "ABERTOS"
        }
    ]
}
*/
def temp-table ttentrada no-undo serialize-name "cliente"
    field codigoCliente as int
    field cpfCNPJ       as char
    field situacao      as char.

def temp-table ttcliente  no-undo serialize-name "cliente"
    field codigoCliente   as int    
    field cpfCNPJ          as char 
    field nomeCliente   as char.

def temp-table ttcupom  no-undo serialize-name "cupom"
    field codigoCliente   as int    
    field idCupom   as int
    field dataGeracao   as date format "99/99/9999"
    field dataValidade   as date format "99/99/9999"
    field valorCupom   as dec
    field percCupom  as dec
    field dataUtilizacao  as date format "99/99/9999".

def dataset conteudoSaida for ttcliente, ttcupom.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.
   

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY").
find first ttentrada no-error.
if not avail ttentrada
then do:
    create ttsaida.
    ttsaida.tstatus = 500.
    ttsaida.descricaoStatus = "Sem dados de Entrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find clien where clien.clicod = ttentrada.codigoCliente no-lock no-error.
if not avail clien
then do:
    find clien where clien.ciccgc = trim(ttentrada.cpfCNPJ) no-lock no-error.
end.
if not avail clien
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Cliente não encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

if ttentrada.situacao = "" then ttentrada.situacao = "TODOS".
create ttcliente.
ttcliente.codigoCliente = clien.clicod.
ttcliente.cpfCNPJ       = clien.ciccgc.
ttcliente.nomeCliente   = clien.clinom. 

    if ttentrada.situacao = "ABERTOS"
    then do:
        for each cupomb2b where 
            cupomb2b.tipocupom = "CASHB" and cupomb2b.clicod = clien.clicod and cupomb2b.dataTransacao = ?  no-lock.
            run penviacupom.
        end.
    end.
    if ttentrada.situacao = "USADOS"
    then do:
        for each cupomb2b where  
                cupomb2b.tipocupom = "CASHB" and cupomb2b.clicod = clien.clicod and cupomb2b.dataTransacao <> ? 
            no-lock.
            run penviacupom.
        end.
    end.
    
    if ttentrada.situacao = "TODOS"
    then do:
        for each cupomb2b where cupomb2b.tipocupom = "CASHB" and cupomb2b.clicod = clien.clicod   no-lock.
            run penviacupom.
        end.
    end.


procedure penviacupom.

    create ttcupom.
    ttcupom.codigoCliente   = cupomb2b.clicod.
    ttcupom.idCupom         = cupomb2b.idcupom.
    ttcupom.dataGeracao     = cupomb2b.dataCriacao.    
    ttcupom.dataValidade    = cupomb2b.dataValidade.
    ttcupom.valorCupom      = cupomb2b.valorDesconto.
    ttcupom.percCupom       = cupomb2b.percentualDesconto.
    ttcupom.dataUtilizacao  = dataTransacao.

end procedure.

hsaida  = dataset conteudoSaida:handle.

/*lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
*/

def var varquivo as char.
def var ppid as char.
INPUT THROUGH "echo $PPID".
DO ON ENDKEY UNDO, LEAVE:
IMPORT unformatted ppid.
END.
INPUT CLOSE.


varquivo  = vtmp + "apilebes_clientes_cupomcashbackcliente" + string(today,"999999") + replace(string(time,"HH:MM:SS"),":","") +
          trim(ppid) + ".json".

lokJson = hsaida:WRITE-JSON("FILE", varquivo, TRUE).

os-command value("cat " + varquivo).
os-command value("rm -f " + varquivo).
