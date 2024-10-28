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
        <table class="table table-hover table-striped text-center" id="wards-table">
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
      <div id="action-section">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWardModel">
          Create Wards
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateWardModel">
          Update Wards
        </button>
      </div>
    </section>
  </main>

  <section id="action-popups">
    <div class="modal fade" id="createWardModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create new ward</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="capacity" class="col-form-label">Capacity:</label>
                <input type="text" class="form-control" id="capacity">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="create-ward-btn" data-bs-dismiss="modal">Create</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="updateWardModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Update ward</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="ward-id" class="col-form-label">Ward id:</label>
                <input type="text" class="form-control" id="ward-id">
              </div>
              <div class="mb-3">
                <label for="capacity" class="col-form-label">Capacity:</label>
                <input type="text" class="form-control" id="capacity">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="update-ward-btn" data-bs-dismiss="modal">Update</button>
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
                <button type="button" data-id="${id}" class="btn-delete btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          `);
        })
      }
    })
  }

  const deleteWard = () => {
    $("#wards-table tbody").on('click', ".btn-delete", (e) => {
      const wardId = $(e.currentTarget).data("id");
      $.ajax({
        url: "/api/v1/ward/delete.php",
        method: "DELETE",
        data: JSON.stringify({ ward_id: wardId }),
        success: (res) => {
          if (res.success) loadWards();

          alert(res.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }

  const createWard = () => {
    $(document).on('click', "#create-ward-btn", (e) => {
      const wardCapacity = $("#createWardModel #capacity").val();

      $("#createWardModel #capacity").val('');

      $.ajax({
        url: "/api/v1/ward/create.php",
        method: "POST",
        data: JSON.stringify({ capacity: wardCapacity }),
        success: (data) => {
          if (data?.success) loadWards();

          alert(data.message);
        },
        error: (error) => {
          alert(error?.responseJSON?.message || "something went wrong");
        }
      })
    })
  }

  const updateWard = () => {
    $(document).on('click', "#update-ward-btn", (e) => {
      const wardId = $("#updateWardModel #ward-id").val();
      const wardCapacity = $("#updateWardModel #capacity").val();

      $("#updateWardModel #ward-id").val('');
      $("#updateWardModel #capacity").val('');

      $.ajax({
        url: "/api/v1/ward/update.php",
        method: "POST",
        data: JSON.stringify({
          ward_id: wardId,
          capacity: wardCapacity
        }),
        success: (data) => {
          if (data?.success) loadWards();

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
      }, error: (error) => {
        if (error.responseJSON?.message) alert(error.responseJSON?.message);
      }
    })
  }


  $(document).ready(() => {
    loadWards();
    deleteWard();
    updateWard();
    createWard();
    handleAction();
  })
</script>