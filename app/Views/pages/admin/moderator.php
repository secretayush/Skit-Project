<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-xl-5">
        <div class="card shadow bg-light rounded" id="company_card">
          <h2 class="text-center">Moderator Details</h2>
          <div class="card-body py-md-4">
            <form data-parsley-validate method="POST" action="/admin/createModerator" id="moderator">
              <div class="form-group">
                <label for="name"><b>Name-</b><span class="red"> *</span></label>
                <input type="text" id="name" class="form-control company_details <?php if (isset($data['name_error'])) : ?> is-invalid <?php endif; ?>" name="name" required data-parsley-pattern = "^([a-zA-Z]+\s)*[a-zA-Z]+$" data-parsley-trigger="change" placeholder="Name...">
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
                <input type="email" id="email" class="form-control company_details <?php if (isset($data['email_error'])) : ?> is-invalid <?php endif; ?>" name="email" required data-parsley-type="email" data-parsley-trigger="change" placeholder="Email...">
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

              <div class="d-flex flex-row align-items-center justify-content-center">
                <button class="btn btn-primary mt-3" type="submit" name="signup_company">Create Moderator Account</button>
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
