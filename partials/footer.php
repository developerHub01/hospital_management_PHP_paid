<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="../public/js/bootstrap.js"></script>
<script src="../public/js/jquery.min.js"></script>
<script src="../public/js/index.js"></script>
<script>
  $(document).ready(() => {
    $('#logout-btn').on('click', (e) => {
      console.log("hello");

      $.ajax({
        url: '/api/v1/auth/logout.php',
        type: 'GET',
        success: (res) => {
          if (res.success)
            window.location.href = '/login.php';
          else alert(res.message || "something went wrong")
        },
        error: (err) => {
          alert(err.responseJSON.message || "something went wrong")
        }
      })
    })
  })
</script>
</body>

</html>