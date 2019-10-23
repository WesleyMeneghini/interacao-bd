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
    if(isset($_POST['btn_salvar'])){
        
        
        // Resgatar od dados enviado pelo formulario
        $nome = $_POST['txt_nome'];
        $telefone = $_POST['txt_telefone'];
        $celular = $_POST['txt_celular'];
        $email = $_POST['txt_email'];
        $codEstado = $_POST['sltestados'];
        
        // Quebra o conteudo ate a / , e vai guardando em um array
        $data_nascimento = explode("/", $_POST['txt_nascimento']);
        $sexo = $_POST['rdo_sexo'];
        $obs = $_POST['txt_obs'];
        
//        var_dump($data_nascimento);
        // Converter a data do padrao brasileiro para guardar no BD, com o padrao americano
        $data_nascimento = $data_nascimento[2]."-".$data_nascimento[1]."-".$data_nascimento[0];
//        echo($data_nascimento);
         
        
        /* VERIFICAR SE O VALOR DO BTNSALVAR É INSERIR*/
        if(strtoupper($_POST['btn_salvar']) == "INSERIR"){
            
            $sql = "insert into tblcontatos (nome, telefone, celular, email, dt_nasc, sexo, obs, codestados)
                values ('".$nome."', '".$telefone."', '".$celular."', '".$email."', '".$data_nascimento."', '".$sexo."', '".$obs."', ".$codEstado.")";
            
        }elseif(strtoupper($_POST['btn_salvar']) == "EDITAR"){
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
        }
        
        echo($sql);
        
        
        
        // Executa o script para o banco de dados [se o script der certo iremos redirecioar para a pagina de cadastro, senoa mostra mensagem de erro]
        if(mysqli_query($conexao, $sql)){
            // Redirecionar para uma determinada pagina
            header('location:../index.php');
        }else{
            echo("Erro ao executar o script no banco");
        }
        
       
        
    }
    
?>