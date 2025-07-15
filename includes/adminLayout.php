<?php
// Start session and output buffering
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

// Redirect to login if user is not admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /vrms-project/views/auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - VRMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { overflow-x: hidden; }

    #sidebar {
      width: 250px;
      height: 100vh;
      background-color: #343a40;
      position: fixed;
      top: 0;
      left: -250px;
      z-index: 1060;
      transition: left 0.3s ease;
      color: white;
      padding: 1rem;
    }

    #sidebar.show { left: 0; }
    #sidebar .nav-link { color: white; }
    #sidebar .nav-link.active { background-color: #495057; }

    #topbar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 60px;
      background-color: white;
      border-bottom: 1px solid #dee2e6;
      z-index: 1050;
    }

    .avatar {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      object-fit: cover;
      cursor: pointer;
    }

    .avatar-lg {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      object-fit: cover;
    }

    .profile-modal {
      position: absolute;
      top: 55px;
      right: 0;
      width: 260px;
      z-index: 9999;
      background-color: white;
      border: 1px solid #dee2e6;
      display: none;
    }

    .profile-modal.show { display: block; }
    .profile-modal .dropdown-item { padding: 10px 16px; }

    #overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1040;
    }

    #overlay.show { display: block; }

    #content {
      padding: 1.5rem;
      margin-top: 70px;
      transition: margin-left 0.3s ease;
    }

    #content.shifted { margin-left: 250px; }

    #hideSidebarBtn,
    #showSidebarBtn {
      position: fixed;
      top: 10px;
      z-index: 1065;
      background-color: #343a40;
      color: white;
      border: none;
      padding: 4px 10px;
      font-size: 1.25rem;
      opacity: 0.9;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
      border-radius: 0 5px 5px 0;
    }

    #hideSidebarBtn { left: 235px; }
    #showSidebarBtn { left: -15px; }

    @media (max-width: 767px) {
      #hideSidebarBtn, #showSidebarBtn { display: none !important; }
      #toggleArrow { display: block !important; }
    }

    @media (min-width: 768px) {
      #toggleArrow { display: none !important; }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
  <form class="mb-3">
    <input type="text" class="form-control form-control-sm" placeholder="Search..."/>
  </form>
  <ul class="nav flex-column mb-3">
    <li class="nav-item"><a href="/vrms-project/views/dashboard/admin.php" class="nav-link">🏠 Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="../../views/appointment/manage.php">Manage Appointments</a></li>
    <li class="nav-item"><a href="/vrms-project/views/pets/manage_pets.php" class="nav-link">🐾 Manage Pets</a></li>
    <li class="nav-item"><a href="/vrms-project/views/appointments/manage_appointments.php" class="nav-link">📅 Appointments</a></li>
    <li class="nav-item"><a href="/vrms-project/views/reports/index.php" class="nav-link">📁 Reports</a></li>
  </ul>
  <hr class="bg-secondary" />
</nav>

<!-- Topbar -->
<nav id="topbar" class="d-flex justify-content-between align-items-center px-3 shadow-sm">
  <div class="d-flex align-items-center gap-3">
    <button id="toggleArrow" class="btn p-1">&#9776;</button>
    <h4 class="fw-bold text-success">VRMS Admin</h4>
  </div>
  <div class="position-relative">
    <img src="/vrms-project/images/default-avatar.jpg" class="avatar" id="profileAvatar"/>
    <div class="profile-modal shadow rounded" id="profileModal">
      <div class="d-flex align-items-center gap-3 p-3 border-bottom">
        <img src="/vrms-project/images/default-avatar.jpg" class="avatar-lg" />
        <div>
          <div class="fw-bold"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
          <div class="text-muted small"><?= htmlspecialchars($_SESSION['user']['email']) ?></div>
          <div class="text-primary small bg-light px-2 py-1 rounded d-inline"><?= htmlspecialchars(ucfirst($_SESSION['user']['role'])) ?></div>
        </div>
      </div>
      <div class="p-3">
        <a class="dropdown-item mb-1" href="/vrms-project/users/edit_user.php">My Profile</a>
        <a class="dropdown-item" href="/vrms-project/logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- Overlay -->
<div id="overlay"></div>

<!-- Toggle Buttons -->
<button id="hideSidebarBtn">&#8592;</button>
<button id="showSidebarBtn">&#8594;</button>

<!-- Main Content -->
<main id="content">
  <?= isset($content) ? $content : '' ?>
</main>

<script>
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const profileAvatar = document.getElementById("profileAvatar");
  const profileModal = document.getElementById("profileModal");
  const hideBtn = document.getElementById("hideSidebarBtn");
  const showBtn = document.getElementById("showSidebarBtn");
  const toggleArrow = document.getElementById("toggleArrow");
  const content = document.getElementById("content");

  let isMobile = window.innerWidth < 768;

  function openSidebar() {
    sidebar.classList.add("show");
    content.classList.add("shifted");
    hideBtn.style.display = isMobile ? "none" : "block";
    showBtn.style.display = "none";
    if (isMobile) overlay.classList.add("show");
    localStorage.setItem("sidebarOpen", "true");
  }

  function closeSidebar() {
    sidebar.classList.remove("show");
    content.classList.remove("shifted");
    hideBtn.style.display = "none";
    showBtn.style.display = isMobile ? "none" : "block";
    overlay.classList.remove("show");
    localStorage.setItem("sidebarOpen", "false");
  }

  toggleArrow.addEventListener("click", openSidebar);
  hideBtn.addEventListener("click", closeSidebar);
  showBtn.addEventListener("click", openSidebar);
  overlay.addEventListener("click", closeSidebar);

  profileAvatar.addEventListener("click", () => {
    profileModal.classList.toggle("show");
  });

  document.addEventListener("click", (e) => {
    if (!e.target.closest("#profileAvatar") && !e.target.closest("#profileModal")) {
      profileModal.classList.remove("show");
    }
  });

  function handleResize() {
    isMobile = window.innerWidth < 768;
    const savedState = localStorage.getItem("sidebarOpen");

    if (!isMobile) {
      if (savedState === "false") {
        closeSidebar();
      } else {
        openSidebar();
      }
    } else {
      closeSidebar();
      hideBtn.style.display = "none";
      showBtn.style.display = "none";
    }
  }

  window.addEventListener("resize", handleResize);
  window.addEventListener("load", handleResize);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php ob_end_flush(); ?>
</html>
