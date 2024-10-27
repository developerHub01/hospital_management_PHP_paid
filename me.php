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
      <section class="shadow table-responsive mb-4">
        <table class="table table-hover table-striped text-center" id="user-table">
          <tbody>
            <!-- <tr>
              <th scope="row">ID</th>
              <td>1</td>
            </tr>
            <tr>
              <th scope="row">Name</th>
              <td>user1</td>
            </tr>
            <tr>
              <th scope="row">Email</th>
              <td>user1@gmail.com</td>
            </tr>
            <tr>
              <th scope="row">Date of Birth</th>
              <td>2000-01-01</td>
            </tr>
            <tr>
              <th scope="row">Gender</th>
              <td>male</td>
            </tr>
            <tr>
              <th scope="row">Role</th>
              <td>super_admin</td>
            </tr> -->
          </tbody>
        </table>
      </section>
      <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateMeModel">
          Update Me
        </button>
      </div>
    </section>

    <div class="modal fade" id="updateMeModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Me</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <input type="text" class="form-control" id="id" hidden>
              <div class="mb-3">
                <label for="name" class="col-form-label">Name:</label>
                <input type="text" class="form-control" id="name">
              </div>
              <div class="mb-3">
                <label for="email" class="col-form-label">Email:</label>
                <input type="email" class="form-control" id="email">
              </div>
              <div class="mb-3">
                <label for="dob" class="col-form-label">DOB:</label>
                <input type="date" class="form-control" id="dob">
              </div>
              <div class="mb-3">
                <label for="password" class="col-form-label">Password:</label>
                <input type="password" class="form-control" id="password">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="update-me-btn" data-bs-dismiss="modal">Update</button>
          </div>
        </div>
      </div>
    </div>
  </main>
</section>

<?php
include "./partials/footer.php";
?>

<script>
  const loadMe = () => {
    $.ajax({
      url: "/api/v1/user/me.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#user-table tbody");
        tableBody.empty();

        const { id, name, email, dob, gender, role } = data;

        console.log(res);


        tableBody.append(`
          <tr>
            <th scope="row">ID</th>
            <td id="id">${id}</td>
          </tr>
          <tr>
            <th scope="row">Name</th>
            <td>${name}</td>
          </tr>
          <tr>
            <th scope="row">Email</th>
            <td>${email}</td>
          </tr>
          <tr>
            <th scope="row">Date of Birth</th>
            <td>${dob}</td>
          </tr>
          <tr>
            <th scope="row">Gender</th>
            <td>${gender}</td>
          </tr>
          <tr>
            <th scope="row">Role</th>
            <td>${role? role: ""}</td>
          </tr>
        `);

        $("#updateMeModel #id").val(id);
        $("#updateMeModel #name").val(name);
        $("#updateMeModel #email").val(email);
        $("#updateMeModel #dob").val(dob);
      }
    })
  }

  const updateMe = () => {
    $(document).on('click', "#update-me-btn", (e) => {
      const id = $("#updateMeModel #id").val();
      const name = $("#updateMeModel #name").val();
      const email = $("#updateMeModel #email").val();
      const dob = $("#updateMeModel #dob").val();

      $("table #id").val('');
      $("#updateMeModel #name").val('');
      $("#updateMeModel #email").val('');
      $("#updateMeModel #dob").val('');

      console.log(JSON.stringify({
        id,
        name,
        email,
        dob
      }));

      $.ajax({
        url: "/api/v1/user/update.php",
        method: "POST",
        data: JSON.stringify({
          id,
          name,
          email,
          dob
        }),
        success: (data) => {
          if (data?.success) loadMe();

          alert(data.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }


  $(document).ready(() => {
    loadMe();
    updateMe();
  })
</script>