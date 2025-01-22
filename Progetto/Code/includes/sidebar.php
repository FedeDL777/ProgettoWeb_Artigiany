

<?php
    include_once("../includes/bootstrap.php");
    require_once("../includes/functions.php");
    $categories = $dbh->getCategories();
?>

<!-- Banda laterale -->
<aside class="col-md-2 col-sm-12 bg-light p-1 px-0 border-end">
    <h5 class="text-center">Categorie</h5>
    <ul class="list-group">
        <?php if (empty($categories)): ?>
            <li class="list-group-item text-muted">Nessuna categoria disponibile</li>
        <?php else: ?>
            <?php foreach ($categories as $category): ?>
                <li class="list-group-item">
                    <a href="prodotti.php?categoria=<?php echo urlencode($category['categoryID']); ?>" class="text-decoration-none">
                        <?php echo htmlspecialchars($category['Nome']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</aside>
