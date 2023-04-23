</main>
<?php
                        /* Show menu items if user is logged in. */
                        $account_url = $app_path.'controllers/?action=view_login';
                        $about_url = $app_path.'controllers/?action=about';
                        $logout_url = $app_path.'controllers/client?action=logout';
                    ?>
<!-- Footer - Begin -->
<div class="container">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <p class="col-md-4 mb-0 text-body-secondary">&copy; <?php echo date("Y"); ?> Team Orange</p>

    <a href="" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="250" height="100" class="" viewBox="0 0 400 150">
                    <?php include('img/logo.php'); ?>
                </svg>
            </a>

    <ul class="nav col-md-4 justify-content-end">
      <li class="nav-item"><a href="<?php echo $about_url ?>" class="nav-link px-2 text-body-secondary">About</a></li>
    </ul>
  </footer>
</div>
<!-- Footer - End -->

</body>
<!-- Body - End -->

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>

  <!-- Scripts -->

</html>