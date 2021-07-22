<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="input-group rounded col-xl-8 my-3">
        <input id="search" type="search" class="form-control rounded" placeholder="Search Companies" aria-label="Search"
          aria-describedby="search-addon" />
        <span class="input-group-text border-0" id="search-addon">
          <i class="fa fa-search"></i>
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 my-2">
        <h1>Table of companies -</h1>
        <div class="row my-2 border shadow">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead class="bg-success">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Company Name</th>
                  <th scope="col">Company Identifier</th>
                  <th scope="col">Company Contact</th>
                  <th scope="col">Account Status</th>
                  <th scope="col">Remarks</th>
                  <th scope="col">Actions</th>
              </thead>
              <tbody>
                <?php foreach ($data['companies']['data'] as $key => $company) : ?>
                  <tr>
                    <td><?php echo $key+1; ?>
                    <td><?php echo $company->name; ?></td>
                    <td><?php echo $company->company_identifier; ?></td>
                    <td><?php echo $company->email; ?></td>
                    <td><span class="badge badge-<?php echo $company->account_status == 1 ? 'success' : ($company->account_status == 2 ? 'danger' : 'warning'); ?>"><?php echo $company->account_status == 1 ? 'Active' : ($company->account_status == 2 ? 'Banned' : ($company->account_status == 3 ? 'Rejected' : 'Inactive')); ?></span></td>
                    <td><?php echo $company->remarks ? $company->remarks : 'N/A'; ?></td>
                    <td>
                      <!-- Button trigger modal -->
                    <?php if (in_array($company->account_status, [0, 2, 3])) : ?>
                      <a href="/admin/activate/<?php echo $company->id; ?>" class="btn btn-success">Activate</a>
                    <?php endif ?>
                    <?php if ($company->account_status == 0) : ?>
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal-<?php echo $company->id; ?>">Reject</button>
                      <!-- Reject Modal -->
                      <div class="modal fade" id="rejectModal-<?php echo $company->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Send message to company admin</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="POST" action="/admin/reject/<?php echo $company->id; ?>">
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
                    <?php if ($company->account_status == 1) : ?>
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#banModal-<?php echo $company->id; ?>">Ban</button>
                      <!-- Ban Modal -->
                      <div class="modal fade" id="banModal-<?php echo $company->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Ban <?php echo $company->name ?></h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="POST" action="/admin/ban/<?php echo $company->id; ?>">
                              <div class="modal-body">
                                <label for="remarks">Remarks:</label>
                                <textarea id="remarks" class="md-textarea form-control" name="remarks" rows="3" placeholder="Message to company admin..."></textarea>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Submit</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    <?php endif ?>
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
                    <li class="page-item"><a class="page-link"  href="/admin/index/1">First</a></li>
                    <li class="page-item <?php if($data['companies']['page'] <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link"  href="<?php if($data['companies']['page'] <= 1){ echo '#'; } else { echo "/admin/index/".($data['companies']['page'] - 1); } ?>">Prev</a>
                    </li>
                    <li class="page-item <?php if($data['companies']['page'] >= $data['companies']['totalPages']){ echo 'disabled'; } ?>">
                        <a class="page-link"  href="<?php if($data['companies']['page'] >= $data['companies']['totalPages']){ echo '#'; } else { echo "/admin/index/".($data['companies']['page'] + 1); } ?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link"  href="/admin/index/<?php echo $data['companies']['totalPages']; ?>">Last</a></li>
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
