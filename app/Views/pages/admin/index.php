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
                <h5 class="mb-2 text-dark font-weight-normal">Total Companies</h5>
                <h2 class="mb-4 text-dark font-weight-bold">932.00</h2>
                <div class="dashboard-progress dashboard-progress-1 d-flex align-items-center justify-content-center item-parent"></div>
                <p class="mt-4 mb-0">Approved</p>
                <h3 class="mb-0 font-weight-bold mt-2 text-dark">5443</h3>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card bg-success shadow">
              <div class="card-body text-center">
                <h5 class="mb-2 text-dark font-weight-normal">Total Student</h5>
                <h2 class="mb-4 text-dark font-weight-bold">756,00</h2>
                <div class="dashboard-progress dashboard-progress-2 d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-account-circle icon-md absolute-center text-dark"></i></div>
                <p class="mt-4 mb-0">Daily registration</p>
                <h3 class="mb-0 font-weight-bold mt-2 text-dark">50%</h3>
              </div>
            </div>
          </div>
          <div class="col-xl-3  col-lg-6">
            <div class="card bg-info shadow">
              <div class="card-body text-center">
                <h5 class="mb-2 text-dark font-weight-normal">Total Job posted</h5>
                <h2 class="mb-4 text-dark font-weight-bold">100,38</h2>
                <div class="dashboard-progress dashboard-progress-3 d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-eye icon-md absolute-center text-dark"></i></div>
                <p class="mt-4 mb-0">Daily job post</p>
                <h3 class="mb-0 font-weight-bold mt-2 text-dark">35%</h3>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card bg-danger shadow">
              <div class="card-body text-center">
                <h5 class="mb-2 text-dark font-weight-normal">Add something</h5>
                <h2 class="mb-4 text-dark font-weight-bold">4250k</h2>
                <div class="dashboard-progress dashboard-progress-4 d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-cube icon-md absolute-center text-dark"></i></div>
                <p class="mt-4 mb-0">Decreased since yesterday</p>
                <h3 class="mb-0 font-weight-bold mt-2 text-dark">25%</h3>
              </div>
            </div>
          </div>
        </div>
        <?php if (unserialize($_SESSION['user'])->title == 'admin'): ?>
          <div class="row">
            <!-- job table shows job data registered on get hired platform -->
            <div class="col-md-12 my-2">
              <h1>System Logs -</h1>
              <div class="row my-2 border shadow">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover">
                    <thead class="bg-success">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Description</th>
                        <th scope="col">Timestamp</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($data['logs']['data'] as $key => $log): ?>
                        <tr>
                          <th scope="row"><?php echo $key+1; ?></th>
                          <td><?php echo $log->description; ?></td>
                          <td><?php echo $log->created_at; ?></td>
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
                          <li class="page-item <?php if($data['logs']['page'] <= 1){ echo 'disabled'; } ?>">
                              <a class="page-link"  href="<?php if($data['logs']['page'] <= 1){ echo '#'; } else { echo "/admin/index/".($data['logs']['page'] - 1); } ?>">Prev</a>
                          </li>
                          <li class="page-item <?php if($data['logs']['page'] >= $data['logs']['totalPages']){ echo 'disabled'; } ?>">
                              <a class="page-link"  href="<?php if($data['logs']['page'] >= $data['logs']['totalPages']){ echo '#'; } else { echo "/admin/index/".($data['logs']['page'] + 1); } ?>">Next</a>
                          </li>
                          <li class="page-item"><a class="page-link"  href="/admin/index/<?php echo $data['logs']['totalPages']; ?>">Last</a></li>
                      </ul>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endif ?>
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
