<?php

function getLoggedUserEmail() {
    return $_SESSION["email"] ?? null;
}

function getIdFromName($name) {
    return preg_replace("/[^a-z]/", '', strtolower($name));
}

function isUserLoggedIn() {
    return isset($_SESSION["logged"]);
}

function isAdminLoggedIn() {
    return isset($_SESSION["logged"]) && ($_SESSION["admin"] ?? false);
}

function registerLoggedUser($user) {
    $_SESSION["logged"] = true;
    $_SESSION["admin"] = false;
    $_SESSION["email"] = $user["email"];
}

function registerAdminLogged($admin) {
    $_SESSION["logged"] = true;
    $_SESSION["admin"] = true;
    $_SESSION["email"] = $admin["email"];
}

function logout() {
    unset($_SESSION["logged"]);
    unset($_SESSION["admin"]);
    unset($_SESSION["email"]);
    session_destroy();
}

function insertAdmin($dbh) {
    $admins = ["federico", "pietro", "alex"];
    $password = "admin";
    
    foreach ($admins as $admin) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $dbh->insertAdmin($admin, $hashedPassword);
    }
}
function maskCardNumber($number) {
    return '**** **** **** ' . substr(strval($number), -4);
}

function uploadImage($path, $image) {
    // Crea la cartella ricorsivamente se non esiste
    if (!file_exists($path)) {
        if (!mkdir($path, 0755, true)) {
            return [0, "Impossibile creare la cartella di destinazione"];
        }
    }

    $imageName = basename($image["name"]);
    $fullPath = $path . $imageName;
    
    $maxKB = 500;
    $result = 0;
    $msg = "";
    
    // Controllo MIME type reale
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $image["tmp_name"]);
    finfo_close($finfo);
    
    // Mappa MIME types con estensioni consentite
    $allowedMimes = [
        'image/jpeg' => ['jpg', 'jpeg'],       // JPEG standard
        'image/pjpeg' => ['jpg', 'jpeg'],      // JPEG legacy
        'image/png' => ['png'],
        'image/gif' => ['gif'],
        'image/x-png' => ['png']               // PNG alternativo
    ];
    
    // Verifica MIME type consentito
    if (!array_key_exists($mime, $allowedMimes)) {
        $msg = "Tipo file non valido! (Rilevato: $mime)";
        return [$result, $msg];
    }

    // Estrazione estensione client
    $clientExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    
    // Verifica corrispondenza estensione-MIME
    $expectedExtensions = $allowedMimes[$mime];
    if (!in_array($clientExtension, $expectedExtensions)) {
        $msg = "Estensione .$clientExtension non valida per $mime. ";
        $msg .= "Estensioni consentite: ." . implode(", .", $expectedExtensions);
        return [$result, $msg];
    }

    // Controllo dimensione file
    if ($image["size"] > $maxKB * 1024) {
        $msg = "Dimensione massima consentita: {$maxKB}KB";
        return [$result, $msg];
    }

    // Genera nome univoco se il file esiste
    if (file_exists($fullPath)) {
        $i = 1;
        $info = pathinfo($imageName);
        do {
            $newName = $info['filename'] . "_" . $i . "." . $clientExtension;
            $fullPath = $path . $newName;
            $i++;
        } while (file_exists($fullPath));
    }

    // Tentativo di salvataggio
    if (move_uploaded_file($image["tmp_name"], $fullPath)) {
        $result = 1;
        $msg = basename($fullPath);
    } else {
        $error = error_get_last();
        $msg = "Errore nel salvataggio: " . ($error['message'] ?? 'Sconosciuto');
    }
    
    return [$result, $msg];
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

function checkUploadPermissions($path) {
    if (!is_writable($path)) {
        return "La cartella non è scrivibile. Permessi: " . decoct(fileperms($path) & 0777);
    }
    return true;
}

// Funzione per debug MIME type
function getFileMimeType($tmp_name) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp_name);
    finfo_close($finfo);
    return $mime;
}

?>