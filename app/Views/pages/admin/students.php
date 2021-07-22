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
    <div class="row">
      <!-- student table shows student data registered on get hired platform -->
      <div class="col-md-12 my-2">
        <h1>Table of Students -</h1>
        <div class="row my-2 border shadow">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead class="bg-success">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Student Name</th>
                  <th scope="col">Student College</th>
                  <th scope="col">Student Contact</th>
                  <th scope="col">Student ID</th>
              </thead>
              <tbody>
                <?php foreach ($data['students']['data'] as $key => $student) : ?>
                  <tr>
                    <td><?php echo $key+1; ?></td>
                    <td><?php echo $student->name; ?></td>
                    <td><?php echo $student->college_name; ?></td>
                    <td><?php echo $student->email; ?></td>
                    <td><?php echo $student->college_id; ?></td>
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
                    <li class="page-item <?php if($data['students']['page'] <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link"  href="<?php if($data['students']['page'] <= 1){ echo '#'; } else { echo "/admin/index/".($data['students']['page'] - 1); } ?>">Prev</a>
                    </li>
                    <li class="page-item <?php if($data['students']['page'] >= $data['students']['totalPages']){ echo 'disabled'; } ?>">
                        <a class="page-link"  href="<?php if($data['students']['page'] >= $data['students']['totalPages']){ echo '#'; } else { echo "/admin/index/".($data['students']['page'] + 1); } ?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link"  href="/admin/index/<?php echo $data['students']['totalPages']; ?>">Last</a></li>
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
