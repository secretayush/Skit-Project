<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
<div class="container">
  <div class="row justify-content-center">
    <div class="input-group rounded col-xl-8 my-3">
      <input id="search" type="search" class="form-control rounded" placeholder="Search Exams" aria-label="Search"
        aria-describedby="search-addon" />
      <span class="input-group-text border-0" id="search-addon">
        <i class="fa fa-search"></i>
      </span>
    </div>
  </div>
    <div class="col-md-12 my-2">
      <h1>My Exams-</h1>
      <div class="row my-2 border shadow">
        <table class="table table-striped table-bordered table-hover" id="Table">
          <thead class="bg-info text-light  fw-bold">
            <tr>
              <th scope="col">Exam name</th>
              <th scope="col">Company</th>
              <th scope="col">Date of exam</th>
              <th scope="col">Status</th>
          </thead>
          <tbody>
            <?php foreach ($data as $exams) : ?>
            <tr>
              <td><?php echo $exams->exam_name; ?></td>
              <td><?php echo $exams->company_name; ?></td>
              <td><?php echo date("M j, Y h:i a",strtotime($exams->exam_time)); ?></td>
              <td>
                <?php if($exams->status == 0): ?>
                  <a href="/student/start/<?php echo $exams->exam_token?>" class="btn btn-warning" role="button" aria-pressed="true">Start Exam</a>
                <?php endif ?>
                <span class="badge badge-pill alert-<?php if($exams->status == 1) echo 'warning';
              else if ($exams->status == 2) echo 'success'?>"> <?php if($exams->status == 1) echo 'Started';
              else if ($exams->status == 2) echo 'Completed'?> </span>
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


