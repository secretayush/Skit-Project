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
    <div class="row">  
      <!-- job table shows job data registered on get hired platform -->
      <div class="col-md-12 my-2">
        <h1>Table of jobs -</h1>
        <div class="row my-2 border shadow">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead class="bg-success">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name of company</th>
                  <th scope="col">Job Title</th>
                  <th scope="col">Job Description</th>
                  <th scope="col">Openings</th>
                  <th scope="col">Salary</th>
                  <th scope="col">Expires On</th>
                  <th scope="col">Status</th>
                  <th scope="col">Actions</th>
                  <th scope="col">Remarks</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach (array_reverse($data['job_openings']['data']) as $key => $job): ?>
                  <tr>
                    <th scope="row"><?php echo $key+1; ?></th>
                    <td><?php echo $job->name; ?></td>
                    <td><?php echo $job->title; ?></td>
                    <td><?php echo $job->short_desc; ?></td>
                    <td><?php echo $job->no_of_openings; ?></td>
                    <td><?php echo $job->salary; ?></td>
                    <td><?php echo $job->expires_on; ?></td>
                    <td><span class="badge badge-pill badge-<?php echo $job->is_approved == 1 ? 'success' : ($job->is_approved == 2 ? 'danger' : 'warning'); ?>"><?php echo $job->is_approved == 1 ? 'Approved' : ($job->is_approved == 2 ? 'Edits Requested' : ($job->account_status == 3 ? 'Rejected' : 'Pending')); ?></span></td>
                    <td>
                    <?php if (in_array($job->is_approved, [0, 2, 3])) : ?>
                      <a href="/admin/approveJob/<?php echo $job->id; ?>" class="btn btn-success">Activate</a>
                    <?php endif ?>
                    <?php if ($job->is_approved == 0) : ?>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal-<?php echo $job->id; ?>">Reject</button>
                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal-<?php echo $job->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Send message to company admin</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <form method="POST" action="/admin/rejectJob/<?php echo $job->id; ?>">
                                <div class="modal-body">
                                  <textarea id="form7" id="remarks" name="remarks" class="md-textarea form-control" rows="3" placeholder="Message to company admin..."></textarea>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-danger">Reject</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      <?php endif ?>
                    </td>
                    <td><?php echo $job->remarks ? $job->remarks : 'N/A'; ?></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
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
