

<?php
    include_once("../includes/bootstrap.php");
    require_once("../includes/functions.php");
    $categories = $dbh->getCategories();
?>

<!-- Barra orizzontale -->
<nav class="bg-light border-bottom">
    <h5 class="text-center py-2">Categorie</h5>
    <ul class="nav nav-pills justify-content-center">
        <?php if (empty($categories)): ?>
            <li class="nav-item">
                <span class="nav-link disabled text-muted">Nessuna categoria disponibile</span>
            </li>
        <?php else: ?>
            <?php foreach ($categories as $category): ?>
                <li class="nav-item">
                    <a href="categorySearch.php?categoria=<?php echo urlencode($category['categoryID']); ?>" class="nav-link">
                        <?php echo htmlspecialchars($category['Nome']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</nav>
