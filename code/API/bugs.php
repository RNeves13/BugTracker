<?php

if(isset($partes[1])){
    switch($partes[1]){
        case "add":
            if(isset($_POST["bugDescription"]) and isset($_SESSION["id"]) and isset( $_POST["projId"]) and isset($_POST["title"])){
                $query3 = "INSERT INTO bug(title, description, dateFound, solved, project, finder) VALUES (?,?,STR_TO_DATE(?, '%Y %m %d'),0,?,?)";
                $sql = mysqli_prepare($ligacao, $query3);
                $today = date("Y m d");
                mysqli_stmt_bind_param($sql,'sssii',$_POST["title"], $_POST["bugDescription"], $today, $_POST["projId"],$_SESSION["id"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Success");
                }else{
                    $msg = Array("error" => "true", "msg" => "Unexpected error");
                }
                mysqli_stmt_close($sql);
            }else{
                $msg = Array("error" => "true", "msg" => "Incomplete data");
            }
            break; 
            
        case "edit":
            if(isset($_POST["newBugDescription"]) and isset($_POST["newTitle"]) and isset($_POST["bugI"]) and isset($_SESSION["id"])){
                $query = "UPDATE bug SET title = ?, description  = ? WHERE idBug = ?";
                $sql = mysqli_prepare($ligacao,$query);
                mysqli_stmt_bind_param($sql,'ssi', $_POST["newTitle"], $_POST["newBugDescription"], $_POST["idBug"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Changed");
                }else{
                    $msg = Array("error" => "true", "msg" => "Error changing bug");
                }        
                mysqli_stmt_close($sql);
            }else{
                $msg = Array("error" => "true", "msg" => "Incomplete data");
            }
            break;
        
        case "solve":
            if(isset($_POST["idBug"]) and isset($_SESSION["id"])){
                $query = "UPDATE bug SET solved = 1 WHERE idBug = ?";
                $sql = mysqli_prepare($ligacao,$query);
                mysqli_stmt_bind_param($sql,'i', $_POST["idBug"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Bug Solved");
                }else{
                    $msg = Array("error" => "true", "msg" => "Error changing DATA");
                }   
                mysqli_stmt_close($sql);
            }else{
                $msg = Array("error" => "true", "msg" => "Incomplete data");
            }
            break;
        
        case "unsolve":
            if(isset($_POST["idBug"]) and isset($_SESSION["id"])){
                $query = "UPDATE bug SET solved = 0 WHERE idBug = ?";
                $sql = mysqli_prepare($ligacao,$query);
                mysqli_stmt_bind_param($sql,'i', $_POST["idBug"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Bug UnSolved");
                }else{
                    $msg = Array("error" => "true", "msg" => "Error changing DATA");
                }   
                mysqli_stmt_close($sql);
            }else{
                $msg = Array("error" => "true", "msg" => "Incomplete data");
            }
            break;
            
        case "check":
            if(isset($_POST["idBug"]) and isset($_SESSION["id"])){
                $query = "SELECT descripcion FROM bug WHERE idBug = ?";
                $sql = mysqli_prepare($ligacao, $query);
                mysqli_stmt_bind_param($sql,'i',$_POST["bugId"]);
                mysqli_stmt_bind_result($sql, $text);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => $text);
                }else{
                    $msg = Array("error" => "true", "msg" => "Error getting description");
                }
                mysqli_stmt_close($sql);
            }else{
                $msg = Array("error" => "true", "msg" => "Incomplete data");
            }
            break;
            
        default:
            $msg = Array("error" => "true", "msg" => "funcao desconhecida");
    }   
}
?>