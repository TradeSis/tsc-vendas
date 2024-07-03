

            var input = document.getElementById('procod');
            input.addEventListener("keyup", function(event) {
               
                if (event.key === "Enter") {
                    var searchVal = $('#procod').val();
                    res = pesquisaregistro(searchVal);
                    $('#pronom').html(res[0]);
                    document.getElementById("precoVenda").value = "R$ " + res[1];
                    document.getElementById("precoProm").value = "R$ " + res[2];
                    registrar();
                }
            });
            var input = document.getElementById('inputqtt');
            input.addEventListener("keyup", function(event) {
               
                if (event.key === "Enter") {
                    var searchVal = $('#procod').val();
                    res = pesquisaregistro(searchVal);
                    $('#pronom').html(res[0]);
                    document.getElementById("precoVenda").value = "R$ " + res[1];
                    document.getElementById("precoProm").value = "R$ " + res[2];
                    registrar();
                }
            });

            // Busca informações a partir de json
            $('#btpesquisa').click(function(){

                var searchVal = $('#procod').val();
                res = pesquisaregistro(searchVal);
                
                //alert('dato3: '+JSON.stringify(res));       

                $('#pronom').html(res[0]);
                //$('precoVenda').value = res[1];
                document.getElementById("precoVenda").value = "R$ " + res[1];
                //$('#precoProm').html(res[2]);
                document.getElementById("precoProm").value = "R$ " + res[2];

            })
            $('#procod').keydown(function(e){
                
                if(e.which == 9){

                    var searchVal = $('#procod').val();

                    res = pesquisaregistro(searchVal);
                    $('#pronom').html(res[0]);
                    //$('precoVenda').value = res[1];
                    document.getElementById("precoVenda").value = "R$ " + res[1];
                    //$('#precoProm').html(res[2]);
                    document.getElementById("precoProm").value = "R$ " + res[2];
                    }
            })


            function pesquisaregistro(prod){
                var result = []
                $.ajax({
                    dataType: "json",
                    url:"/tslebes/api/vendas/produtos/"+prod,
                    //url:"buscaproduto.php", //teste
                    type: "GET",
                    async: false,
                    success: function (data) {

                    //   alert('dato: '+JSON.stringify(data));

                      var  aux = [];

                      aux = data['produtos'] ;
                    //  alert('dato: '+JSON.stringify(aux));

                        var lista = [aux['pronom'], 
                                     aux['precoVenda'],
                                     aux["precoProm"]];
                        result = lista;

                     //   alert('dato2: '+JSON.stringify(result));       

                    },
                    error: function(e) {
                        alert('Erro: '+JSON.stringify(e['responseJSON']));
                        console.log('erro');

                        return null;
                    }
                });
                
                return result;
            }


                        // Registra na tabela manualmente
                        function registrar(){

                            var id = $('#procod').val();
                            var pronom = $('#pronom').html();
                            var qtt = $('#inputqtt').val();
                            var precoVenda = $('#precoVenda').val();
                            var precoProm = $('#precoProm').val();
                            var Total  = qtt * precoProm.replace('R$','');

                            
                            if (id && qtt ){
                                if (pronom != "") {

                                    var tabela = document.getElementById('corpodatabela');
                
                                    var row = tabela.insertRow()
                                    var cel_id = row.insertCell(0)
                                    var cel_nome = row.insertCell(1)
                                    var cel_qtt = row.insertCell(2)
                                    var cel_precoVenda = row.insertCell(3)
                                    var cel_precoProm = row.insertCell(4)
                                    var cel_total = row.insertCell(5)

                                    var cel_del = row.insertCell(6)
                
                                    cel_id.innerHTML = id
                                    cel_qtt.innerHTML = qtt
                                    cel_nome.innerHTML = pronom
                                    cel_precoVenda.innerHTML = precoVenda
                                    cel_precoProm.innerHTML = precoProm;
                                    cel_total.innerHTML = Total;
                                
                                    cel_del.innerHTML = "<button class='btdeletar'><i class='bi bi-trash'></button>"
                /*
                                    if($('#prodnome').html() == ''){
                                        cel_nome.innerHTML = pesquisaregistro(id)[0]
                                        cel_preco.innerHTML = pesquisaregistro(id)[1]
                                    }else{
                                        cel_nome.innerHTML = $('#prodnome').html()
                                        cel_preco.innerHTML = $('#prodpreco').html().replace('R$','')
                                    }
                */
                                    $('#procod').val('');
                                    $('#inputqtt').val('1');
                                    $('#precoVenda').val('');
                                    $('#precoProm').val('');
                                    $('#prodnom').html('')
                                    $('#total').html('')
                
                                    $('#procod').select();
                            }
            
                            } else {
                                alert('Preencha pelo menos os campos de "Produto" e "Quantidade"')
                            }
                        }
            

