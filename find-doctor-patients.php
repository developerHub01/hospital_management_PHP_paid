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
      <div class="shadow mb-4">
        <div class="input-group">
          <input type="text" class="form-control" id="doctor-id" placeholder="Enter doctor id">
          <button class="btn btn-primary btn-lg" type="button" id="doctor-id-btn">Search</button>
        </div>
      </div>
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
    </section>
  </main>
</section>

<?php
include "./partials/footer.php";
?>

<script>
  const loadPatients = () => {
    $("#doctor-id-btn").on("click", () => {
      const doctorId = $("#doctor-id").val();

      $.ajax({
        url: "/api/v1/patient_doctor/patients_by_doctor_id.php",
        method: "POST",
        data: JSON.stringify({
          doctor_id: doctorId
        }),
        success: (res) => {
          const { data } = res;

          if (!data) return;

          const tableBody = $("#patients-table tbody");
          tableBody.empty();

          data.forEach(({ user_id, patient_id, ward_id, name, email, dob, gender }, index) => {
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
            </tr>
            `);
          })
        }
      })

    })


  }

  $(document).ready(() => {
    loadPatients();
  })
</script>