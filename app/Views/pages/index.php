<?php
require $_ENV['ROOT'] . '/Views/partials/head.php';
?>
  <section id="hero" class="d-flex align-items-center" style="height: 100vh">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
          <h1>Hiring solution for corporates</h1>
          <h1>Job opportunities for students</h1>
          <h2>Welcome to get hired, hiring made easy!</h2>
          <?php if (!isset($_SESSION['user'])): ?>
            <div class="d-lg-flex flex-column col-lg-7 text-center">
              <a href="/auth/signup/company" class="btn-get-started">Continue as Company</a>
              <a href="/auth/signup/student" class="btn-get-started">Continue as Student</a>
            </div>
          <?php else: ?>
            <div class="d-lg-flex">
              <a href="/<?php echo unserialize($_SESSION['user'])->title; ?>" class="btn-get-started scrollto">Dashboard</a>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
          <img src="/Images/home_hero.png" class="img-fluid animated" alt="">
        </div>
      </div>
    </div>
  </section>
<?php
require $_ENV['ROOT'] . '/Views/partials/footer.php';
?>
