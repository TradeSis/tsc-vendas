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

find fincotasuplib where fincotasuplib.fcccod = ttentrada.fcccod AND
                        fincotasuplib.supcod = ttentrada.supcod AND
                        fincotasuplib.DtIVig = ttentrada.DtIVig
                        no-lock no-error.
if avail fincotasuplib
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Cota Supervisor ja cadastrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

do on error undo:
  create fincotasuplib.
  fincotasuplib.supcod = ttentrada.supcod.
  fincotasuplib.fcccod = ttentrada.fcccod.
  fincotasuplib.DtFVig = ttentrada.DtFVig.
  fincotasuplib.CotasLib = ttentrada.CotasLib.
  fincotasuplib.DtIVig = ttentrada.DtIVig.
end.


FIND fincotacluster WHERE fincotacluster.fcccod = ttentrada.fcccod NO-LOCK.
FOR EACH fincotaclplan  WHERE fincotaclplan.fcccod = fincotaclplan.fcccod NO-LOCK.

        /* atualiza fincotaetb */
        for each fincotacllib of fincotacluster no-lock.
            find fincotaetb where
                fincotaetb.etbcod = fincotacllib.etbcod and
                fincotaetb.fincod = fincotaclplan.fincod and
                fincotaetb.dtivig = fincotacllib.dtivig 
                no-error.
            if not avail fincotaetb
            then do:
                create fincotaetb.
                fincotaetb.etbcod = fincotacllib.etbcod.
                fincotaetb.fincod = fincotaclplan.fincod.
                fincotaetb.dtivig = fincotacllib.dtivig. 
                fincotaetb.CotasLib = 0.
            end.                                   
            
            fincotaetb.Ativo    = yes.
            fincotaetb.DtFVig   = fincotacllib.DtFVig .
       end.
       
       /* atualiza fincotasup */
       for each fincotasuplib of fincotacluster no-lock.
      
            FOR EACH estab WHERE estab.supcod = fincotasuplib.supcod NO-LOCK:
         
                find fincotasup where
                    fincotasup.supcod = fincotasuplib.supcod and
                    fincotasup.etbcod = estab.etbcod and
                    fincotasup.fincod = fincotaclplan.fincod and
                    fincotasup.dtivig = fincotasuplib.dtivig 
                    no-error.
                if not avail fincotasup
                then do:
                    create fincotasup.
                    fincotasup.supcod = fincotasuplib.supcod.
                    fincotasup.etbcod = estab.etbcod.
                    fincotasup.fincod = fincotaclplan.fincod.
                    fincotasup.dtivig = fincotasuplib.dtivig. 
                    fincotasup.CotasLib = 0 .
                end.                                  
                fincotasup.Ativo    = yes.
                fincotasup.DtFVig   = fincotasuplib.DtFVig.
            END.
        end. 
 END.       
create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Cota criada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
