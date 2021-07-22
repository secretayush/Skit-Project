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
      <h1>List of Shortlisted Students-</h1>
      <div class="row my-2 border shadow">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead class="bg-info text-light  fw-bold">
              <tr>
                <th scope="col">-</th>
                <th scope="col">Sl. no.</th>
                <th scope="col">Job Title</th>
                <th scope="col">Student Name</th>
                <th scope="col">Student College</th>
                <th scope="col">Resume</th>
            </thead>
            <form method="POST" action="">
            <tbody>
              <?php foreach ($data['shortlisted'] as $key =>$jobs) : ?>
              <tr>
                <td>
                  <input type="checkbox" name="send[]" value="<?php echo $jobs->id.'|'.$jobs->job_id;?>">
                </td>
                <td><?php echo ++$key."."; ?></td>
                <td><?php echo $jobs->title; ?></td>
                <td><?php echo $jobs->name; ?></td>
                <td><?php echo $jobs->college_name; ?></td>
                <td>
                    <a href="<?php echo substr($jobs->resume, strpos($jobs->resume, "/storage")); ?>" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Resume</a>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row justify-content-center" style="width: 100%;">
          <div class="col-md-6 d-flex justify-content-center">
            <nav>
              <ul class="pagination">
                <li class="page-item"><a class="page-link" href="/hr/showshortlisted/1">First</a></li>
                <li class="page-item <?php if ($data['companies']['page'] <= 1) {
                                        echo 'disabled';
                                      } ?>">
                  <a class="page-link" href="<?php if ($data['companies']['page'] <= 1) {
                                                echo '#';
                                              } else {
                                                echo "/hr/showshortlisted/" . ($data['companies']['page'] - 1);
                                              } ?>">Prev</a>
                </li>
                <li class="page-item <?php if ($data['companies']['page'] >= $data['companies']['totalPages']) {
                                        echo 'disabled';
                                      } ?>">
                  <a class="page-link" href="<?php if ($data['companies']['page'] >= $data['companies']['totalPages']) {
                                                echo '#';
                                              } else {
                                                echo "/hr/showshortlisted/" . ($data['companies']['page'] + 1);
                                              } ?>">Next</a>
                </li>
                <li class="page-item"><a class="page-link" href="/hr/showshortlisted/<?php echo $data['companies']['totalPages']; ?>">Last</a></li>
              </ul>
            </nav>
          </div>
        </div>
      <div class="text-center">
        <div class="form-group mb-3">
          <select class="form-control" required name="exam_id">
            <option selected>Choose Exam</option>
            <?php foreach ($data['exams'] as $exams): ?>
              <option value="<?php echo $exams->id; ?>"><?php echo $exams->name; ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <br>
        <div class="input-group">
          <label for="exam_time">Exam Time: </label>
        </div>
        <input size="16" type="text" id="exam_time" name="exam_time" required readonly class="form-control exam_time">
        <br>
        <button class="btn btn-info" type="submit">Send Exam Links</button>
      </form>
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
