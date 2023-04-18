<?php include 'views/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="row my-4 justify-content-center">
        <div class="justify-content-center">
        <h1>Access Logs</h1>
        </div>
    </div>
</div>

<div class="container">

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

</div>

<?php include 'views/footer.php'; ?>