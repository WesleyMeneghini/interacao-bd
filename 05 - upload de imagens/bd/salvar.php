<?php
    /* Ativa o recurso de variaveis de sesao no servidor, que por padra vem desabilitado*/
    // tambem verificar se a sessao ja foi startada na pagina quando der um require chamndo mais funcoes
    if( !isset($_SESSION)){
        session_start();
    }

    // Import do arquivo de conexao
    require_once('conexao.php');
    // chamada para function de conexao com o Mysql
    $conexao = conexaoMysql();
    
    // Verificar se ouve açao do POST pelo formulario
    if(isset($_POST['btn_limpar'])){
        header('location:../index.php');
    }
    
    if(isset($_POST['btn_salvar'])){
        
        
        // Resgatar od dados enviado pelo formulario
        $nome = strtoupper($_POST['txt_nome']);
        $telefone = $_POST['txt_telefone'];
        $celular = $_POST['txt_celular'];
        $email = strtolower($_POST['txt_email']);
        $codEstado = strtoupper($_POST['sltestados']);
        
        // Quebra o conteudo ate a / , e vai guardando em um array
        $data_nascimento = explode("/", $_POST['txt_nascimento']);
        $sexo = $_POST['rdo_sexo'];
        $obs = $_POST['txt_obs'];
        
        // Converter a data do padrao brasileiro para guardar no BD, com o padrao americano
        $data_nascimento = $data_nascimento[2]."-".$data_nascimento[1]."-".$data_nascimento[0];
        

        // Caso a foto NAO SEJA selecionada no modo editar
        if(strtoupper($_POST['btn_salvar']) == "EDITAR" && $_FILES['fle_foto']['name'] == ""){
            $sql = "update tblcontatos set
                                    nome ='".$nome."',
                                    telefone ='".$telefone."',
                                    celular ='".$celular."',
                                    email ='".$email."',
                                    dt_nasc ='".$data_nascimento."',
                                    sexo ='".$sexo."',
                                    obs ='".$obs."',
                                    codestados=".$codEstado."
                                    where codigo =".$_SESSION['codigo'].";";
            
            if(mysqli_query($conexao, $sql)){
                header('location:../index.php');
            }else{
//                echo($sql);
                echo("Erro ao executar o script no banco");
            }
        }else{/*Caso a foto SEJA selecionada no modo editar*/
            
            // GUARDA O TAMANHO DO ARQUIVO A SER UPADO PARA O SERVIDOR
            // var_dump($_FILES['fle_foto']); /* Verificar o que retona do method post*/
            if($_FILES['fle_foto']['size'] > 0 && $_FILES['fle_foto']['type'] != ""){/*Comparar para saber se o arquivo tem algo do servidor*/

                var_dump($_FILES['fle_foto']);

                $arquivoSize = $_FILES['fle_foto']['size']; /*Retorna um array e a propriedade ['size'] retorna o tamanho do arquivo*/
                $tamanhoArquivo = round($arquivoSize/1024); /*Converte para KB e usa o methodo round() para retornar o numero inteiro */
                $extensaoArquivo = $_FILES['fle_foto']['type'];/* Guarda o tipo de extensao do arquivo*/
                $arquivosPermitidos = array("image/jpeg", "image/jpg", "image/png"); /*array com extensoes das imagens permitidas*/

                // Verificar se a entensao do arquivo existe no array de arquivos permitidos
                if(in_array($extensaoArquivo, $arquivosPermitidos)){


                    //Comparar se o tamanho do arquivo é menor que 2MB
                    if($tamanhoArquivo < 2000){ /**/

                        // Como usar: pathinfo( var, PATHINFO_FILENAME);
                        $nomeArquivo = pathinfo($_FILES['fle_foto']['name'], PATHINFO_FILENAME);/* Permite retornar apenas o nome do arquivo*/
                        // Como usar: pathinfo( var, PATHINFO_EXTENSION);
                        $nomeArquivoExtensao = pathinfo($_FILES['fle_foto']['name'], PATHINFO_EXTENSION);/* Permite retornar apenas a extensao do arquivo*/

                        // No PHP dois algotitmos de criptografia md5(), cha1(), hash('tipo algoritmo', 'var') 
                        // Estamos gerando uma chave com o nome do arquivo + uniqid(time()) que é um numero aleatório com base em uma hh:mm:ss
                        $nomeArquivoCriptrografado = md5(uniqid(time()).$nomeArquivo);

                        $foto = $nomeArquivoCriptrografado.".".$nomeArquivoExtensao;

                        $arquivoTmp = $_FILES['fle_foto']['tmp_name'];

                        $diretorio = "arquivos/";

                        if(move_uploaded_file($arquivoTmp, $diretorio.$foto)){

                            /* VERIFICAR SE O VALOR DO BTNSALVAR É INSERIR*/
                            if(strtoupper($_POST['btn_salvar']) == "INSERIR"){

                                $sql = "insert into tblcontatos (nome, telefone, celular, email, dt_nasc, sexo, obs, codestados, foto)
                                    values ('".$nome."', '".$telefone."', '".$celular."', '".$email."', '".$data_nascimento."', '".$sexo."', '".$obs."', ".$codEstado.", '".$foto."');";

                            }elseif(strtoupper($_POST['btn_salvar']) == "EDITAR"){
                                $sql = "update tblcontatos set
                                        nome ='".$nome."',
                                        telefone ='".$telefone."',
                                        celular ='".$celular."',
                                        email ='".$email."',
                                        dt_nasc ='".$data_nascimento."',
                                        sexo ='".$sexo."',
                                        obs ='".$obs."',
                                        codestados=".$codEstado.",
                                        foto='".$foto."'
                                        where codigo =".$_SESSION['codigo'].";";
                            }

        //                    echo($sql);

                            // Executa o script para o banco de dados [se o script der certo iremos redirecioar para a pagina de cadastro, senoa mostra mensagem de erro]
                            if(mysqli_query($conexao, $sql)){
                                
                                // Tratamento para apagar a foto antiga do servidor
                                if(isset($_SESSION['nomeFoto'])){
                                    unlink('arquivos/'.$_SESSION['nomeFoto']);
                                    unset($_SESSION['nomeFoto']);
                                }
                                // Redirecionar para uma determinada pagina
                                header('location:../index.php');
                                
                            }else{
                                echo("Erro ao executar o script no banco");
                            }

                        }else{
                             echo("<script>
                                    alert('Nao foi possivel enviar o arquivo para o servidor!');
                                </script>");
                        }

                    }else{

                        echo("<script>
                                alert('Tamanho da imagem maior que 2MB!');
                            </script>");

                    }

                }else{
                     echo("<script>
                                alert('Tipo de arquivo não é permitido! (arquivos permitidos: .jpeg, .jpg, .png)');
                            </script>");
                }

            }else{
                echo("<script>
                        alert('Arquivo nao selecionado conforme tamanho ou tipo incorreto!');
                    </script>");
            }
        }
    }
    
?>