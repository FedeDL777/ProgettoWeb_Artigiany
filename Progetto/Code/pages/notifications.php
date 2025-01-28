<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica autenticazione
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Gestione delle azioni
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_all_notifications'])) {
        // Elimina singola notifica
        $email = $_SESSION['email'];
        $data = $_POST['delete_all_notifications'];
        $dbh->deleteNotifications($email);
    } elseif (isset($_POST['mark_all_read'])) {
        // Segna tutte come lette
        $dbh->markAllNotificationsAsRead($_SESSION['email']);
    }
    
    // Redirect per evitare resubmission
    header("Location: notifications.php");
    exit();
}

// Recupero notifiche
$notifications = $dbh->getNotificationsByEmail($_SESSION['email']);

include("../includes/header.php");
?>

<main class="container my-4">
    <div class="container-page">
        <div id="main-content">
            <h1 class="text-center mb-4">Le tue notifiche</h1>

            <?php if (empty($notifications)): ?>
                <p class="text-center">Nessuna notifica da visualizzare.</p>
            <?php else: ?>
                <div class="notifications-list">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="card mb-3 <?= $notification['Letto'] ? '' : 'border-primary' ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <p class="card-text mb-1"><?= htmlspecialchars($notification['Testo']) ?></p>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($notification['Data_'])) ?>
                                        </small>
                                    </div>
            
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Sezione pulsanti azione -->
                <div class="mt-4 text-center">
                    <form method="POST" class="d-inline">
                        <button type="submit" name="mark_all_read" class="btn btn-primary">
                            <i class="bi bi-check-all"></i> Segna tutte come lette
                        </button>
                    </form>
                    <form method="POST" class="d-inline">
                        <button type="submit" name="delete_all_notifications" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Elimina tutte
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>


<?php include("../includes/footer.php"); ?>