<?php
include "./partials/header.php";
include "./config/dotenv.php";
?>

<section class="vh-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6 text-black d-flex flex-column justify-content-center align-items-lg-start">
        <div class="px-5 ms-xl-4">
          <span class="h1 fw-bold mb-0">Prime Hospital</span>
        </div>

        <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

          <form style="width: 23rem;" id="register-form">
            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register</h3>

            <div data-mdb-input-init class="form-outline mb-2">
              <label class="form-label" for="form2Example18">Name</label>
              <input type="name" name="name" id="form2Example18" class="form-control form-control" />
            </div>


            <div class="row mb-2">
              <div data-mdb-input-init class="form-outline col-6">
                <label class="form-label" for="form2Example18">Date of birth</label>
                <input type="date" name="dob" id="form2Example18" class="form-control form-control" />
              </div>
              <div data-mdb-input-init class="form-outline col-6">
                <label class="form-label" for="form2Example18">Gender</label>
                <select name="gender" id="gender" class="form-select">
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                </select>
              </div>
            </div>


            <div data-mdb-input-init class="form-outline mb-2">
              <label class="form-label" for="form2Example18">Email address</label>
              <input type="email" name="email" id="form2Example18" class="form-control form-control" />
            </div>

            <div data-mdb-input-init class="form-outline mb-2">
              <label class="form-label" for="form2Example28">Password</label>
              <input type="password" name="password" id="form2Example28" class="form-control form-control" />
            </div>

            <div class="pt-1 mb-2">
              <input class="btn btn-primary" type="submit" value="Register" />
            </div>

            <p>Already have an account? <a href="/login.php" class="link-parimary">Login here</a></p>
          </form>
        </div>
      </div>
      <div class="col-sm-6 px-0 d-none d-sm-block">
        <img src="public/images/login_signup_page.jpg" alt="Login image" class="w-100 vh-100"
          style="object-fit: cover; object-position: left;">
      </div>
    </div>
  </div>
</section>

<?php
include "./partials/footer.php";
?>

<script>
  $(document).ready(() => {
    $("#register-form").on("submit", function (e) {
      e.preventDefault();

      const formSerializeData = $(this).serializeArray();

      const formData = {};

      for (const { name, value } of formSerializeData) {
        formData[name] = value.trim();
      }

      $.ajax({
        url: "/api/v1/user/create.php",
        method: "POST",
        data: JSON.stringify(formData),
        success: (response) => {
          console.log(response);

          if (!response.success) return

          window.location.href = "/login.php";
        }
      })
    })
  })
</script>