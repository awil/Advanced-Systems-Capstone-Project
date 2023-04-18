<?php include 'views/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="row my-4 justify-content-center">
        <div class="justify-content-center">
        <h1><?php echo $current_admin->getFirstName();?>'s Clients</h1>
        </div>
    </div>
</div>

<div class="container">

    <div class="table-responsive">
    <table class="table table-striped table-hover align-middle table-sm">
        <thead>
            <tr>
                <th>Company</th>
                <th>Representative</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client->getCompanyName($client->getID())); ?></td>
                    <td><?php echo htmlspecialchars($client->getName());?></td>
                    <td><form action="." method="post" class="m-0">
                            <input type="hidden" name="action" value="select_client">
                            <input type="hidden" name="cl_id" value="<?php echo $client->getID(); ?>">
                            <input type="hidden" name="co_id" value="<?php echo $client->getCompany(); ?>">
                            <input type="submit" value="Select" class="btn btn-sm btn-primary">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

</div>

<?php include 'views/footer.php'; ?>