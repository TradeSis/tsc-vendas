def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fincotacluster"   /* JSON ENTRADA */
    field petbcod   as int
    field pfiltraperiodo   as log format "Sim/Nao"
    field pdtini   as date format "99/99/9999"
    field pdtfim   as date format "99/99/9999".

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
 

def var pfiltraperiodo as log format "Sim/Nao". 
def var pdtini as date format "99/99/9999".
def var pdtfim as date format "99/99/9999".
def var petbcod as int.

petbcod = ttentrada.petbcod.
pfiltraperiodo = ttentrada.pfiltraperiodo.
pdtini = ttentrada.pdtini.   
pdtfim = ttentrada.pdtfim.
          
        find estab where estab.etbcod = petbcod no-lock no-error.
        if not avail estab and petbcod > 0
        then do:
            create ttsaida.
            ttsaida.tstatus = 400.
            ttsaida.descricaoStatus = "filial invalida".

            hsaida  = temp-table ttsaida:handle.

            lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
            message string(vlcSaida).
            //return.
        end.     
 
def temp-table ttcotas no-undo
    field fcccod    like fincotacluster.fcccod
    field etbcod    like fincotacllib.etbcod 
    field dtivig like fincotaetb.dtivig
    field dtfvig    like fincotaetb.dtivig
    field cotaslib    like fincotacllib.CotasLib
    field cotasuso  like fincotaetb.cotasuso
    index x is unique primary fcccod asc etbcod asc dtivig asc dtfvig asc .
    
def var pordem as int.
 
def var varqcsv as char format "x(65)".

    varqcsv = "/admcom/relat/cotascluster_" + 
                string(today,"99999999") + "_" + replace(string(time,"HH:MM:SS"),":","") + ".csv".

for each fincotacluster no-lock,
    each fincotacllib  of fincotacluster where
            no-lock:
    if petbcod <> 0 then if fincotacllib.etbcod <> petbcod then next.
    if pdtini <> ?  then if fincotacllib.dtivig < pdtini then next.
    if pdtfim <> ?  then if fincotacllib.dtfvig > pdtfim then next.
    
    find first ttcotas where  
            ttcotas.fcccod = fincotacluster.fcccod and
            ttcotas.etbcod = fincotacllib.etbcod   and
            ttcotas.dtivig  = fincotacllib.dtivig and
            ttcotas.dtfvig  = fincotacllib.dtfvig
        no-error.
    if not avail ttcotas
    then do:
        create ttcotas. 
        ttcotas.fcccod = fincotacluster.fcccod.
        ttcotas.etbcod = fincotacllib.etbcod.
        ttcotas.dtivig  = fincotacllib.dtivig.
        ttcotas.dtfvig  = fincotacllib.dtfvig .
    end.
    ttcotas.cotaslib = ttcotas.cotaslib + fincotacllib.cotaslib.   
    
    for each fincotaclplan of fincotacluster no-lock,
         each fincotaetb where 
                    fincotaetb.etbcod = fincotacllib.etbcod and
                    fincotaetb.fincod = fincotaclplan.fincod and
                    fincotaetb.dtivig = fincotacllib.dtivig and
                    fincotaetb.dtfvig = fincotacllib.dtfvig 
                    no-lock.
        ttcotas.cotasuso = ttcotas.cotasuso + fincotaetb.cotasuso.   
    end.

end.



output to value(varqcsv).
put unformatted  "filial;muncipio;CLS;Cluster;" 
                 "dataInicio;dataFinal;"
                 "cotas Liberadas;cotas Usadas;"
                 skip.

for each ttcotas.

        find fincotacluster of ttcotas no-lock.
        find estab of ttcotas no-lock.
    
        put unformatted
            ttcotas.etbcod ";"
            estab.munic ";"
            ttcotas.fcccod ";"
            fincotacluster.fccnom ";"
            ttcotas.dtivig format "99/99/9999" ";"
            ttcotas.dtfvig format "99/99/9999" ";"
            ttcotas.cotaslib ";"
            ttcotas.cotasuso ";"
            skip.

    end.  


output close.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Arquivo csv gerado " + varqcsv.

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
