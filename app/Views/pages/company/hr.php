<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-xl-5">
        <div class="card shadow bg-light rounded" id="company_card">
          <h2 class="text-center" style="font-family: 'Kalam', cursive">HR Details</h2>
          <div class="card-body py-md-4">
            <form data-parsley-validate method="POST" action="/company/hr" id="hrforms">
              <div class="form-group">
                <label for="name"><b>Name-</b><span class="red"> *</span></label>
                <input type="text" class="form-control" name="name" id="name" required data-parsley-pattern = "^([a-zA-Z]+\s)*[a-zA-Z]+$" data-parsley-trigger="change" placeholder="Name...">
                <i class="fa fa-exclamation-circle name" aria-hidden="true"></i>
                <i class="fa fa-check-circle name" aria-hidden="true"></i>
              </div>

              <div class="form-group">
                <label for="employee_id"><b>Employee ID-</b><span class="red"> *</span></label>
                <input type="employee_id" class="form-control" id="employee_id" name="employee_id" required data-parsley-pattern="^[a-zA-Z0-9-]+$" data-parsley-trigger="change" placeholder="Employee ID...">
                <i class="fa fa-exclamation-circle employee_id" aria-hidden="true"></i>
                <i class="fa fa-check-circle employee_id" aria-hidden="true"></i>
              </div>

              <div class="form-group">
                <label for="email"><b>Email-</b><span class="red"> *</span></label>
                <input type="email" class="form-control" id="email" name="email" required data-parsley-type="email" data-parsley-trigger="change" placeholder="Email...">
                <i class="fa fa-exclamation-circle email" aria-hidden="true"></i>
                <i class="fa fa-check-circle email" aria-hidden="true"></i>
              </div>

              <div class="form-group">
                <label for="contact_number"><b>Contact number-</b><span class="red"> *</span></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">+91</span>
                  </div>
                  <input type="text" class="form-control" id="contact_number" name="contact_number" maxlength="10" required data-parsley-pattern="^[0-9]{10}$"  data-parsley-trigger="change" placeholder="Contact number...">
                </div>
                <i class="fa fa-exclamation-circle contact_number" aria-hidden="true"></i>
                <i class="fa fa-check-circle contact_number" aria-hidden="true"></i>
              </div>

              <div class="d-flex flex-row align-items-center justify-content-center">
                <button class="btn btn-primary" type="submit" name="signup_company">Create HR Account</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
require_once $_ENV['ROOT'] . '/Views/partials/dashboard/footer.php';
?>
<?php
require_once $_ENV['ROOT'] . '/Views/partials/dashboard/javascript.php';
?>
