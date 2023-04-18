<?php include 'views/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="row my-2 mb-0 justify-content-center">
        <div class="justify-content-center">
        <h1>Access Logs</h1>
        </div>
    </div>
</div>

<div class="container">
    <nav aria-label="Page Navigation">
        <ul class="pagination justify-content-end">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a href="?action=view_log&page=<?= $page - 1 ?>" class="page-link">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a href="#" class="page-link">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $tot_pg; $i++): ?>
                <li class="page-item">
                    <a href="?action=view_log&page=<?= $i ?>"<?= $i == $page ? ' class="page-link active"' : ' class="page-link"' ?>><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $tot_pg): ?>
                <li class="page-item"><a class="page-link" href="?action=view_log&page=<?= $page + 1 ?>">Next</a></li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a href="#" class="page-link">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="table-responsive">
    <table class="table table-striped table-hover align-middle table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Date</th>
                <th>Description</th>
                <th>Company ID</th>
                <th>Baseline ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($logs as $l): ?>
                <tr>
                    <td><?php echo htmlspecialchars($l->getID()); ?></td>
                    <td><?php echo htmlspecialchars($l->getAdmID()); ?></td>
                    <td><?php echo htmlspecialchars($l->getAccDate()); ?></td>
                    <td><?php echo htmlspecialchars($l->getAccData()); ?></td>
                    <td><?php echo htmlspecialchars($l->getCompanyID()); ?></td>
                    <td><?php echo htmlspecialchars($l->getBaselineID()); ?></td>
                    <td>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <nav aria-label="Page Navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a href="?action=view_log&page=<?= $page - 1 ?>" class="page-link">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a href="#" class="page-link">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $tot_pg; $i++): ?>
                <li class="page-item">
                    <a href="?action=view_log&page=<?= $i ?>"<?= $i == $page ? ' class="page-link active"' : ' class="page-link"' ?>><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $tot_pg): ?>
                <li class="page-item"><a class="page-link" href="?action=view_log&page=<?= $page + 1 ?>">Next</a></li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a href="#" class="page-link">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<?php include 'views/footer.php'; ?>