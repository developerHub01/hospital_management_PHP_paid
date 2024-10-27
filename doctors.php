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
      <table class="doctors-table table table-hover table-striped text-center">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">User Id</th>
            <th scope="col">Doctor Id</th>
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
            <td>Doctor Id</td>
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
      <a href="/create-doctor.php" class="btn btn-primary">Create Doctor</a>
    </div>
  </main>
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

        data.forEach(({ user_id, doctor_id, name, email, gender, dob }, index) => {
          tableBody.append(`
            <tr>
              <th scope="row">${index + 1}</th>
              <td>${user_id}</td>
              <td>${doctor_id}</td>
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
    loadDoctors();
  })
</script>