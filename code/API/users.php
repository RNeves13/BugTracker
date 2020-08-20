<?php

if(isset($partes[1])){
    switch($partes[1]) {
        case "login":
            if(isset($_POST["uName"]) and isset($_POST["pass"])){
                $query = "SELECT name FROM User WHERE userName = ? and password = ?;";
                $sql = mysqli_prepare($ligacao,$query);
                $name = $_POST["uName"];
                $pass = md5($salt . $_POST["pass"] . $salt);
                mysqli_stmt_bind_param($sql,'ss', $name, $pass);
                mysqli_stmt_execute($sql);
                mysqli_stmt_bind_result($sql, $nome);
                mysqli_stmt_store_result($sql); 
                if(mysqli_stmt_num_rows($sql) > 0){
                    mysqli_stmt_fetch($sql);
                    $_SESSION["name"] = $nome;
                    $msg = Array("error" => "false", "msg" => $nome);
                }else
                $msg = Array("error" => "true", "msg" => "Login errado");
            }else{
                $msg = Array("error" => "true", "msg" => "Falta de dados");
            }
        break;
    }
}

?>