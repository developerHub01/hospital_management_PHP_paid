<?php
include "./partials/header.php";
include "./config/dotenv.php";
?>

<section class="d-flex">
  <?php
  include "./partials/sidebar.php";
  ?>
  <main class="container">
    <div class="row row-cols-1 row-cols-md-2 g-2 short-cat">
      <div class="col-xl-8 col-md-6 col-sm-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Admins</h6>
            <h5 class="card-number">5</h5>
            <a href="/admins.php" class="btn btn-primary btn-sm">Go to Admin</a>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6 col-sm-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Doctors</h6>
            <h5 class="card-number">5</h5>
            <a href="/doctors.php" class="btn btn-primary btn-sm">Go to Doctor</a>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6 col-sm-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Patients</h6>
            <h5 class="card-number">5</h5>
            <a href="/patients.php" class="btn btn-primary btn-sm">Go to Patients</a>
          </div>
        </div>
      </div>
      <div class="col-xl-8 col-md-6 col-sm-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Wards</h6>
            <h5 class="card-number">5</h5>
            <a href="/wards.php" class="btn btn-primary btn-sm">Go to Wards</a>
          </div>
        </div>
      </div>
    </div>
  </main>
</section>

<?php
include "./partials/footer.php";

?>