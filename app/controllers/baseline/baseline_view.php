<?php include 'views/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="row my-4 justify-content-center">
        <div class="justify-content-center">

        <h1>
            <?php echo $bl_head; ?> Baselines</h1>
        </div>
    </div>
</div>

<div class="container">

    <div class="table-responsive">
    <table class="table table-striped table-hover align-middle table-sm">
        <thead>
            <tr>
                <th>Client</th>
                <th>ID</th>
                <th>System</th>
                <th>Impact</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Last Updated</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($baselines as $bl): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bl->getShort()); ?></td>
                    <td><?php echo htmlspecialchars($bl->getBaselineID()); ?></td>
                    <td><?php echo htmlspecialchars($bl->getBaselineSystem()); ?></td>
                    <td><?php echo htmlspecialchars($bl->getImpactLvl()); ?></td>
                    <td><?php echo htmlspecialchars($bl->getBaselineStat()); ?></td>
                    <td><?php echo htmlspecialchars($bl->getStartDate()); ?></td>
                    <td><?php echo htmlspecialchars($bl->getModDate()); ?></td>
                    <td>
                        <form action="." method="post" class="m-0">
                            <input type="hidden" name="action" value="edit_baseline">
                            <input type="hidden" name="bl_id" value="<?php echo $bl->getBaselineID(); ?>">
                            <input type="hidden" name="co_id" value="<?php echo $bl->getBaselineCOID(); ?>">
                            <input type="submit" value="Edit" class="btn btn-sm btn-primary">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">Notes: <?php echo htmlspecialchars($bl->getComments()); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

</div>

<?php include 'views/footer.php'; ?>