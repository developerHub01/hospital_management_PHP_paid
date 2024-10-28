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
        <table class="table table-hover table-striped text-center" id="patients-table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">User Id</th>
              <th scope="col">Patient Id</th>
              <th scope="col">Ward Id</th>
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
            <td>User Id</td>
            <td>Patient Id</td>
            <td>Ward Id</td>
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
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPatientModel">
          Create Patient
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePatientModel">
          Update Patient
        </button>
      </div>
    </section>
  </main>

  <section id="action-popups">
    <div class="modal fade" id="createPatientModel" tabindex="-1" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create new patient</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="user-id" class="col-form-label">User id:</label>
                <input type="text" class="form-control" id="user-id">
              </div>
              <div class="mb-3">
                <label for="ward-id" class="col-form-label">Ward id:</label>
                <input type="text" class="form-control" id="ward-id">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="create-patient-btn"
              data-bs-dismiss="modal">Create</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="updatePatientModel" tabindex="-1" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Update patient</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="patient-id" class="col-form-label">Patient id:</label>
                <input type="text" class="form-control" id="patient-id">
              </div>
              <div class="mb-3">
                <label for="ward-id" class="col-form-label">Ward id:</label>
                <input type="text" class="form-control" id="ward-id">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="update-patient-btn"
              data-bs-dismiss="modal">update</button>
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
  const loadPatients = () => {
    $.ajax({
      url: "/api/v1/patient/all.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#patients-table tbody");
        tableBody.empty();

        data.forEach(({ user_id, patient_id, ward_id, name, email, gender, dob }, index) => {
          tableBody.append(`
            <tr>
              <th scope="row">${index + 1}</th>
              <td>${user_id}</td>
              <td>${patient_id}</td>
              <td>${ward_id}</td>
              <td>${name}</td>
              <td>${email}</td>
              <td>${dob}</td>
              <td>${gender}</td>
              <td>
                <button type="button" data-id="${patient_id}" class="btn-delete btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          `);
        })
      },
      error: (error) => {
        if (error.responseJSON?.message) alert(error.responseJSON?.message);
      }
    })
  }

  const deletePatient = () => {
    $("#patients-table tbody").on('click', ".btn-delete", (e) => {
      const patientId = $(e.currentTarget).data("id");
      $.ajax({
        url: "/api/v1/patient/delete.php",
        method: "DELETE",
        data: JSON.stringify({ patient_id: patientId }),
        success: (res) => {
          if (res.success) {
            loadPatients();
            alert(res.message);
          } else alert(res.message);
        },
        error: (error) => {
          if (error.responseJSON?.message) alert(error.responseJSON?.message);
        }
      })
    })
  }

  const createPatient = () => {
    $(document).on('click', "#create-patient-btn", (e) => {
      const userId = $("#createPatientModel #user-id").val();
      const wardId = $("#createPatientModel #ward-id").val();

      $("#createPatientModel #user-id").val('');
      $("#createPatientModel #ward-id").val('');

      $.ajax({
        url: "/api/v1/patient/create.php",
        method: "POST",
        data: JSON.stringify({
          user_id: userId,
          ward_id: wardId
        }),
        success: (data) => {
          if (data?.success) loadPatients();

          alert(data.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }

  const updatePatient = () => {
    $(document).on('click', "#update-patient-btn", (e) => {
      const patientId = $("#updatePatientModel #patient-id").val();
      const wardId = $("#updatePatientModel #ward-id").val();

      $("#updatePatientModel #patient_id").val('');
      $("#updatePatientModel #ward-id").val('');

      $.ajax({
        url: "/api/v1/patient/update.php",
        method: "POST",
        data: JSON.stringify({
          patient_id: patientId,
          ward_id: wardId
        }),
        success: (data) => {
          if (data?.success) loadPatients();

          alert(data.message);
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
    loadPatients();
    deletePatient();
    createPatient();
    updatePatient();
    handleAction();
  })
</script>