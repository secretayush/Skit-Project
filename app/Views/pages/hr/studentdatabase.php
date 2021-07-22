<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper" style="height:inherit">
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
      <h1>List of Apllied Students-</h1>
      <div class="row my-2 border shadow">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead class="bg-info text-light  fw-bold">
              <tr>
                <th scope="col">Sl. no.</th>
                <th scope="col">Job Title</th>
                <th scope="col">Student Name</th>
                <th scope="col">Student College</th>
                <th scope="col">Date Applied</th>
                <th scope="col">Status</th>
                <th scope="col">Remarks</th>
                <th scope="col">Resume</th>
                <th scope="col">Change Status</th>
            </thead>
            <tbody>
              <?php foreach ($data as $key=>$jobs) : ?>
              <tr>
                <td><?php echo ++$key."."; ?></td>
                <td><?php echo $jobs->title; ?></td>
                <td><?php echo $jobs->name; ?></td>
                <td><?php echo $jobs->college_name; ?></td>
                <td><?php echo date("M j, Y h:i a",strtotime($jobs->created_at)); ?></td>
                 <td><span class="badge badge-pill alert-<?php echo $jobs->status == 1 ? 'primary' : ($jobs->status == -1 ? 'danger' : ($jobs->status == 2 ? 'success' : 'warning')); ?>"><?php echo $jobs->status == 1 ? 'Accepted' : ($jobs->status == -1 ? 'Rejected' : ($jobs->status == 2 ? 'Shortlisted' : ($jobs->status == 3? 'Mail Sent' : 'Applied'))); ?></span>
                </td>
                <td>
                  <?php if ($jobs->active_backlogs != 0) : ?>
                    <p><?php echo "Student has " . $jobs->active_backlogs . " active backlogs."; ?></p>
                  <?php endif ?>
                  <?php if ($jobs->tenth_marks < 60) : ?>
                    <p><?php echo "Student has less than 60% in 10th."; ?></p>
                  <?php endif ?>
                  <?php if ($jobs->twelveth_marks < 60) : ?>
                    <p><?php echo "Student has less than 60% in 12th."; ?></p>
                  <?php endif ?>
                </td>
                <td>
                  <a href="<?php echo substr($jobs->resume, strpos($jobs->resume, "/storage")); ?>" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>Resume</a>
                </td>
                <td>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#statusModal-<?php echo $jobs->uid?>">Change status</button>
                  <div class="modal fade" id="statusModal-<?php echo $jobs->uid?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Send message to student</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <textarea id="form7" class="md-textarea form-control" rows="3" placeholder="Message to student..."></textarea>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <?php if ($jobs->status == -1) : ?>
                            <a href="/hr/accept/<?php echo $jobs->user_id."/".$jobs->job_id; ?>" class="btn btn-success">Accept</a>
                          <?php endif ?>
                          <?php if ($jobs->status == 0) : ?>
                            <a href="/hr/accept/<?php echo $jobs->user_id."/".$jobs->job_id; ?>" class="btn btn-success">Accept</a>
                            <a href="/hr/reject/<?php echo $jobs->user_id."/".$jobs->job_id; ?>" class="btn btn-danger">Reject</a>
                          <?php endif ?>
                          <?php if ($jobs->status == 1) : ?>
                            <a href="/hr/shortlist/<?php echo $jobs->user_id."/".$jobs->job_id; ?>" class="btn btn-success">Shortlist</a>
                            <a href="/hr/reject/<?php echo $jobs->user_id."/".$jobs->job_id; ?>" class="btn btn-danger">Reject</a>
                          <?php endif ?>
                           <?php if ($jobs->status == 2) : ?>
                            <a href="/hr/reject/<?php echo $jobs->user_id."/".$jobs->job_id; ?>" class="btn btn-success">reject</a>
                          <?php endif ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
        <div class="row justify-content-center" style="width: 100%;">
            <div class="col-md-6 d-flex justify-content-center">
              <nav>
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="/hr/studentdatabase/1">First</a></li>
                  <li class="page-item <?php if ($data['companies']['page'] <= 1) {
                                          echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($data['companies']['page'] <= 1) {
                                                  echo '#';
                                                } else {
                                                  echo "/hr/studentdatabase/" . ($data['companies']['page'] - 1);
                                                } ?>">Prev</a>
                  </li>
                  <li class="page-item <?php if ($data['companies']['page'] >= $data['companies']['totalPages']) {
                                          echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($data['companies']['page'] >= $data['companies']['totalPages']) {
                                                  echo '#';
                                                } else {
                                                  echo "/hr/studentdatabase/" . ($data['companies']['page'] + 1);
                                                } ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="/hr/studentdatabase/<?php echo $data['companies']['totalPages']; ?>">Last</a></li>
                </ul>
              </nav>
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
