<?php

if(isset($partes[1])){
    switch($partes[1]) {
        case 'add':
            if(isset($_SESSION["id"])){
                if(isset($_POST["uId"]) and isset($_POST["projId"]) and isset($_POST['type']) and strcmp($_POST['type'], 'owner') != 0){
                    $checkQuery = "SELECT type FROM projectworker WHERE userId = ? AND projId = ?";
                    $checkSQL = mysqli_prepare($ligacao, $checkQuery);
                    mysqli_stmt_bind_param($checkSQl, 'ii', $_SESSION["id"], $_POST["projId"]);
                    mysqli_execute($checkSQL);
                    mysqli_stmt_bind_result($checkSQL, $type);
                    mysqli_stmt_fetch($checkQuery);
                    mysqli_stmt_close($checkSQL);
                    if(strcmp($type, 'owner') == 0 or strcmp($type, 'admin') == 0){
                        $query= "INSERT INTO projectworker VALUES(?, ?, ?)";
                        $sql = mysqli_prepare($ligacao, $query);
                        mysqli_stmt_bind_param($sql, 'iis', $_POST["uId"], $_POST['projId'], $_POST["type"]);
                        if(mysqli_stmt_execute($sql)){
                            $msg = Array("error" => "false", "msg" => "Sucess");
                        }else{
                            $msg = Array("error" => "true", "msg" => "Unexpected error");
                        }
                        mysqli_stmt_close($sql);
                    }else{
                        $msg = Array("error" => "true", "msg" => "Access denied - you must be admin or owner");
                    }
                }else{
                    $msg = Array("error" => "true", "msg" => "Incomplete data");
                }
            }else{
                $msg = Array("error" => "true", "msg" => "Access denied - login");
            }
        break;
        case 'remove':
            if(isset($_SESSION["id"])){
                if(isset($_POST["uId"]) and isset($_POST["projId"])){
                    $checkQuery = "SELECT type FROM projectworker WHERE userId = ? AND projId = ?";
                    $checkSQL = mysqli_prepare($ligacao, $checkQuery);
                    mysqli_stmt_bind_param($checkSQl, 'ii', $_SESSION["id"], $_POST["projId"]);
                    mysqli_execute($checkSQL);
                    mysqli_stmt_bind_result($checkSQL, $type);
                    mysqli_stmt_fetch($checkQuery);
                    mysqli_stmt_close($checkSQL);
                    if(strcmp($type, 'owner') == 0 or strcmp($type, 'admin') == 0){
                        $query= "DELETE FROM projectworker WHERE userId = ? AND projecId = ?";
                        $sql = mysqli_prepare($ligacao, $query);
                        mysqli_stmt_bind_param($sql, 'ii', $_POST["uId"], $_POST['projId']);
                        if(mysqli_stmt_execute($sql)){
                            $msg = Array("error" => "false", "msg" => "Sucess");
                        }else{
                            $msg = Array("error" => "true", "msg" => "Unexpected error");
                        }
                        mysqli_stmt_close($sql);
                    }else{
                        $msg = Array("error" => "true", "msg" => "Access denied - you must be admin or owner");
                    }
                }else{
                    $msg = Array("error" => "true", "msg" => "Incomplete data");
                }
            }else{
                $msg = Array("error" => "true", "msg" => "Access denied - login");
            }

        break;
        case 'role':
            //unfinished - precisa de uma query para garantir que nao tas a trocar o coiso do nome, e provavelmente nao deixar admins mudar admins
            if(isset($_SESSION["id"])){
                if(isset($_POST["uId"]) and isset($_POST["projId"]) and isset($_POST['type']) and strcmp($_POST['type'], 'owner') != 0){
                    $checkQuery = "SELECT type FROM projectworker WHERE userId = ? AND projId = ?";
                    $checkSQL = mysqli_prepare($ligacao, $checkQuery);
                    mysqli_stmt_bind_param($checkSQl, 'ii', $_SESSION["id"], $_POST["projId"]);
                    mysqli_execute($checkSQL);
                    mysqli_stmt_bind_result($checkSQL, $type);
                    mysqli_stmt_fetch($checkSQL);
                    mysqli_stmt_close($checkSQL);
                    
                    $checkQueryUser = "SELECT type FROM projectworker WHERE userId = ? AND projId = ?";
                    $checkSQLUser = mysqli_prepare($ligacao, $checkQuery);
                    mysqli_stmt_bind_param($checkSQlUser, 'ii', $_POST["uId"], $_POST["projId"]);
                    mysqli_execute($checkSQLUser);
                    mysqli_stmt_bind_result($checkSQLUser, $oldType);
                    mysqli_stmt_fetch($checkSQLUser);
                    mysqli_stmt_close($checkSQLUser);
                    if(strcmp($type, 'owner') == 0 or strcmp($type, 'admin') == 0 and strcmp($oldType, 'admin') != 0){
                        $query= "UPDATE projectworker SET type = ? WHERE userId = ? AND projectId = ?";
                        $sql = mysqli_prepare($ligacao, $query);
                        mysqli_stmt_bind_param($sql, 'sii', $_POST["type"], $_POST['usedId'], $_POST["projId"]);
                        if(mysqli_stmt_execute($sql)){
                            $msg = Array("error" => "false", "msg" => "Sucess");
                        }else{
                            $msg = Array("error" => "true", "msg" => "Unexpected error");
                        }
                        mysqli_stmt_close($sql);
                    }else{
                        $msg = Array("error" => "true", "msg" => "Access denied - you must be admin or owner");
                    }
                }else{
                    $msg = Array("error" => "true", "msg" => "Incomplete data");
                }
            }else{
                $msg = Array("error" => "true", "msg" => "Access denied - login");
            }
        break;
        default:
            $msg = Array("error" => "true", "msg" => "Unkown function");
    }
?>