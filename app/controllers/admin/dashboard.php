<?php include 'views/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="row my-4 justify-content-center">
        <div class="justify-content-center">
        <h1>Welcome to your dashboard, <?php echo htmlspecialchars($current_admin->getFirstName());?>!</h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php foreach($clients as $client): ?>
                <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                    <div class="d-flex justify-content-between mb-0 pb-0">
                        <h5 class="p-4 mb-0"><?php echo htmlspecialchars($client->getName());?></h5>
                        <form action="." method="post" class="p-4">
                            <input type="hidden" name="action" value="select_client">
                            <input type="hidden" name="cl_id" value="<?php echo $client->getID(); ?>">
                            <input type="hidden" name="co_id" value="<?php echo $client->getCompany(); ?>">
                            <input type="submit" value="Select" class="btn btn-sm btn-primary">
                        </form>
                    </div>
                    <div class="card-footer">
                        <small class="text-body-secondary">9 mins</small>
                    </div>
                    </div>
                </div>
                </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'views/footer.php'; ?>