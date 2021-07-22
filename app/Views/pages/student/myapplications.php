<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="input-group rounded col-xl-8 my-3">
        <input id="search" type="search" class="form-control rounded" placeholder="Search Jobs" aria-label="Search"
          aria-describedby="search-addon" />
        <span class="input-group-text border-0" id="search-addon">
          <i class="fa fa-search"></i>
        </span>
      </div>
    </div>
    <div class="col-md-12 my-2">
      <h1>List of Apllied Jobs-</h1>
      <div class="row my-2 border shadow">
        <table class="table table-striped table-bordered table-hover" id="Table">
          <thead class="bg-info text-light  fw-bold">
            <tr>
              <th scope="col">Date Applied</th>
              <th scope="col">Job Title</th>
              <th scope="col">Company Name</th>
              <th scope="col">Status</th>
          </thead>
          <tbody>
            <?php foreach ($data as $jobs) : ?>
            <tr>
              <td><?php echo date("M j, Y h:i a",strtotime($jobs->created_at)); ?></td>
              <td><?php echo $jobs->title; ?></td>
              <td><?php echo $jobs->name; ?></td>
              <td><span class="badge badge-pill alert-<?php echo $jobs->status == 1 ? 'primary' : ($jobs->status == -1 ? 'danger' : ($jobs->status == 2 ? 'success' : 'warning')); ?>"><?php echo $jobs->status == 1 ? 'Accepted' : ($jobs->status == -1 ? 'Rejected' : ($jobs->status == 2 ? 'Shortlisted' : ($jobs->status == 3? 'Mail Received' : 'Applied'))); ?></span>
              </td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
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


