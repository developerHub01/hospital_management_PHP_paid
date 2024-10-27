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
        <table class="table table-hover table-striped text-center" id="admins-table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Id</th>
              <th scope="col">User Id</th>
              <th scope="col">Role</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">DOB</th>
              <th scope="col">Gender</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>
            <!-- <tr>
            <th scope="row">1</th>
            <td>Id</td>
            <td>User Id</td>
            <td>Role</td>
            <td>Name</td>
            <td>Email</td>
            <td>DOB</td>
            <td>Gender</td>
            <td>
              <button type="button" class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr> -->
          </tbody>
        </table>
      </section>
      <div id="action-section">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdminModel">
          Create Admin
        </button>
      </div>
    </section>

    <div class="modal fade" id="createAdminModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create new admin</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="user-id" class="col-form-label">User id:</label>
                <input type="text" class="form-control" id="user-id">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="create-admin-btn" data-bs-dismiss="modal">Create</button>
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
  const loadAdmins = () => {
    $.ajax({
      url: "/api/v1/user/me.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#user-table tbody");
        tableBody.empty();

        const { id, name, email, dob, gender, role } = data;

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
            <td>${role}</td>
          </tr>
        `);

        $("#updateMeModel #id").val(id);
        $("#updateMeModel #name").val(name);
        $("#updateMeModel #email").val(email);
        $("#updateMeModel #dob").val(dob);
      }
    })


    $.ajax({
      url: "/api/v1/admin/all.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#admins-table tbody");
        tableBody.empty();

        data.forEach(({ id, user_id, role, name, email, gender, dob }, index) => {
          tableBody.append(`
            <tr>
              <th scope="row">${index + 1}</th>
              <td>${id}</td>
              <td>${user_id}</td>
              <td>${role}</td>
              <td>${name}</td>
              <td>${email}</td>
              <td>${dob}</td>
              <td>${gender}</td>
              <td>
                <button type="button" data-id="${id}" class="btn-delete btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          `);
        })
      }
    })
  }

  const createAdmin = () => {
    $(document).on('click', "#create-admin-btn", (e) => {
      const userId = $("#createAdminModel #user-id").val();

      $("#createAdminModel #user-id").val('');

      $.ajax({
        url: "/api/v1/admin/create.php",
        method: "POST",
        data: JSON.stringify({
          user_id: userId,
        }),
        success: (data) => {
          if (data?.success) loadAdmins();

          alert(data.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }

  const deleteAdmin = () => {
    $("#admins-table tbody").on('click', ".btn-delete", (e) => {
      const adminId = $(e.currentTarget).data("id");
      $.ajax({
        url: "/api/v1/admin/delete.php",
        method: "DELETE",
        data: JSON.stringify({ admin_id: adminId }),
        success: (res) => {
          if (res.success) loadAdmins();

          alert(res.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }

  const handleAction = () => {
    $.ajax({
      url: "/api/v1/user/me.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#user-table tbody");
        tableBody.empty();

        const { id, name, email, dob, gender, role } = data;

        if (role === "super_admin") return;

        $("#action-section").html("")
        $(".btn-delete").remove();
      }
    })
  }

  $(document).ready(() => {
    loadAdmins();
    createAdmin();
    deleteAdmin();
    handleAction();
  })
</script>