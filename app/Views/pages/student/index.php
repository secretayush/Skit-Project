<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="input-group rounded col-xl-8 my-3">
        <input id="search_jobs" type="search" class="form-control rounded" placeholder="Search Jobs" onkeyup="myFunction()" aria-label="Search"
          aria-describedby="search-addon" />
        <span class="input-group-text border-0" id="search-addon">
          <i class="fa fa-search"></i>
        </span>
      </div>
    </div>
    <div class="row pt-5 m-auto justify-content-center" id="myItems">
      <?php foreach ($data as $key => $jobs) : ?>
      <div class="col-md-6 col-lg-4 pb-3 job" id="job<?php echo $key+1; ?>">
        <div class="card card-custom bg-light shadow text-center">
          <div class="card-body py-md-5">
            <div class="heading d-flex flex-column my-1">
              <a href="#companywebsite"><img src="https://d2fltix0v2e0sb.cloudfront.net/dev-rainbow.png" width="50" height="50"></a>
              <h3 class="card-header text-info my-2"><?php echo $jobs->title; ?></h3>
              <h6 class="card-subtitle mb-2 text-muted"><?php echo $jobs->name; ?></h6>
            </div>
            <p class="card-text"><?php echo $jobs->short_desc; ?></p>
            <p class="card-text"><small><i class="fas fa-rupee-sign"></i> Salary: <?php echo $jobs->salary; ?></small></p>
            <p class="card-text"><small><i class="far fa-calendar-alt"></i> Apply before: <?php echo date("M j, Y",strtotime($jobs->expires_on)); ?></small></p>
            <?php if (!in_array(unserialize($_SESSION['user'])->id, $jobs->applied)): ?>
              <a href="/student/apply/<?php echo $jobs->id; ?>" class="card-link">Apply Now</a>
            <?php else: ?>
              <a href="/student/apply/<?php echo $jobs->id; ?>" class="card-link text-danger">Already Applied</a>
            <?php endif ?>
            <a href="#companywebsite" class="card-link">See Job</a>
          </div>
        </div>
      </div>
      <?php endforeach ?>
    </div>
  </div>
</div>
<?php
require_once $_ENV['ROOT'] . '/Views/partials/dashboard/footer.php';
?>
<?php
require_once $_ENV['ROOT'] . '/Views/partials/dashboard/javascript.php';
?>
