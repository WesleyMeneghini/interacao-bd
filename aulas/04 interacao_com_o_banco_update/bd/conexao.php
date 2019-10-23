<?php 
    
    function conexaoMysql(){
        /*
            TIPOS DE CONEXOES COM O BANCO MYSQL

                Primeiro tipo - mysql_connect();    ---> biblioteca mais antiga para criar conexao com o BD
                    Ex: mysql_connect(host, user, password);
                        mysql_select_db("database name");     ---> Estabelece qual database que sera utilizado

                Segundo tipo - mysqli_connect();    ---> biblioteca atual que subistitui a ysql_connect()
                    Ex: mysqli_connect(host, user, password, "database name");

                Terceiro tipo - PDO     ---> e uma classe para conexao com o banco de dados utilizando orientaçao a objetos
                    Ex: $conn = new PDO('mysql:host=localhost;dbname=meuBancoDeDados', user, password);

        */

        $server = (string) "localhost";     //Local de instalaçao do banco de dados
        $user = (string) "root";    // Usuario para a conexao com o banco de dados
        $password = (string) "bcd127";      // Senha para conexao com o BAnco de Dados
        $database = (string) "dbcontatos20192ta"; //Nome do databade

        // Estabelece a conexao com o banco de dados
        $conexao = mysqli_connect($server, $user, $password, $database); 
        
        return $conexao;
    }
?>