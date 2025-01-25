<?php

function getLoggedUserEmail() {
    return isset($_SESSION["email"]) ? $_SESSION["email"] : null;
}

//ok
function getIdFromName($name){
    return preg_replace("/[^a-z]/", '', strtolower($name));
}

function isUserLoggedIn()
{
    return isset($_SESSION["logged"]) && !$_SESSION["admin"];
}

function isAdminLoggedIn()
{
    return isset($_SESSION["logged"]) && $_SESSION["admin"];
}

//ok ma potrebbe essere migliorata
function registerLoggedUser($user){
    $_SESSION["logged"] = true;
    $_SESSION["admin"] = false;
    $_SESSION["email"] = $user["email"];
}
//stessa cosa di sopra
function registerAdminLogged($admin){
    $_SESSION["logged"] = true;
    $_SESSION["admin"] = true;
    $_SESSION["email"] = $admin["email"];
}
//ok
function logout()
{
    unset($_SESSION["logged"]);
    unset($_SESSION["admin"]);
    unset($_SESSION["email"]);
}
//aggiungi i messaggi delle notifiche

function insertAdmin($dbh){

    $admin1 = "federico";
    $admin2 = "pietro";
    $admin3 = "alex";
    $password = "admin";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    //hashed password
    $dbh->insertAdmin($admin1, $hashedPassword);
    $dbh->insertAdmin($admin2, $hashedPassword);
    $dbh->insertAdmin($admin3, $hashedPassword);
}

//Per fare l'upload di un'immagine
function uploadImage($path, $image){
    $imageName = basename($image["name"]);
    $fullPath = $path.$imageName;
    
    $maxKB = 500;
    $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
    $result = 0;
    $msg = "";
    //Controllo se immagine è veramente un'immagine
    $imageSize = getimagesize($image["tmp_name"]);
    if($imageSize === false) {
        $msg .= "File caricato non è un'immagine! ";
    }
    //Controllo dimensione dell'immagine < 500KB
    if ($image["size"] > $maxKB * 1024) {
        $msg .= "File caricato pesa troppo! Dimensione massima è $maxKB KB. ";
    }

    //Controllo estensione del file
    $imageFileType = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION));
    if(!in_array($imageFileType, $acceptedExtensions)){
        $msg .= "Accettate solo le seguenti estensioni: ".implode(",", $acceptedExtensions);
    }

    //Controllo se esiste file con stesso nome ed eventualmente lo rinomino
    if (file_exists($fullPath)) {
        $i = 1;
        do{
            $i++;
            $imageName = pathinfo(basename($image["name"]), PATHINFO_FILENAME)."_$i.".$imageFileType;
        }
        while(file_exists($path.$imageName));
        $fullPath = $path.$imageName;
    }

    //Se non ci sono errori, sposto il file dalla posizione temporanea alla cartella di destinazione
    if(strlen($msg)==0){
        if(!move_uploaded_file($image["tmp_name"], $fullPath)){
            $msg.= "Errore nel caricamento dell'immagine.";
        }
        else{
            $result = 1;
            $msg = $imageName;
        }
    }
    return array($result, $msg);
}

?>