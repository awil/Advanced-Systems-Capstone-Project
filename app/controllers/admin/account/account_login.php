<?php 
    include 'views/header.php'; 
    ?>


<div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
    <div class="form-signin col-md-3 m-auto text-center">
        <h1 class="h3 mb-3 fw-normal">Admin Login</h1>
            <form action="." method="post" id="login_form">
                <input type="hidden" name="action" value="login">
                
                <div class="form-floating mb-3">
                    <input type="text" name="adm_email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php echo htmlspecialchars($adm_email); ?>" size="30">
                    <?php echo $fields->getField('adm_email')->getHTML(); ?>
                    <label for="floatingInput">Email:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="adm_password" class="form-control" id="floatingPassword" placeholder="Password" size="30">
                    <?php echo $fields->getField('adm_password')->getHTML(); ?>
                    <label for="floatingPassword">Password:</label>
                </div>

                <button class="w-100 btn btn-lg btn-primary mb-3" type="submit" value="Login">Sign in</button>
                <?php if (!empty($adm_password_msg)) : ?>         
                <span class="alert alert-primary" role="alert"><?php echo htmlspecialchars($adm_password_msg); ?></span><br>
                <?php endif; ?>
            </form>
            <form action="." method="post">
                <input type="hidden" name="action" value="view_register">
                <button class="w-100 btn btn-lg btn-secondary" type="submit" value="Register">Request Access</button>
            </form>
    </div>
</div>

<?php include 'views/footer.php'; ?>

