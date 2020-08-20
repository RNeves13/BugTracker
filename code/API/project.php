<?php

if(isset($partes[1])){
    switch($partes[1]) {
        case 'new':
            if(isset($_SESSION["name"])){
                $getIdQuery = "SELECT idUser FROM user WHERE name = ?";
                $getIdSQL = mysqli_prepare($ligacao, $getIdQuery);
                mysqli_stmt_bind_param($getIdSQL, $_SESSION["name"]);
                
            }
    }

}
?>