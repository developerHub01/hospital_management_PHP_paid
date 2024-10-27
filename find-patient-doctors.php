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
          <input type="text" class="form-control" id="patient-id" placeholder="Enter patient id">
          <button class="btn btn-primary btn-lg" type="button" id="patient-id-btn">Search</button>
        </div>
      </div>
      <section class="shadow table-responsive mb-4">
        <table class="table table-hover table-striped text-center" id="doctors-table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">User Id</th>
              <th scope="col">Doctor Id</th>
              <th scope="col">Name</th>
              <th scope="col">Specialization</th>
              <th scope="col">Email</th>
              <th scope="col">DOB</th>
              <th scope="col">Gender</th>
            </tr>
          </thead>
          <tbody>
            <!-- <tr>
            <th scope="row">1</th>
            <td>User Id</td>
            <td>Doctor Id</td>
            <td>Name</td>
            <td>Specialization</td>
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
  const loadDoctors = () => {
    $("#patient-id-btn").on("click", () => {
      const patientId = $("#patient-id").val();

      $.ajax({
        url: "/api/v1/patient_doctor/doctors_by_patent_id.php",
        method: "POST",
        data: JSON.stringify({
          patient_id: patientId
        }),
        success: (res) => {
          const { data } = res;

          if (!data) return;

          const tableBody = $("#doctors-table tbody");
          tableBody.empty();

          data.forEach(({ user_id, doctor_id, specialization, name, email, dob, gender }, index) => {
            tableBody.append(`
              <tr>
                <th scope="row">#</th>
                <td>${user_id}</td>
                <td>${doctor_id}</td>
                <td>${name}</td>
                <td>${specialization}</td>
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
    loadDoctors();
  })
</script>