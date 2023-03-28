<!-- Nav Header - Begin -->
<div class="sticky-top bg-dark">
    <div class="container-xxl">
        <div class="">
                <a href="" class="text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="250" height="100" class="" viewBox="0 0 400 150">
                        <?php include('views/img/logo.php'); ?>
                    </svg>
                </a>
        </div>
        <?php
            /* Show menu items if user is logged in. */
            if (isset($_SESSION['adm_id'])) {
                include ('nav_bar_admin.php');
            } else if (isset($_SESSION['cl_id'])) {
                include ('nav_bar_client.php');
            } else {
                include ('nav_bar.php');
            }
        ?>
    </div>
</div>
<!-- Nav Header - End -->