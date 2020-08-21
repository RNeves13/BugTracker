<?php

if(isset($partes[1])){
    switch($partes[1]) {
        case 'new':
            if(isset($_SESSION["id"]) and isset($_POST["projName"]) and isset($_POST["projDesc"])){
                $insertQuery = "INSERT INTO project(projectName, description, dateCreation, owner) ". 
                                "VALUES(?, ?, STR_TO_DATE(?, '%Y %m %d'), ?)";
                $today = date("Y m d");
                $insertSQL = mysqli_prepare($ligacao, $insertQuery);
                mysqli_stmt_bind_param($insertSQL, 'sssi', $_POST["projName"], $_POST["projDesc"], $today, $_SESSION["id"]);
                if(mysqli_stmt_execute($insertSQL)){
                    $msg = Array("error" => "false", "msg" => "Project ". $_POST["projName"]." created");
                }else{
                    $msg = Array("error" => "true", "msg" => mysqli_stmt_error($insertSQL));
                }   
            }else{
                $msg = Array("error" => "true", "msg" => "Incomplete data");
            }
        break;

        case 'edit':
            if(isset($_SESSION["id"])){
                if(isset($_POST["projId"]) and isset($_POST["newName"]) and isset($_POST["newDesc"])){
                    $checkQuery = "SELECT type FROM projectworker where idUser = ? AND idProject = ?";
                    $checkSQL = mysqli_prepare($ligacao, $checkQuery);
                    mysqli_stmt_bind_param($checkSQL, 'ii', $_SESSION["id"], $_POST["projId"]);
                    mysqli_stmt_execute($checkSQL);
                    mysqli_stmt_bind_result($checkSQL, $type);
                    mysqli_stmt_store_result($checkSQL);
                    $r = mysqli_stmt_num_rows($checkSQL);
                    mysqli_stmt_fetch($checkSQL);
                    if($r  > 0 and strcmp($type, "admin") == 0){
                        $update = "UPDATE project SET projectName = ?, description = ? WHERE  idProject = ?";
                        $sql = mysqli_prepare($ligacao, $update);
                        mysqli_stmt_bind_param($sql, 'ssi', $_POST['newName'], $_POST['newDesc'], $_POST["projId"]);
                        mysqli_stmt_execute($sql);
                        if(mysqli_affected_rows($ligacao) > 0){
                            $msg = Array("error" => "false", "msg" => "Sucess");
                        }else{
                            $msg = Array("error" => "true", "msg" => "Project doesnt exist");
                        }
                    }else{
                        $msg = Array("error" => "true", "msg" => "Access denied");
                    }
                }else{
                     $msg = Array("error" => "true", "msg" => "Incomplete data");
                }
            }else{
                $msg = Array("error" => "true", "msg" => "Access denied");
               
            }
        break;

        case 'delete':
            if(isset($_SESSION["id"])){
                if(isset($_POST["projId"])){
                    $workers = "DELETE FROM projectworker WHERE idProject = ?";
                    $sqlWorkers = mysqli_prepare($ligacao, $workers);
                    mysqli_stmt_bind_param($sqlWorkers, 'i', $_POST["projId"]);
                    mysqli_stmt_execute($sqlWorkers);

                    $query = "DELETE FROM project WHERE  idProject = ? AND owner = ?";
                    $sql = mysqli_prepare($ligacao, $query);
                    mysqli_stmt_bind_param($sql, 'ii', $_POST["projId"], $_SESSION["id"]);
                    mysqli_stmt_execute($sql);
                    mysqli_stmt_store_result($sql);
                    echo mysqli_error($ligacao);
                    if(mysqli_affected_rows($ligacao) > 0){
                        $msg = Array("error" => "false", "msg" => "Success");
                    }else{
                        $msg = Array("error" => "true", "msg" => "Project doesnt exist or Access denied");
                    }
                }else{
                    $msg = Array("error" => "true", "msg" => "Incomplete data");
                }
            }else{
                $msg = Array("error" => "true", "msg" => "Access denied");
            }
        break;
        default:
            $msg = Array("error" => "true", "msg" => "Unkown function");
    }
}
?>
