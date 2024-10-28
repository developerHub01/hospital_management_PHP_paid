<?php
include "./partials/header.php";
include "./config/dotenv.php";

if (isset($_COOKIE['access_token'])) {
  header("Location: /");
  exit;
}
?>

<section class="vh-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6 text-black d-flex flex-column justify-content-center align-items-lg-start">
        <div class="px-5 ms-xl-4">
          <span class="h1 fw-bold mb-0">Prime Hospital</span>
        </div>

        <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

          <form style="width: 23rem;" id="login-form">
            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>

            <div data-mdb-input-init class="form-outline mb-4">
              <label class="form-label" for="form2Example18">Email address</label>
              <input type="email" name="email" id="form2Example18" class="form-control form-control" />
            </div>

            <div data-mdb-input-init class="form-outline mb-4">
              <label class="form-label" for="form2Example28">Password</label>
              <input type="password" name="password" id="form2Example28" class="form-control form-control" />
            </div>

            <div class="pt-1 mb-4">
              <input class="btn btn-primary" type="submit" value="Login" />
            </div>

            <p>Don't have an account? <a href="/register.php" class="link-parimary">Register here</a></p>

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
  const handleLogin = () => {
    $("#login-form").on("submit", function (e) {
      e.preventDefault();

      const formSerializeData = $(this).serializeArray();

      const formData = {};

      for (const { name, value } of formSerializeData) {
        formData[name] = value.trim();
      }

      $.ajax({
        url: "/api/v1/auth/login.php",
        method: "POST",
        data: JSON.stringify(formData),
        success: (response) => {
          if (!response.success) return

          window.location.href = "/";
        }
      })
    })
  }

  const handleAuth = () => {
    $.ajax({
      url: "/api/v1/user/me.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (data) window.location.href = "/";
      },
      error: (error) => {
        if(error.responseJSON?.message) alert(error.responseJSON?.message);
      }
    })
  }


  $(document).ready(() => {
    handleAuth();
    handleLogin();
  })
</script>