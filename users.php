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
      <table class="table table-hover table-striped text-center" id="users-table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Id</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">DOB</th>
            <th scope="col">Gender</th>
          </tr>
        </thead>
        <tbody>
          <!-- <tr>
            <th scope="row">1</th>
            <td>Id</td>
            <td>Name</td>
            <td>Email</td>
            <td>DOB</td>
            <td>Gender</td>
          </tr> -->
        </tbody>
      </table>
    </section>
  </main>
</section>

<?php
include "./partials/footer.php";
?>

<script>
  const loadUsers = () => {
    $.ajax({
      url: "/api/v1/user/all.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#users-table tbody");
        tableBody.empty();

        data.forEach(({ id, name, email, gender, dob }, index) => {

          tableBody.append(`
            <tr>
              <th scope="row">${index + 1}</th>
              <td>${id}</td>
              <td>${name}</td>
              <td>${email}</td>
              <td>${dob}</td>
              <td>${gender}</td>
            </tr>
          `);
        })
      }
    })
  }

  $(document).ready(() => {
    loadUsers();
  })
</script>