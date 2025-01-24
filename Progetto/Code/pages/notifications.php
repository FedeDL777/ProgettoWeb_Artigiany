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
    if (isset($_POST['delete_notification'])) {
        // Elimina singola notifica
        $email = $_SESSION['email'];
        $data = $_POST['notification_data'];
        $dbh->deleteNotification($email, $data);
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
    <link rel="stylesheet" href="../CSS/styles.css">
    
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
                                    <div class="d-flex flex-column align-items-end">
                                        <?php if (!$notification['Letto']): ?>
                                            <span class="badge bg-primary mb-2">Nuova</span>
                                        <?php endif; ?>
                                        <form method="POST" class="mt-auto">
                                            <input type="hidden" name="notification_data" value="<?= $notification['Data_'] ?>">
                                            <button type="submit" name="delete_notification" class="btn btn-danger btn-sm" title="Elimina notifica">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <?php if ($notification['orderID']): ?>
                                    <div class="mt-2">
                                        <a href="order_details.php?orderID=<?= $notification['orderID'] ?>" 
                                           class="btn btn-sm btn-outline-secondary">
                                            Dettagli ordine
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 text-center">
                    <form method="POST">
                        <button type="submit" name="mark_all_read" class="btn btn-primary">
                            <i class="bi bi-check-all"></i> Segna tutte come lette
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include("../includes/footer.php"); ?>