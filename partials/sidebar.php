<?php
$base_name = basename($_SERVER['PHP_SELF']);

$activeTab = explode(".php", $base_name)[0];

$activeTab = $activeTab === "index" ? "home" : $activeTab;

?>


<aside class="sidebar bg-primary">
  <div class="wrapper">
    <div class="top">
      <div class="logo-container">
        <a href="/" class="logo">
          Prime Hospital
        </a>
      </div>
      <div class="sidebar-list">
        <a href="/" class="<?php echo $activeTab === "home" ? "active" : "" ?>">
          home
        </a>
        <a href="/users.php" class="<?php echo $activeTab === "users" ? "active" : "" ?>">
          users
        </a>
        <a href="/admins.php" class="<?php echo $activeTab === "admins" ? "active" : "" ?>">
          admins
        </a>
        <a href="/doctors.php" class="<?php echo $activeTab === "doctors" ? "active" : "" ?>">
          doctors
        </a>
        <a href="/patients.php" class="<?php echo $activeTab === "patients" ? "active" : "" ?>">
          patients
        </a>
        <a href="/wards.php" class="<?php echo $activeTab === "wards" ? "active" : "" ?>">
          wards
        </a>
        <a href="/patient-doctor.php" class="<?php echo $activeTab === "patient-doctor" ? "active" : "" ?>">
          patient doctor
        </a>
        <a href="/find-doctor-patients.php" class="<?php echo $activeTab === "find-doctor-patients" ? "active" : "" ?>">
          find doctor's patients
        </a>
        <a href="/find-patient-doctors.php" class="<?php echo $activeTab === "find-patient-doctors" ? "active" : "" ?>">
          find patient's doctors
        </a>
        <a href="/me.php" class="<?php echo $activeTab === "me" ? "active" : "" ?>">
          Me
        </a>
      </div>
    </div>
    <div class="sidebar-list">
      <button id="logout-btn">Logout</button>
    </div>
  </div>
</aside>