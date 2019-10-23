<?php 
    
    /* Ativa o recurso de variaveis de sesao no servidor, que por padra vem desabilitado*/
    // tambem verificar se a sessao ja foi startada na pagina quando der um require chamndo mais funcoes
    if( !isset($_SESSION)){
        session_start();
        /*  
            Criar variavel de sessao
                $_SESSION['nomeDaVariavel'];
            
            Para apagar variavel de sessao no servidor
                unset($_SESSION['nomeDaVariavel']);
                    usar por exemplo quando voce sai de um sistema e fica no mesmo navegdor
            
            Para eliminar todas as variaveis de sessao do sistema automaticamente
                session_destroy();

        */
    }
    
    $chkFeminino = (string) "";
    $chkMasculino = (string) "";
    $nome = (string) "";

    $botao = (string) "INSERIR";
    $codEstado = (int) 0;
    $siglaEstado = (string) "";


    // Import do arquivo de conexao
    require_once('bd/conexao.php');

    // chamada para function de conexao com o Mysql
    $conexao = conexaoMysql();
    
    // Ver o que volta do banco, pois ele retorna um objeto
//    var_dump($conexao);

    // Valida se existe a variavel modo existe
    if(isset($_GET['modo'])){
        
        // valida se modo e editar
        if($_GET['modo']=='editar'){
//            $nome = $_GET['nome'];
//            $telefone = $_GET['telefone'];
//            $celular = $_GET['celular'];
            
            $codigo = $_GET['codigo'];
            
            
            
            /* Criando variavel de sessão para enviar o codigo do registro para outra pagina*/
            $_SESSION['codigo'] = $codigo;
            
            
            $sql = "select tblcontatos.*, tblestados.sigla
                    from tblcontatos inner join tblestados
                    on tblestados.codigo = tblcontatos.codestados
                    where tblcontatos.codigo=".$codigo;
            
            // transformar em array
            $select = mysqli_query($conexao, $sql);
            
            
            if($rsConsulta = mysqli_fetch_array($select)){
                $nome = $rsConsulta['nome'];
                $telefone = $rsConsulta['telefone'];
                $celular = $rsConsulta['celular'];
                $email = $rsConsulta['email'];
                $codEstado = $rsConsulta['codestados'];
                $siglaEstado = $rsConsulta['sigla'];
                $data_nascimento = explode("-", $rsConsulta['dt_nasc']);
                $data_nascimento = $data_nascimento[2]."/".$data_nascimento[1]."/".$data_nascimento[0];
                $sexo = $rsConsulta['sexo'];
                
                if($sexo == "F"){
                    $chkFeminino = "checked";
                }elseif($sexo == "M"){
                    $chkMasculino = "checked";
                }
                
                $obs = $rsConsulta['obs'];
                
                
                $botao = "EDITAR";
            }
        }
    }

    
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">

        <script src="js/jquery.js"></script>
        <script src="js/modulo.js"></script>
        <script>
            
            
            $(document).ready(function(){
                
                // Function para abrir a modal
                $('.visualizar').click(function(){
                    $('#conatiner_modal').fadeIn(1000);
                });
                
                // Fechar a modal
                $('#fechar').click(function(){
                    $('#conatiner_modal').fadeOut(1000);
                });
                
            });
            
            // funcao para jogar dados dentro da modal, usando o AJAX
            function visualizarDados(idItem){
                $.ajax({
                    type: "POST",
                    url: "modal_contatos.php",
                    data: {modo:'visualizar', codigo:idItem},
                    success: function(dados){
                        $('#modal_dados').html(dados);
                    }
                });
            }
            
        </script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>Home</title>
    </head>
    <body>

    <!-- Criar modal que ira receber os dados de outro arquyivo, atraves do javascript-->
        <div id="conatiner_modal">
            <div id="modal">
                
                <div id="fechar">
                    Fechar
                </div>
                
                <div id="modal_dados"></div>
            </div>  
        </div>        
        <div id="container_formulario">
            <div class="conteudo center">
                <div id="formulario" class="center">
                    <!--

                        HTML 5

                            required -  faz com que a caixa seja obrigatória na digitaçao

                            type="text"
                            type="email"
                            type="tel"
                            type="number"
                            type="range"
                            type="url"
                            type="password"
                            type="date"
                            type="color"
                            type="month" -> Retona o mes
                            type="week"  -> Retorna a semana

                        pattern - permite criar uma mascara para a entradad e dados no formulario (são expressoes regulares)
                        ex:pattern="[a-z A-Z é á â ô í ]*"

                    -->
                    <form name="cadastro" method="post" action="bd/salvar.php">
                        <h1 class="center">
                            CADASTRO DE CONTATOS
                        </h1>
                        <div class="segura_input">
                            <p class="informacoes_cadastro">
                                NOME:
                            </p>
                            <input placeholder="Digite seu nome..." type="text" name="txt_nome" class="dados_cadastro" required onkeypress="return validarEntrada(event, 'string');" value="<?=$nome?>">
                        </div>
                        <div class="segura_input">
                            <p class="informacoes_cadastro">
                                TELEFONE:
                            </p>
                            <input type="text" name="txt_telefone"  id="telefone" class="dados_cadastro" required onkeypress="return mascaraFone(this, event, 'numeric');" value="<?=@$telefone?>">
                        </div>
                        <div class="segura_input">
                            <p class="informacoes_cadastro">
                                CELULAR:
                            </p>
                            <input type="text" name="txt_celular"  class="dados_cadastro" value="<?=@$celular?>" required>
                        </div>
                        <div class="segura_input">
                            <p class="informacoes_cadastro">
                                EMAIL:
                            </p>
                            <input type="email" name="txt_email"  class="dados_cadastro" value="<?=@$email?>" required>
                        </div>
                        <div class="segura_input">
                            <p class="informacoes_cadastro">
                                Estados:
                            </p>
                            <select name="sltestados">
                                <?php
                                    if($_GET['modo'] == 'editar'){
                                        
                                    
                                
                                ?>
                                <option value="<?=$codEstado?>"><?=$siglaEstado?></option>
                                <?php
                                    }else{
                                ?>
                                
                                <option value="">Selecione um estado</option>
                                
                                
                                <?php
                                    }
                                    $sql = "select * from tblestados where codigo <> ".$codEstado;
    
                                    $selectTblEstados = mysqli_query($conexao, $sql);
                                    
                                   while($rsEstados = mysqli_fetch_array($selectTblEstados)){
                                       
                                ?>
                                            <option value="<?=$rsEstados['codigo']?>"><?=$rsEstados['sigla']?></option>;
                                        
                                <?php
                                    
                                   }
                                   
                                ?>
                                
                            </select>
                        </div>
                        
                        
                        <div class="segura_input">
                            <p class="informacoes_cadastro">
                                DATA DE NASCIMENTO:
                            </p>
                            <input type="text" name="txt_nascimento"  class="dados_cadastro" required value="<?=@$data_nascimento?>">
                        </div>
                        <div class="segura_input">
                            <p class="informacoes_cadastro">
                                SEXO:
                            </p>
                            <input type="radio" name="rdo_sexo" value="F" <?=@$chkFeminino?> class="dados_cadastro_radio" required>Feminino
                            <input type="radio" name="rdo_sexo" value="M" <?=@$chkMasculino?> class="dados_cadastro_radio" required>Masculino
                        </div>
                        <div id="segura_input">
                            <p class="informacoes_cadastro" required>
                                Observação:
                            </p>
                            <textarea  name="txt_obs" rows="10"><?=@$obs?></textarea>
                        </div>
                        <input class="button" type="submit" value="<?=$botao?>" name="btn_salvar">
                        <input class="button" type="submit" value="LIMPAR" name="btn_limpar">
                    </form>
                </div>
            </div>
        </div>
        <div id="consulta">
            <div class="conteudo center">
                <h1>
                    CONSULTA DE DADOS
                </h1>
                <table>
                    <tr>
                        <td>NOME</td>
                        <td>TELEFONE</td>
                        <td>CELULAR</td>
                        <td>EMAIL</td>
                        <td>Estado</td>
                        <td>OPCOES</td>
                    </tr>

                    <?php
                        // script para relacionar os elementos do bd 
                        $sql = "select tblcontatos.*, tblestados.sigla
                                from tblcontatos inner join tblestados
                                on tblestados.codigo = tblcontatos.codestados";
                        $select = mysqli_query($conexao, $sql);
                        
                        /*

                            Exeemplo de funcoes que convertem a resposta do banco de dados em formato de dados para manipulacao

                                mysqli_fetch_array()
                                mysqli_fetch_assoc()
                                mysqli_fetch_object()
                        */

                        while($rsContatos = mysqli_fetch_array($select)){

                        // var_dump($select);

                        

                    ?>


                    <tr>
                        <td><?=$rsContatos['nome']?></td>
                        <td><?=$rsContatos['telefone']?></td>
                        <td><?=$rsContatos['celular']?></td>
                        <td><?=$rsContatos['email']?></td>
                        <td><?=$rsContatos['sigla']?></td>
                        <td>
                            <div class="icone_consulta">
                                <a href="index.php?modo=editar&codigo=<?=$rsContatos['codigo']?>">
                                    <img src="icones/lapis.png">
                                </a>
                            </div>
                            
                            <div class="icone_consulta">
                                <a onclick="return confirm('Deseja realmente excluir esse registro?')" 
                                   href="bd/deletar.php?modo=excluir&codigo=<?=$rsContatos['codigo']?>">
                                    
                                    <img src="icones/delete.png">
                                </a>
                            </div>
                            
                            <div class="icone_consulta">
                                
                                <a href="#" 
                                   class="visualizar" 
                                   onclick="visualizarDados(<?=$rsContatos['codigo']?>);">
                                    
                                    <img src="icones/lupa.png">
                                    
                                </a>
                                
                            </div>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
            </div>
        </div>
        
    </body>
</html>