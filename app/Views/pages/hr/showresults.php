<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="input-group rounded col-xl-8 my-3">
        <input id="search" type="search" class="form-control rounded" placeholder="Search Students" aria-label="Search"
          aria-describedby="search-addon" />
        <span class="input-group-text border-0" id="search-addon">
          <i class="fa fa-search"></i>
        </span>
      </div>
    </div>
    <div class="col-md-12 my-2">
      <h1>Result of Student Exams-</h1>
      <div class="row my-2 border shadow">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead class="bg-info text-light  fw-bold">
              <tr>
                <th scope="col">Sl. no.</th>
                <th scope="col">Exam Name</th>
                <th scope="col">Student Name</th>
                <th scope="col">Result</th>
            </thead>
            <tbody>
              <?php foreach ($data['exams'] as $key => $exam): ?>
              <tr>
                <td><?php echo ($key+1) . "."; ?></td>
                <td><?php echo $exam->name; ?></td>
                <td><?php echo $exam->student_name; ?></td>
                <td><?php echo $exam->result; ?> points</td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
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
