<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['numero'])) {
    $user = getLoggedUser();
    
    try {
        $stmt = $dbh->prepare("DELETE FROM CARTA_DI_CREDITO 
                             WHERE Email = ? AND Numero = ?");
        $stmt->execute([$user['Email'], $_POST['numero']]);
    } catch (PDOException $e) {
        error_log("Errore nell'eliminazione della carta: " . $e->getMessage());
    }
}

header("Location: cards.php");
exit();
?>