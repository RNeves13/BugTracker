<?php

if(isset($partes[1])){
    switch($partes[1]){
        case "add":
            if(isset($_POST["bugDescription"]) and isset($_POST["id"])){
                $query3 = "INSERT INTO bug(description, DATEFound,solved, project, finder) VALUES (?,STR_TO_DATE(?, '%Y %m %d),?,?,?)";
                $sql = mysqli_prepare($ligacao, $query3);
                mysqli_stmt_bing_param($sql,'ssiii', $_POST["bugDescription"], date("Y m d"),0, $_POST["projId"],$_SESSION["id"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Ola1");
                }else{
                    $msg = Array("error" => "true", "msg" => "Ola2");
                }
                mysqli_stmt_close($sql);
            }
            break; 
            
        case "edit":
            if(isset($_POST["bugDescription"]) and isset($_POST["idBug"]) and isset($_POST["id"])){
                $query = "UPDATE bug SET description  = ? WHERE idBug = ?";
                $sql = mysqli_prepare($ligacao,$query);
                mysqli_stmt_bing_param($sql,'si', $_POST["bugDescription"], $_POST["idBug"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Changed");
                }else{
                    $msg = Array("error" => "true", "msg" => "Error changing Description");
                }        
                mysqli_stmt_close($sql);
            }
            break;
        
        case "solve":
            if(isset($_POST["idBug"]) and isset($_POST["id"])){
                $query = "UPDATE bug SET solved = 1 WHERE idBug = ?";
                $sql = mysqli_prepare($ligacao,$query);
                mysqli_stmt_bing_param($sql,'i', $_POST["idBug"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Bug Solved");
                }else{
                    $msg = Array("error" => "true", "msg" => "Error changing DATA");
                }   
                mysqli_stmt_close($sql);
            }
            break;
        
        case "unsolve":
            if(isset($_POST["idBug"]) and isset($_POST["id"])){
                $query = "UPDATE bug SET solved = 0 WHERE idBug = ?";
                $sql = mysqli_prepare($ligacao,$query);
                mysqli_stmt_bing_param($sql,'i', $_POST["idBug"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Bug UnSolved");
                }else{
                    $msg = Array("error" => "true", "msg" => "Error changing DATA");
                }   
                mysqli_stmt_close($sql);
            }
            break;
            
        case "check":
            if(isset($_POST["idBug"]) and isset($_POST["id"])){
                $query = "SELECT descripcion FROM bug WHERE idBug = ?";
                $sql = mysqli_prepare($ligacao, $query);
                mysqli_stmt_bing_param($sql,'i',$_POST["bugId"]);
                mysqli_stmt_bind_result($sql, $text);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => $text);
                }else{
                    $msg = Array("error" => "true", "msg" => "Error getting description");
                }
                mysqli_stmt_close($sql);
            }
            break;
            
        default:
            $msg = Array("error" => "true", "msg" => "funcao desconhecida");
    } 
        
        
        
        
        
        
        
?>