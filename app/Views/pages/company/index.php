<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
  <div class="container">
    <div class="row justify-content-center my-3">
      <!-- dashboard panel -->
      <div class="col-md-12 my-5">
        <h1>Dashboard -</h1>
        <div class="row d-flex my-2">
          <div class="col-xl-3 col-lg-6">
            <div class="card bg-warning">
              <div class="card-body text-center shadow">
                <h5 class="mb-2 text-dark font-weight-normal">Total Students applied</h5>
                <h2 class="mb-4 text-dark font-weight-bold">932</h2>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card bg-success shadow">
              <div class="card-body text-center shadow">
                <h5 class="mb-2 text-dark font-weight-normal">Approved</h5>
                <h2 class="mb-4 text-dark font-weight-bold">544</h2>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card bg-info shadow">
              <div class="card-body text-center">
                <h5 class="mb-2 text-dark font-weight-normal">Total Job posted</h5>
                <h2 class="mb-4 text-dark font-weight-bold">103</h2>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card bg-danger shadow">
              <div class="card-body text-center">
                <h5 class="mb-2 text-dark font-weight-normal">Total Job Openings</h5>
                <h2 class="mb-4 text-dark font-weight-bold">42</h2>
              </div>
            </div>
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
