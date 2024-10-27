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
        <table class="table table-hover table-striped text-center" id="doctors-table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Doctor Id</th>
              <th scope="col">User Id</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Specialization</th>
              <th scope="col">DOB</th>
              <th scope="col">Gender</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>
            <!-- <tr>
            <th scope="row">1</th>
            <td>Doctor Id</td>
            <td>User Id</td>
            <td>Name</td>
            <td>Email</td>
            <td>Specialization</td>
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
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDoctorModel">
          Create Doctor
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateDoctorModel">
          Update Doctor
        </button>
      </div>
    </section>
  </main>

  <section id="action-popups">
    <div class="modal fade" id="createDoctorModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create new doctor</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="user-id" class="col-form-label">User id:</label>
                <input type="text" class="form-control" id="user-id">
              </div>
              <div class="mb-3">
                <label for="specialization" class="col-form-label">Specialization:</label>
                <input type="text" class="form-control" id="specialization">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="create-doctor-btn" data-bs-dismiss="modal">Create</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="updateDoctorModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Update doctor</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="id" class="col-form-label">id:</label>
                <input type="text" class="form-control" id="id">
              </div>
              <div class="mb-3">
                <label for="specialization" class="col-form-label">Specialization:</label>
                <input type="text" class="form-control" id="specialization">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="update-doctor-btn" data-bs-dismiss="modal">Update</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</section>

<?php
include "./partials/footer.php";
?>

<script>
  const loadDoctors = () => {
    $.ajax({
      url: "/api/v1/doctor/all.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#doctors-table tbody");
        tableBody.empty();

        data.forEach(({ user_id, doctor_id: id, name, email, specialization, gender, dob }, index) => {
          tableBody.append(`
            <tr>
              <th scope="row">${index + 1}</th>
              <td>${id}</td>
              <td>${user_id}</td>
              <td>${name}</td>
              <td>${email}</td>
              <td>${specialization}</td>
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

  const deleteDoctor = () => {
    $("#doctors-table tbody").on('click', ".btn-delete", (e) => {
      const doctorId = $(e.currentTarget).data("id");
      $.ajax({
        url: "/api/v1/doctor/delete.php",
        method: "DELETE",
        data: JSON.stringify({ doctor_id: doctorId }),
        success: (res) => {
          if (res.success) loadDoctors();

          alert(res.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }

  const createDoctor = () => {
    $(document).on('click', "#create-doctor-btn", (e) => {
      const userId = $("#createDoctorModel #user-id").val();
      const specialization = $("#createDoctorModel #specialization").val();

      $("#createDoctorModel #user-id").val('');
      $("#createDoctorModel #specialization").val('');

      $.ajax({
        url: "/api/v1/doctor/create.php",
        method: "POST",
        data: JSON.stringify({
          user_id: userId,
          specialization
        }),
        success: (res) => {
          if (res?.success) loadDoctors();

          alert(res.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }

  const updateDoctor = () => {
    $(document).on('click', "#update-doctor-btn", (e) => {
      const id = $("#updateDoctorModel #id").val();
      const specialization = $("#updateDoctorModel #specialization").val();

      $("#updateDoctorModel #id").val('');
      $("#updateDoctorModel #specialization").val('');

      $.ajax({
        url: "/api/v1/doctor/update.php",
        method: "POST",
        data: JSON.stringify({
          id,
          specialization
        }),
        success: (res) => {
          if (res?.success) loadDoctors();

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

        if (["super_admin", "admin"].includes(role)) return;

        $("#action-section").html("")
        $("#action-popups").html("")
        $(".btn-delete").remove();
      }
    })
  }

  $(document).ready(() => {
    loadDoctors();
    deleteDoctor();
    createDoctor();
    updateDoctor();
    handleAction()
  })
</script>