<?php
require $_ENV['ROOT'] . '/Views/partials/head.php';
?>
  <section id="hero" class="d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-1 order-lg-1" data-aos="fade-up" data-aos-delay="200">
          <?php if ($data['role'] == 'company') : ?>
            <h1>Register to find the best for your company.</h1>
          <?php endif ?>
          <?php if ($data['role'] == 'student') : ?>
            <h1>Register to find your dream career.</h1>
          <?php endif ?>
          <h2>Welcome to get hired, hiring made easy!</h2>
        </div>
        <div class="col-lg-6 order-2 order-lg-2 hero-img my-3" data-aos="zoom-in" data-aos-delay="200">
        <!-- Signup form -->
          <main class="form-signin shadow bg-light rounded p-5">
            <form data-parsley-validate method="POST" action="/auth/signup/<?php echo $data['role']; ?>" enctype="multipart/form-data" id="signup">
            <div class="text-center">
            <img src="/Images/logo.png" alt="" height="50px" width="200px">
            <h2 class="card-title text-info" style="font-family: 'Berkshire Swash', cursive;">SignUp</h2>
            </div>
              <div class="form-group">
                <label for="name"><b>Name-</b><span class="red"> *</span></label>
                <input type="text" id="name" class="form-control company_details  <?php if ($data['name_error']) : ?> is-invalid <?php endif; ?>" name="name" placeholder="Name..." <?php if(isset($data['name'])): ?> value="<?php echo $data['name']; ?>" <?php endif; ?> required data-parsley-pattern = "^([a-zA-Z]+\s)*[a-zA-Z]+$" data-parsley-trigger="change">
                <i class="fa fa-exclamation-circle name" aria-hidden="true"></i>
                <i class="fa fa-check-circle name" aria-hidden="true"></i>
                <?php if (isset($data['name_error'])) : ?>
                  <div class="invalid-feedback" style="display: block;">
                    <?php echo $data['name_error']; ?>
                  </div>
                <?php endif ?>
              </div>

              <div class="form-group">
                <label for="email"><b>Email-</b><span class="red"> *</span></label>
                <input type="email" id="email" class="form-control company_details <?php if ($data['email_error']) : ?> is-invalid <?php endif; ?>" name="email" placeholder="Email..." <?php if(isset($data['email'])): ?> value="<?php echo $data['email']; ?>" <?php endif; ?> <?php if (isset($data['prefilled'])): ?> disabled <?php endif; ?> required data-parsley-type="email" data-parsley-trigger="change" >
                  <i class="fa fa-exclamation-circle email" aria-hidden="true"></i>
                <i class="fa fa-check-circle email" aria-hidden="true"></i>
                <?php if (isset($data['email_error'])) : ?>
                  <div class="invalid-feedback" style="display: block;">
                    <?php echo $data['email_error']; ?>
                  </div>
                <?php endif ?>
              </div>

              <div class="form-group">
                <label for="contact_number"><b>Contact number-</b><span class="red"> *</span></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">+91</span>
                  </div>
                  <input type="text" id="contact_number" class="form-control company_details <?php if ($data['contact_number_error']) : ?> is-invalid <?php endif; ?>" name="contact_number" maxlength="10" placeholder="Contact number..." <?php if(isset($data['contact_number'])): ?> value="<?php echo $data['contact_number']; ?>" <?php endif; ?> required data-parsley-pattern="^[0-9]{10}$"  data-parsley-trigger="change" >
                  <?php if (isset($data['contact_number_error'])) : ?>
                    <div class="invalid-feedback" style="display: block;">
                      <?php echo $data['contact_number_error']; ?>
                    </div>
                  <?php endif ?>
                </div>
                <i class="fa fa-exclamation-circle contact_number" aria-hidden="true"></i>
                <i class="fa fa-check-circle contact_number" aria-hidden="true"></i>
              </div>

              <?php if ($data['role'] == 'company') : ?>
                <div class="form-group">
                  <label for="company_identifier"><b>Company Identifier-</b><span class="red"> *</span></label>
                  <input type="text" id="company_identifier" class="form-control company_details <?php if ($data['company_identifier_error']) : ?> is-invalid <?php endif; ?>" name="company_identifier" placeholder="Trade licence/Company PAN..." <?php if(isset($data['company_identifier'])): ?> value="<?php echo $data['company_identifier']; ?>" <?php endif; ?> required data-parsley-pattern="^[a-zA-Z0-9-]+$" data-parsley-trigger="change">
                  <i class="fa fa-exclamation-circle company_identifier" aria-hidden="true"></i>
                  <i class="fa fa-check-circle company_identifier" aria-hidden="true"></i>
                  <?php if (isset($data['company_identifier_error'])) : ?>
                    <div class="invalid-feedback" style="display: block;">
                      <?php echo $data['company_identifier_error']; ?>
                    </div>
                  <?php endif ?>
                </div>
              <?php endif ?>

              <?php if ($data['role'] == 'student') : ?>
                <div class="form-group">
                  <label for="college_name"><b>College Name-</b><span class="red"> *</span></label>
                  <input type="text" id="college_name" class="form-control company_details <?php if ($data['college_name_error']) : ?> is-invalid <?php endif; ?>" name="college_name" placeholder="College Name" <?php if(isset($data['college_name'])): ?> value="<?php echo $data['college_name']; ?>" <?php endif; ?> required data-parsley-pattern="^[a-zA-Z ]+$" data-parsley-trigger="change">
                  <i class="fa fa-exclamation-circle college_name" aria-hidden="true"></i>
                  <i class="fa fa-check-circle college_name" aria-hidden="true"></i>
                  <?php if (isset($data['college_name_error'])) : ?>
                    <div class="invalid-feedback" style="display: block;">
                      <?php echo $data['college_name_error']; ?>
                    </div>
                  <?php endif ?>
                </div>

                <div class="form-group">
                  <label for="college_id"><b>College ID-</b><span class="red"> *</span></label>

                  <input type="text" id="college_id" class="form-control company_details <?php if ($data['college_id_error']) : ?> is-invalid <?php endif; ?>" name="college_id" placeholder="College ID/University Roll Number" <?php if(isset($data['college_id'])): ?> value="<?php echo $data['college_id']; ?>" <?php endif; ?> required data-parsley-pattern="^[A-Z0-9]+$" data-parsley-trigger="change">
                  <i class="fa fa-exclamation-circle college_id" aria-hidden="true"></i>
                  <i class="fa fa-check-circle college_id" aria-hidden="true"></i>
                  <?php if (isset($data['college_id_error'])) : ?>
                    <div class="invalid-feedback" style="display: block;">
                      <?php echo $data['college_id_error']; ?>
                    </div>
                  <?php endif ?>
                </div>

                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="current_cgpa"><b>College CGPA-</b><span class="red"> *</span></label>
                      <input type="text" id="current_cgpa" class="form-control company_details <?php if ($data['current_cgpa_error']) : ?> is-invalid <?php endif; ?>" name="current_cgpa" placeholder="Current CGPA" <?php if(isset($data['current_cgpa'])): ?> value="<?php echo $data['current_cgpa']; ?>" <?php endif; ?> required data-parsley-pattern="^[0-9.]+$" data-parsley-trigger="change">
                      <i class="fa fa-exclamation-circle current_cgpa" aria-hidden="true"></i>
                      <i class="fa fa-check-circle current_cgpa" aria-hidden="true"></i>
                      <?php if (isset($data['current_cgpa_error'])) : ?>
                        <div class="invalid-feedback" style="display: block;">
                          <?php echo $data['current_cgpa_error']; ?>
                        </div>
                      <?php endif ?>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="active_backlogs"><b>Active backlogs-</b><span class="red"> *</span></label>
                      <input type="text" id="active_backlogs" class="form-control company_details <?php if ($data['active_backlogs_error']) : ?> is-invalid <?php endif; ?>" name="active_backlogs" placeholder="Active Backlogs" <?php if(isset($data['active_backlogs'])): ?> value="<?php echo $data['active_backlogs']; ?>" <?php endif; ?> required data-parsley-pattern="^[0-9]+$" data-parsley-trigger="change">
                      <i class="fa fa-exclamation-circle active_backlogs" aria-hidden="true"></i>
                      <i class="fa fa-check-circle active_backlogs" aria-hidden="true"></i>
                      <?php if (isset($data['active_backlogs_error'])) : ?>
                        <div class="invalid-feedback" style="display: block;">
                          <?php echo $data['active_backlogs_error']; ?>
                        </div>
                      <?php endif ?>
                    </div>
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="tenth_marks"><b>10th Percentage-</b><span class="red"> *</span></label>
                    <div class="input-group">
                      <input type="text" id="tenth_marks" class="form-control company_details <?php if ($data['tenth_marks_error']) : ?> is-invalid <?php endif; ?>" name="tenth_marks" placeholder="10th Percentile" <?php if(isset($data['tenth_marks'])): ?> value="<?php echo $data['tenth_marks']; ?>" <?php endif; ?> required data-parsley-pattern="^[0-9.]+$" data-parsley-trigger="change">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">%</span>
                      </div>
                    </div>
                      <i class="fa fa-exclamation-circle tenth_marks" id= "two" aria-hidden="true"></i>
                      <i class="fa fa-check-circle tenth_marks" id= "two" aria-hidden="true"></i>
                      <?php if (isset($data['tenth_marks_error'])) : ?>
                        <div class="invalid-feedback" style="display: block;">
                          <?php echo $data['tenth_marks_error']; ?>
                        </div>
                      <?php endif ?>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="twelveth_marks"><b>12th Percentage-</b><span class="red"> *</span></label>
                    <div class="input-group">
                      <input type="text" id="twelveth_marks" class="form-control company_details <?php if ($data['twelveth_marks_error']) : ?> is-invalid <?php endif; ?>" name="twelveth_marks" placeholder="12th Percentile" <?php if(isset($data['twelveth_marks'])): ?> value="<?php echo $data['twelveth_marks']; ?>" <?php endif; ?> required data-parsley-pattern="^[0-9.]+$" data-parsley-trigger="change">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">%</span>
                      </div>
                    </div>
                      <i class="fa fa-exclamation-circle twelveth_marks" id= "two" aria-hidden="true"></i>
                      <i class="fa fa-check-circle twelveth_marks" id= "two" aria-hidden="true"></i>
                      <?php if (isset($data['twelveth_marks_error'])) : ?>
                        <div class="invalid-feedback" style="display: block;">
                          <?php echo $data['twelveth_marks_error']; ?>
                        </div>
                      <?php endif ?>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="resume"><b>Resume (Only PDF)</b><span class="red"> *</span></label>
                  <div class="button-wrapper">
                    <input type="file" accept="application/pdf" id="upload" class="form-control upload-box company_details <?php if ($data['resume_error']) : ?> is-invalid <?php endif; ?>" name="resume" placeholder="Resume">
                    <button class="btn btn-info" type="button" id="custom-button" style="overflow:hidden"><small>Choose a file</small></button>
                  </div>
                  <?php if (isset($data['resume_error'])) : ?>
                    <div class="invalid-feedback" style="display: block;">
                      <?php echo $data['resume_error']; ?>
                    </div>
                  <?php endif ?>
                </div>
              <?php endif ?>

            <div class="form-group">
              <label for="password"><b>Password-</b><span class="red"> *</span></label>
              <input type="<?php echo $data['prefilled'] ? 'hidden' : 'password' ?>" id="password" class="form-control company_details <?php if ($data['password_error']) : ?> is-invalid <?php endif; ?>" name="password" placeholder="Password..." <?php if($data['prefilled']): ?> value="<?php echo $data['email']; ?>" <?php endif; ?> required data-parsley-pattern="^.{6,}$" data-parsley-trigger="change"> 
              <?php if (isset($data['password_error'])) : ?>
                <div class="invalid-feedback" style="display: block;">
                  <?php echo $data['password_error']; ?>
                </div>
              <?php endif ?>
              <i class="fa fa-exclamation-circle password" aria-hidden="true"></i>
              <i class="fa fa-check-circle password" aria-hidden="true"></i>
            </div>

            <div class="form-group">
              <label for="confirm_password"><b>Confirm Password-</b><span class="red"> *</span></label>
              <input type=<?php echo $data['prefilled'] ? 'hidden' : 'password' ?> id="confirm_password" class="form-control company_details <?php if ($data['confirm_password_error']) : ?> is-invalid <?php endif; ?>" name="confirm_password" placeholder="Confirm password..." <?php if($data['prefilled']): ?> value="<?php echo $data['email']; ?>" <?php endif; ?> required data-parsley-equalto="#password" data-parsley-trigger="change">
              <i class="fa fa-exclamation-circle confirm_password" aria-hidden="true"></i>
              <i class="fa fa-check-circle confirm_password" aria-hidden="true"></i>
              <?php if (isset($data['confirm_password_error'])) : ?>
                <div class="invalid-feedback" style="display: block;">
                  <?php echo $data['confirm_password_error']; ?>
                </div>
              <?php endif ?>

            </div>
            
            <div class="d-flex flex-row align-items-center justify-content-between">
              <a href="/auth/login"><b>Login</b></a>
              <button class="btn btn-signup text-white" type="submit" name="signup_company">Create Account</button>
            </div>
            <div class="d-flex flex-row align-items-center justify-content-between my-3">
              <a class="btn btn-block btn-social btn-google text-white mx-1" href="<?php echo $data['google_url']; ?>" style="margin-top: .5rem;">
                <span class="fa fa-google"></span>
                Sign up With Google
              </a>
              <!-- <a class="btn btn-block btn-social btn-linkedin text-white mx-1" href="<?php echo $data['linkedin_url']; ?>">
                <span class="fa fa-linkedin"></span>
                Sign up With LinkedIn
              </a> -->
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
<?php
require $_ENV['ROOT'] . '/Views/partials/footer.php';
?>
