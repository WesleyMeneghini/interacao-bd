<?php
    // Verifica a existencia da variavel modo
    if(isset($_GET['modo'])){
        // Valida se a variavel modo tem a acao de excluir
        if($_GET['modo']=='excluir'){

            // importar o arquivo de conexao com o banco
            require_once('conexao.php');
            // abre a conexao com o BD 
            $conexao = conexaoMysql();

            $codigo = $_GET['codigo'];
            $sql = "delete from tblcontatos where codigo=".$codigo.";";
            // echo($sql);

            if(mysqli_query($conexao, $sql))
                header('location:../index.php');
            else
                echo("Erro ao deletar o registro!");
        }
    }

?>