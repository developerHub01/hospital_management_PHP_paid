<?php
include "./partials/header.php";
include "./config/dotenv.php";
?>

<section class="d-flex">
  <?php
  include "./partials/sidebar.php";
  ?>
  <main class="wrapper">
    <section class="container py-5">
      <div class="row row-cols-1 row-cols-md-2 g-2 short-cat">
        <div class="col-xl-8 col-md-6 col-sm-12">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Admins</h6>
              <h5 class="card-number" id="admins-count">5</h5>
              <a href="/admins.php" class="btn btn-primary btn-sm">Go to Admin</a>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Doctors</h6>
              <h5 class="card-number" id="doctors-count">5</h5>
              <a href="/doctors.php" class="btn btn-primary btn-sm">Go to Doctor</a>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Patients</h6>
              <h5 class="card-number" id="patients-count">5</h5>
              <a href="/patients.php" class="btn btn-primary btn-sm">Go to Patients</a>
            </div>
          </div>
        </div>
        <div class="col-xl-8 col-md-6 col-sm-12">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Wards</h6>
              <h5 class="card-number" id="wards-count">5</h5>
              <a href="/wards.php" class="btn btn-primary btn-sm">Go to Wards</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</section>

<?php
include "./partials/footer.php";
?>

<script>
  const loadCounts = (countType) => {

    let url = "";
    let domId = "";

    switch (countType) {
      case "doctors-count":
        url = "/api/v1/doctor/all.php";
        domId = "#doctors-count";
        break;
      case "patients-count":
        url = "/api/v1/patient/all.php";
        domId = "#patients-count";
        break;
      case "wards-count":
        url = "/api/v1/ward/all.php";
        domId = "#wards-count";
        break;
    }

    $.ajax({
      url,
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        $(domId).text(data.length);
      }
    })
  }

  $(document).ready(() => {
    loadCounts("admins-count");
    loadCounts("doctors-count");
    loadCounts("patients-count");
    loadCounts("wards-count");
  })
</script>