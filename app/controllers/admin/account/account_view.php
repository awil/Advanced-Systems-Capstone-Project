<?php 
    include 'views/header.php'; 
    ?>



<div class="container">
    <div class="row my-5">
        <div class="card bg-light shadow">
            <div class="card-body">
            <h2 class="mb-4"><?php echo htmlspecialchars($current_admin->getFirstName());?>'s Profile</h2>
            <form action="." method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="adm_id" value="<?php echo $current_admin->getID(); ?>">

            <div class="pl-lg-4">
                <div class="row mb-2">
                    <div class="col-lg-6">
                        <div class="input-group">
                        <label class="input-group-text" for="adm_first">First</label>
                        <input type="text" id="adm_first" class="form-control form-control-alternative" name="adm_first" value="<?php echo htmlspecialchars($current_admin->getFirstName()); ?>"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                        <label class="input-group-text" for="adm_last">Last</label>
                        <input type="text" id="adm_last" class="form-control form-control-alternative" name="adm_last" value="<?php echo htmlspecialchars($current_admin->getLastName()); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-6">
                        <div class="input-group">
                        <label class="input-group-text" for="adm_title">Title</label>
                        <input type="text" name="adm_title" id="adm_title" class="form-control form-control-alternative" value="<?php echo htmlspecialchars($current_admin->getTitle()); ?>"/>                        
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                        <label class="input-group-text" for="adm_email">Email</label>
                        <input type="text" name="adm_email" id="adm_email" class="form-control form-control-alternative" value="<?php echo htmlspecialchars($current_admin->getEmail()); ?>"/>                        
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="input-group">
                        <label class="input-group-text" for="adm_password_1">Password</label>
                        <input type="password" name="adm_password_1" id="adm_password_1" class="form-control form-control-alternative" value=""/>                        
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                        <label class="input-group-text" for="adm_password_2">Confirm Password</label>
                        <input type="password" name="adm_password_2" id="adm_password_2" class="form-control form-control-alternative" value=""/>                        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                        <input class="btn btn-sm btn-primary" type="submit" value="Update" />
                        </div>
                    </div>
                </div>
            </div>

            </form>

            </div>
        </div>
    </div>
</div>


<?php include 'views/footer.php'; ?>