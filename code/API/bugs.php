<?php

if(isset($partes[1])){
    switch($partes[1]){
        case "add":
            if(isset($_POST["bugDescription"])){
                $query3 = "INSERT INTO bug(description, DATE,solved, project, finder) VALUES (?,STR_TO_DATE(?, '%Y %m %d),?,?,?)";
                $sql = mysqli_prepare($ligacao, $query3);
                mysqli_stmt_bing_param($sql,'ssiii', $_POST["bugDescription"], date("Y m d"),0, $_POST["projId"],$_SESSION["id"]);
                if(mysqli_stmt_execute($sql)){
                    $msg = Array("error" => "false", "msg" => "Ola1");
                }else{
                    $msg = Array("error" => "true", "msg" => "Ola2");
                }
            }
            break; 
            
        case "edit":
            $query = "UPDATE bug SET description  = ?";
            $sql = mysqli_prepare($ligacao,$query);
            mysqli_stmt_bing_param($sql,'s', $_POST["bugDescription"]);
            if(mysqli_stmt_execute($sql)){
                $msg = Array("error" => "false", "msg" => "Changed");
            }else{
                $msg = Array("error" => "true", "msg" => "Error changing Description");
            }
            break;
        
        case "solve":
            $query = "UPDATE bug SET solved = 1";
            $sql = mysqli_prepare($ligacao,$query);
            if(mysqli_stmt_execute($sql)){
                $msg = Array("error" => "false", "msg" => "Bug Solved");
            }else{
                $msg = Array("error" => "true", "msg" => "Error changing DATA");
            }            
            break;
        
        case "unsolve":
            $query = "UPDATE bug SET solved = 0";
            $sql = mysqli_prepare($ligacao,$query);
            if(mysqli_stmt_execute($sql)){
                $msg = Array("error" => "false", "msg" => "Bug Unsolved");
            }else{
                $msg = Array("error" => "true", "msg" => "Error changing DATA");
            }
            break;
            
        case "check":
            $query = "SELECT descripcion FROM bug WHERE idBug = ?";
            $sql = mysqli_prepare($ligacao, $query);
            mysqli_stmt_bing_param($sql,'i',$_POST["bugId"]);
            mysqli_stmt_bind_result($sql, $text);
            if(mysqli_stmt_execute($sql)){
                $msg = Array("error" => "false", "msg" => $text);
            }else{
                $msg = Array("error" => "true", "msg" => "Error getting description");
            }
            break;
            
        default:
            $msg = Array("error" => "true", "msg" => "funcao desconhecida");
    } 
        
        
        
        
        
        
        
?>