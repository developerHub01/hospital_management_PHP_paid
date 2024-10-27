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
        <table class="table table-hover table-striped text-center" id="patient-doctor-table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Id</th>
              <th scope="col">Patient Id</th>
              <th scope="col">Doctor Id</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </section>
      <div id="action-section">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPatientDoctorModel">
          Create Patient Doctor
        </button>
      </div>
    </section>
  </main>

  <section id="action-popups">
    <div class="modal fade" id="createPatientDoctorModel" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                <label for="patient-id" class="col-form-label">Patient id:</label>
                <input type="text" class="form-control" id="patient-id">
              </div>
              <div class="mb-3">
                <label for="doctor-id" class="col-form-label">Doctor id:</label>
                <input type="text" class="form-control" id="doctor-id">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="create-patient-doctor-btn"
              data-bs-dismiss="modal">Create</button>
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
  const loadPatientDoctor = () => {
    $.ajax({
      url: "/api/v1/patient_doctor/all.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#patient-doctor-table tbody");
        tableBody.empty();

        data.forEach(({ id, patient_id, doctor_id }, index) => {
          tableBody.append(`
            <tr>
              <th scope="row">${index + 1}</th>
              <td>${id}</td>
              <td>${patient_id}</td>
              <td>${doctor_id}</td>
              <td>
                <button type="button" data-id="${id}" data-patient-id="${patient_id}" data-doctor-id="${doctor_id}" class="btn-delete btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          `);
        })
      }
    })
  }

  const deletePatientDoctor = () => {
    $("#patient-doctor-table tbody").on('click', ".btn-delete", (e) => {
      const patientId = $(e.currentTarget).data("patient-id");
      const doctorId = $(e.currentTarget).data("doctor-id");

      console.log({
        patient_id: patientId,
        doctor_id: doctorId
      });


      $.ajax({
        url: "/api/v1/patient_doctor/delete.php",
        method: "DELETE",
        data: JSON.stringify({
          patient_id: patientId,
          doctor_id: doctorId
        }),
        success: (res) => {
          if (res.success) loadPatientDoctor();

          else alert(res.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }

  const createPatientDoctor = () => {
    $(document).on('click', "#create-patient-doctor-btn", (e) => {
      const patientId = $("#createPatientDoctorModel #patient-id").val();
      const doctorId = $("#createPatientDoctorModel #doctor-id").val();

      $("#createPatientDoctorModel #patient-id").val('');
      $("#createPatientDoctorModel #doctor-id").val('');

      $.ajax({
        url: "/api/v1/patient_doctor/create.php",
        method: "POST",
        data: JSON.stringify({
          patient_id: patientId,
          doctor_id: doctorId
        }),
        success: (data) => {
          if (data?.success) loadPatientDoctor();

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
    loadPatientDoctor();
    deletePatientDoctor();
    createPatientDoctor();
    handleAction();
  })
</script>