<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
  <div class="container">
    <div class="row">
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
      <!-- list of all exams -->
      <div class="col-md-12 my-2 company_content">
        <h1 class="my-3 text-center">Exams Data</h1>
        <h1>List of exams -</h1>
        <div class="row my-2 border shadow">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead class="bg-success">
                <tr>
                  <th scope="col">Sl. No.</th>
                  <th scope="col">Description</th>
                  <th scope="col">Duration</th>
                  <th scope="col">Buffer Time</th>
                  <th scope="col">Has Negative Marking</th>
                  <th scope="col">Created By</th>
                  <th scope="col">Approval Status</th>
                  <th scope="col">Actions</th>
              </thead>
              <tbody>
                <?php foreach ($data['exams']['data'] as $x => $exam) : ?>
                  <tr>
                    <td><?php echo $x+1; ?></td>
                    <td><?php echo $exam->description; ?></td>
                    <td><?php echo $exam->duration; ?> minutes</td>
                    <td><?php echo $exam->buffer_time; ?> minutes</td>
                    <td><span class="badge badge-<?php echo $exam->has_negative_marking == 1 ? 'success' : 'warning'?>"><?php echo $exam->has_negative_marking == 1 ? 'Yes' : 'No'?></span></td>
                    <td><?php echo $exam->employee_id; ?></td>
                    <td><span class="badge badge-<?php echo $exam->is_approved == 1 ? 'success' : ($exam->is_approved == 2 ? 'danger' : 'warning'); ?>"><?php echo $exam->is_approved == 1 ? 'Approved' : ($exam->is_approved == 2 ? 'Rejected' : 'Pending'); ?></span></td>
                    <td><a href="/exam/browse/<?php echo $exam->id; ?>" class="btn btn-info">View</a></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
          <div class="row justify-content-center" style="width: 100%;">
            <div class="col-md-6 d-flex justify-content-center">
              <nav>
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="/hr/index/1">First</a></li>
                  <li class="page-item <?php if ($data['companies']['page'] <= 1) {
                                          echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($data['companies']['page'] <= 1) {
                                                  echo '#';
                                                } else {
                                                  echo "/hr/index/" . ($data['companies']['page'] - 1);
                                                } ?>">Prev</a>
                  </li>
                  <li class="page-item <?php if ($data['companies']['page'] >= $data['companies']['totalPages']) {
                                          echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($data['companies']['page'] >= $data['companies']['totalPages']) {
                                                  echo '#';
                                                } else {
                                                  echo "/hr/index/" . ($data['companies']['page'] + 1);
                                                } ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="/hr/index/<?php echo $data['companies']['totalPages']; ?>">Last</a></li>
                </ul>
              </nav>
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
