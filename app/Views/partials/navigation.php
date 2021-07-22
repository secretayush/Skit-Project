<?php

use App\Helpers\Session;

if (Session::isset('user'))
  $user = Session::user();
?>
<!-- ======= Header ======= -->
<header id="header" class="fixed-top header-scrolled">
  <div class="container d-flex align-items-center">

     <a href="/" class="logo mr-auto"><img src="/Images/logo.png" alt="" class="img-fluid"></a>

    <nav class="nav-menu d-none d-lg-block">
      <!-- <ul>
        <li><a href="/">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#services">Services</a></li>
      </ul> -->
    </nav><!-- .nav-menu -->
    <?php if (!isset($user)) : ?>
      <a href="/auth/login" class="get-started-btn scrollto">Login</a>
    <?php else: ?>
      <a href="/<?php echo $user->title == 'moderator' ? 'admin' : $user->title; ?>" class="get-started-btn">Dashboard</a>
      <a href="/auth/logout" class="get-started-btn">Logout</a>
    <?php endif; ?>
  </div>
</header><!-- End Header -->
