<?php include 'views/header.php'; ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg mt-5">
                <div class="card-header text-ng-primary">Database Error</div>
                <div class="card-body">
                    <p>A database error occurred.</p>
                    <p>Error message: <?php echo $error_message; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?>