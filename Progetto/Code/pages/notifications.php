<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
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
                                    <?php if (!$notification['Letto']): ?>
                                        <span class="badge bg-primary ms-2">Nuova</span>
                                    <?php endif; ?>
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
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include("../includes/footer.php"); ?>