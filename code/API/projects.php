<?php

if(isset($partes[1])){
    switch($partes[1]) {
        case 'new':
            if(isset($_SESSION["id"]) and isset($_POST["projName"]) and isset($_POST["projDesc"])){
                $insertQuery = "INSERT INTO project( `projectName`, `description`, `dateCreation`, `terminated`) ". 
                                "VALUES(?, ?, STR_TO_DATE(?, '%Y %m %d'),0)";
                $today = date("Y m d");
                $insertSQL = mysqli_prepare($ligacao, $insertQuery);
                mysqli_stmt_bind_param($insertSQL, 'sss', $_POST["projName"], $_POST["projDesc"], $today);
                if(mysqli_stmt_execute($insertSQL)){
                    mysqli_stmt_store_result($insertSQL);

                    $getID = "SELECT MAX(idProject) FROM project";
                    $idsql = mysqli_prepare($ligacao, $getID);
                    mysqli_stmt_execute($idsql);
                    mysqli_stmt_bind_result($idsql, $id);
                    mysqli_stmt_store_result($idsql);
                    mysqli_stmt_fetch($idsql);

                    $workerQuery = "INSERT INTO projectworker VALUES(?, ?, 'owner')";
                    $workerSQL = mysqli_prepare($ligacao, $workerQuery);
                    mysqli_stmt_bind_param($workerSQL, 'ii', $_SESSION["id"], $id);
                    mysqli_stmt_execute($workerSQL);
                    $msg = Array("error" => "false", "msg" => "Project ". $_POST["projName"]." created");
                }else{
                    $msg = Array("error" => "true", "msg" => "Unexpected error");
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
                    if($r  > 0 and (strcmp($type, "admin") == 0 or strcmp($type, "owner") == 0)){
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
                    $idQuery = "SELECT idUser FROM projectworker WHERE idProject = ? AND type = 'owner'";
                    $idSQL = mysqli_prepare($ligacao, $idQuery);
                    mysqli_stmt_bind_param($idSQL, 'i', $_POST["projId"]);
                    mysqli_stmt_execute($idSQL);
                    mysqli_stmt_bind_result($idSQL, $id);
                    mysqli_stmt_fetch($idSQL);
                    mysqli_stmt_close($idSQL);
                    if($id == $_SESSION["id"]){
                        $query = "DELETE FROM project WHERE  idProject = ?";
                        $sql = mysqli_prepare($ligacao, $query) or die(mysqli_error($ligacao));
                        mysqli_stmt_bind_param($sql, 'i', $_POST["projId"]);
                        mysqli_stmt_execute($sql);
                        mysqli_stmt_store_result($sql);
                        if(mysqli_affected_rows($ligacao) > 0){
                            $msg = Array("error" => "false", "msg" => "Success");
                        }else{
                            $msg = Array("error" => "true", "msg" => "Project doesnt exist");
                        }
                    }else{
                        $msg = Array("error" => "true", "msg" => "Access denied - only the owner can delete the project page");
                    }
                }else{
                    $msg = Array("error" => "true", "msg" => "Incomplete data");
                }
            }else{
                $msg = Array("error" => "true", "msg" => "Access denied - login");
            }
        break;
       
        case 'terminate':
            if(isset($_SESSION["id"])){
                if(isset($_POST["projId"])){
                    $idQuery = "SELECT idUser FROM projectworker WHERE idProject = ? AND type = 'owner'";
                    $idSQL = mysqli_prepare($ligacao, $idQuery);
                    mysqli_stmt_bind_param($idSQL, 'i', $_POST["projId"]);
                    mysqli_stmt_execute($idSQL);
                    mysqli_stmt_bind_result($idSQL, $id);
                    mysqli_stmt_fetch($idSQL);
                    mysqli_stmt_close($idSQL);
                    if($id == $_SESSION["id"]){
                        $query = "UPDATE project  SET terminated = 1 WHERE  idProject = ?";
                        $sql = mysqli_prepare($ligacao, $query);
                        mysqli_stmt_bind_param($sql, 'i', $_POST["projId"]);
                        mysqli_stmt_execute($sql);
                        mysqli_stmt_store_result($sql);
                        if(mysqli_affected_rows($ligacao) > 0){
                            $msg = Array("error" => "false", "msg" => "Success");
                        }else{
                            $msg = Array("error" => "true", "msg" => "Project doesnt exist");
                        }
                    }else{
                        $msg = Array("error" => "true", "msg" => "Access denied - only the owner can terminate the project");
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
}
?>