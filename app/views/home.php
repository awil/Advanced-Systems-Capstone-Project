<?php 
    include 'views/header.php'; ?>

<?php
                        /* Show menu items if user is logged in. */
                        $account_url = $app_path.'controllers/?action=view_login';

                    ?>


<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      <div class="col-10 col-sm-8 col-lg-6">
        <img src="views/img/francesco-ungaro-8AaKYZZxoN4-unsplash.jpg" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
      </div>
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Welcome!</h1>
        <p class="lead">This is a prototype system for Authority to Operate as a Service (ATOaaS). The goal is to help automate Federal Risk Management Frameworks. To get started, click the login button and use the credentials you were given.</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="<?php echo $account_url ?>" class="btn btn-primary btn-lg px-4 me-md-2">Login</a>
        </div>
      </div>
    </div>
  </div>


<?php include 'views/footer.php'; ?>