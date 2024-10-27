<?php
include "./partials/header.php";
include "./config/dotenv.php";
?>

<section class="d-flex">
  <?php
  include "./partials/sidebar.php";
  ?>
  <main class="container py-5">
    <section class="shadow table-responsive mb-4">
      <table class="patients-table table table-hover table-striped text-center">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">User Id</th>
            <th scope="col">Patient Id</th>
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
    <div>
      <a href="/create-ward.php" class="btn btn-primary">Create Patient</a>
    </div>
  </main>
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

        data.forEach(({ user_id, patient_id, name, email, gender, dob }, index) => {
          tableBody.append(`
            <tr>
              <th scope="row">${index + 1}</th>
              <td>${user_id}</td>
              <td>${patient_id}</td>
              <td>${name}</td>
              <td>${email}</td>
              <td>${dob}</td>
              <td>${gender}</td>
              <td>
                <button type="button" class="btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          `);
        })
      }
    })
  }

  $(document).ready(() => {
    loadPatients();
  })
</script>