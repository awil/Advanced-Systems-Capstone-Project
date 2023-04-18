<?php include 'views/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="row my-4 justify-content-center">
        <div class="justify-content-center">
            <h1><?php echo $bl_head; ?> Baseline Controls</h1>
        </div>
    </div>
</div>

<div class="container">
    <nav aria-label="Page Navigation">
        <ul class="pagination justify-content-end">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a href="?action=edit_baseline&page=<?= $page - 1 ?>" class="page-link">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a href="#" class="page-link">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $tot_pg); $i++): ?>
                <li class="page-item">
                    <a href="?action=edit_baseline&page=<?= $i ?>"<?= $i == $page ? ' class="page-link active"' : ' class="page-link"' ?>><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $tot_pg): ?>
                <li class="page-item"><a class="page-link" href="?action=edit_baseline&page=<?= $page + 1 ?>">Next</a></li>
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
                <th>Baseline</th>
                <th>Control</th>
                <th>Status</th>
                <th>POAM</th>
                <th>Date Started</th>
                <th>Last Updated</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($ctrls as $ctrl): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ctrl->getBaselineID()); ?></td>
                    <td><?php echo htmlspecialchars($ctrl->getCtrlID()); ?></td>
                    <td><?php echo htmlspecialchars($ctrl->getStatus()); ?></td>
                    <td><?php echo htmlspecialchars($ctrl->poamStatus()); ?></td>
                    <td><?php echo htmlspecialchars($ctrl->getStartDate()); ?></td>
                    <td><?php echo htmlspecialchars($ctrl->getModDate()); ?></td>
                    <td>
                        <form action="." method="post" class="m-0">
                            <input type="hidden" name="action" value="mod_poam">
                            <input type="hidden" name="blc_id" value="<?php echo $ctrl->getBaselineCtrlID(); ?>">
                            <input type="hidden" name="bl_id" value="<?php echo $ctrl->getBaselineID(); ?>">
                            <div class="input-group">
                                <?php if($ctrl->hasPOAM()): ?>
                                    <!-- <input type="submit" name="submit" value="View" class="btn btn-sm btn-secondary"> -->
                                    <input type="submit" name="submit" value="Edit" class="btn btn-sm btn-secondary">
                                <?php else: ?>
                                    <input type="submit" name="submit" value="Start" class="btn btn-sm btn-primary">
                                <?php endif; ?>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">Notes: <?php echo htmlspecialchars($ctrl->getComments()); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <nav aria-label="Page Navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a href="?action=edit_baseline&page=<?= $page - 1 ?>" class="page-link">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a href="#" class="page-link">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $tot_pg); $i++): ?>
                <li class="page-item">
                    <a href="?action=edit_baseline&page=<?= $i ?>"<?= $i == $page ? ' class="page-link active"' : ' class="page-link"' ?>><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $tot_pg): ?>
                <li class="page-item"><a class="page-link" href="?action=edit_baseline&page=<?= $page + 1 ?>">Next</a></li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a href="#" class="page-link">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

</div>

<?php include 'views/footer.php'; ?>