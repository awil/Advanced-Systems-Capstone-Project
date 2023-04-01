<?php include 'views/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="row my-4 justify-content-center">
        <div class="justify-content-center">
        <h1>Welcome to your dashboard, <?php echo $current_admin->getFirstName();?>!</h1>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
            <?php 
                foreach($clients as $client):
            ?>
        <div class="card-group">
            <div class="card text-bg-primary shadow text-center">
            <div class="card-header">
                <span class="fs-1">
                <?php echo $client->getFirstName().' '.$client->getLastName();?>
                </span>
            </div>
            <div class="card-body">
                <a href="" class="card-footer small text-decoration-none text-white">view client</a>
            </div>
            </div>
            </div>

            <?php endforeach; ?>

    </div>
</div>

<?php include 'views/footer.php'; ?>