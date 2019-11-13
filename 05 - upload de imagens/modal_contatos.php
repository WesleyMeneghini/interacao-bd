<?php
    
    // Verifica que existe a variavel modo
    if(isset($_POST['modo'])){
        
        // Valida se o conteudo da variavel modo é visualizar
        if(strtoupper($_POST['modo']) == 'VISUALIZAR'){
            
            // Recebi o id do refistro enviado pelo AJAX
            $codigo = $_POST['codigo'];
            
             // Import do arquivo de conexao
            require_once('bd/conexao.php');
            // chamada para function de conexao com o Mysql
            $conexao = conexaoMysql();
            
            $sql = "select * from tblcontatos
                    where codigo =".$codigo;
            
            $select = mysqli_query($conexao, $sql);
            
            // Verificar se existe dados e converte em array
            if($rsVisualizar = mysqli_fetch_array($select)){
                $nome = $rsVisualizar['nome'];
                $telefone = $rsVisualizar['telefone'];
                $celular = $rsVisualizar['celular'];
                $email = $rsVisualizar['email'];
                $dt_nasc = $rsVisualizar['dt_nasc'];
                $sexo = $rsVisualizar['sexo'];
                $obs = $rsVisualizar['obs'];
            }
        }
    }

?>


<!-- Colocando os dados obtidos na tela em um elemento HTML-->
<!DOCTYPE html>
<html>
    <head>
        <title>
        </title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
    </head>
    <body>
        
        <table>
            <tr>
                <td>Nome:</td>
                <td><?=$nome?></td>
            </tr> 
            <tr>
                <td>Celular:</td>
                <td><?=$celular?></td>
            </tr>
            <tr>
                <td>Telefone:</td>
                <td><?=$telefone?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?=$email?></td>
            </tr>
            <tr>
                <td>Data Nascimento:</td>
                <td><?=$dt_nasc?></td>
            </tr>
            <tr>
                <td>Sexo:</td>
                <td><?=$sexo?></td>
            </tr>
            <tr>
                <td>Obs:</td>
                <td><?=$obs?></td>
            </tr>
            
        </table>
    </body>
</html>