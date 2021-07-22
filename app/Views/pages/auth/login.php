<?php
require $_ENV['ROOT'] . '/Views/partials/head.php';
?>
  <section id="hero" class="d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-1 order-lg-1" data-aos="fade-up" data-aos-delay="200">
          <h1>Sign in to a tailored dashboard.</h1>
          <h2>Welcome to get hired, hiring made easy!</h2>
        </div>
        <div class="col-lg-6 order-2 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
          <div class="container" id="login">
            <main class="form-signin shadow bg-light rounded p-5 text-center">
              <form data-parsley-validate method="POST" action="/auth/login<?php echo $data['continue'] ? '?continue='.$data['continue'] : '' ?>">

                <img src="/Images/logo.png" alt="" height="50px" width="200px">
                <h2 class="h3 my-4 fw-normal text-info" style="font-family: 'Berkshire Swash', cursive;">Log in</h2>
                <div class="form-floating">
                  <input type="email" class="form-control <?php if ($data['email_error']) : ?> is-invalid <?php endif; ?>" name="email" id="floatingInput" placeholder="Email- name@example.com" required data-parsley-type="email" data-parsley-trigger="change">
                  <?php if (isset($data['email_error'])) : ?>
                    <div class="invalid-feedback" style="display: block;">
                      <?php echo $data['email_error']; ?>
                    </div>
                  <?php endif ?>
                </div>
                <div class="form-floating">
                  <input type="password" class="form-control <?php if ($data['password_error']) : ?> is-invalid <?php endif; ?>" name="password" id="floatingPassword" placeholder="Password" required data-parsley-trigger="change" >
                  <?php if (isset($data['password_error'])) : ?>
                    <div class="invalid-feedback" style="display: block;">
                      <?php echo $data['password_error']; ?>
                    </div>
                  <?php endif ?>
                </div>
                <div class="text-muted my-3">
                  <a href="/password/forgot">Forgot password?</a>
                </div>
                <button class="w-100 btn btn-primary my-2" type="submit">Sign in</button>
                <a class="btn btn-block btn-social btn-google text-white" href="<?php echo $data['google_url']; ?>">
                  <span class="fa fa-google"></span>
                  Sign in With Google
                </a>
                <!-- <a class="btn btn-block btn-social btn-linkedin text-white" href="<?php echo $data['linkedin_url']; ?>">
                  <span class="fa fa-linkedin"></span>
                  Sign in With LinkedIn
                </a>
                <div class="text-muted my-3"> -->
                  Don't have an account?
                </div>
                <div class="my-2">
                  <a href="/auth/signup/student" class="mt-5 mb-3 text-primary">Signup As a student</a>
                </div>
                <div class="my-2">
                  <a href="/auth/signup/company" class="mt-5 mb-3 text-primary">Signup As a company</a>
                </div>
              </form>
            </main>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php
require $_ENV['ROOT'] . '/Views/partials/footer.php';
?>
