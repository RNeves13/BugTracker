<?php

if(isset($partes[1])){
    switch($partes[1]) {
        case "login":
            if(isset($_POST["uName"]) and isset($_POST["pass"])){
                $query = "SELECT idUser,name FROM User WHERE userName = ? and password = ?;";
                $sql = mysqli_prepare($ligacao,$query);
                $user = $_POST["uName"];
                $pass = md5($salt . $_POST["pass"] . $salt);
                mysqli_stmt_bind_param($sql,'ss', $user, $pass);
                mysqli_stmt_execute($sql);
                mysqli_stmt_bind_result($sql, $id, $name);
                mysqli_stmt_store_result($sql); 
                if(mysqli_stmt_num_rows($sql) > 0){
                    mysqli_stmt_fetch($sql);
                    $_SESSION["name"] = $name;
                    $_SESSION["userName"] = $user;
                    $_SESSION["id"] = $id;
                    $msg = Array("error" => "false", "msg" => $name);
                }else
                $msg = Array("error" => "true", "msg" => "Login errado");
            }else{
                $msg = Array("error" => "true", "msg" => "Falta de dados");
            }
        break;
            
        case "register":
            if(isset($_POST["uName"]) and isset($_POST["pass"]) and isset($_POST["email"]) and isset($_POST["name"])){
                $query2 ="INSERT INTO user(userName, password, email, name) VALUES (?,?,?,?)";
                $sql = mysqli_prepare($ligacao,$query2);
                $username = $_POST["uName"];
                $pass = md5($salt . $_POST["pass"] . $salt);
                $mail = $_POST["email"];
                $nome = $_POST["name"];
                mysqli_stmt_bind_param($sql,'ssss', $username, $pass, $mail, $nome);
                //mysqli_stmt_execute($sql);
                //mysqli_stmt_store_result($sql);                 
                //echo mysqli_stmt_num_rows($sql);   
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => $nome);
  
                }else{
                    $msg = Array("error" => "true", "msg" => "already exist");

                }

            }else{
                    $msg = Array("error" => "true", "msg" => "register incompleto");

            }
            break;
            
        case "logoff":
            if(isset($_SESSION["userName"])){
                $_SESSION["name"] =  "";
                $_SESSION["userName"] = "";  
                session_destroy();
            }
            break;
            
        default:
            $msg = Array("error" => "true", "msg" => "funcao desconhecida");
    }
}

?>