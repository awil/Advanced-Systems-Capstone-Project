<?php include 'views/header.php'; ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg mt-5">
                <div class="card-header text-ng-primary">Database Error</div>
                <div class="card-body">
                <p>There was an error connecting to the database.</p>
                <p>The database must be installed as described in appendix A.</p>
                <p>The database must be running as described in chapter 1.</p>
                <p>Error message: <?php echo $error_message; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?>