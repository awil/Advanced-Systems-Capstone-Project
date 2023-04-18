<?php 
    include 'header.php'; 
    ?>

<div class="container-fluid justify-content-between align-items-center py-3 my-4">
    <div class="form-signin col-md-8 col-lg-6 col-xl-4 m-auto text-center">
        <h1 class="h3 mb-3 fw-normal">Login</h1>
            <form class="" action="." method="post" id="login_form">
                <input type="hidden" name="action" value="login">
                
                <div class="form-floating mb-3">
                    <input type="text" name="u_email" class="form-control" id="floatingInput" value="<?php echo htmlspecialchars($u_email); ?>" size="30">
                    <?php echo $fields->getField('u_email')->getHTML(); ?>
                    <label for="floatingInput">Email:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="u_password" class="form-control" id="floatingPassword" size="30">
                    <?php echo $fields->getField('u_password')->getHTML(); ?>
                    <label for="floatingPassword">Password:</label>
                </div>

                <button class="w-100 btn btn-lg btn-primary mb-3" type="submit" value="Login">Sign in</button>
                <?php if (!empty($u_password_message)) : ?>         
                <span class="alert alert-primary" role="alert"><?php echo htmlspecialchars($u_password_message); ?></span><br>
                <?php endif; ?>
            </form>
    </div>
</div>


<?php include 'footer.php'; ?>

