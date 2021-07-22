<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="/" class="nav-link">Home</a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" href="/auth/logout" role="button"><i
          class="fas fa-sign-out-alt"></i> Logout</a>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="index.html" class="brand-link border-bottom-0">
    <img src="/Images/logo.png" alt="gethired" class="brand-image px-5 mx-0"
         style="opacity: .8">
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="/Images/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo unserialize($_SESSION['user'])->name; ?></a>
       <small class="text-white"><?php echo unserialize($_SESSION['user'])->display_name; ?></small>
      </div>
    </div>

    <?php
      $roleTitle = unserialize($_SESSION['user'])->title == 'moderator' ? 'admin' : unserialize($_SESSION['user'])->title;
      require $_ENV['ROOT'] . '/Views/pages/' . $roleTitle .'/partials/sidebar.php';
    ?>
  </div>
  <!-- /.sidebar -->
</aside>
