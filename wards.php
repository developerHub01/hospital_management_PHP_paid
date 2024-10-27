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
      <table class="wards-table table table-hover table-striped text-center">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Id</th>
            <th scope="col">Capacity</th>
            <th scope="col">Current Patients Count</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          <!-- <tr>
            <th scope="row">1</th>
            <td>1</td>
            <td>5</td>
            <td>3</td>
            <td>
              <button type="button" class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr> -->
        </tbody>
      </table>
    </section>
    <div>
      <a href="/create-ward.php" class="btn btn-primary">Create Wards</a>
    </div>
  </main>
</section>

<?php
include "./partials/footer.php";
?>

<script>
  const loadWards = () => {
    $.ajax({
      url: "/api/v1/ward/all.php",
      method: "GET",
      success: (res) => {
        const { data } = res;

        if (!data) return;

        const tableBody = $("#wards-table tbody");
        tableBody.empty();

        data.forEach(({ id, capacity, current_patients_count }, index) => {

          tableBody.append(`
            <tr>
              <th scope="row">${index + 1}</th>
              <td>${id}</td>
              <td>${capacity}</td>
              <td>${current_patients_count}</td>
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
    loadWards();
  })
</script>