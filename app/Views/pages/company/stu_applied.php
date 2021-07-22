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
        <div class="row justify-content-center my-3">
            <!-- Students Applied for job -->
            <div class="col-md-12 my-2">
                <h1>List of Students -</h1>
                <div class="row my-2 border shadow">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover mb-0">
                            <thead class="bg-success">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Stuent Name</th>
                                    <th scope="col">College Name</th>
                                    <th scope="col">College CGPA</th>
                                    <th scope="col">Account Status</th>
                                    <th scope="col">Actions</th>
                                    <th scope="col">Message</th>
                            </thead>
                            <tbody>
                                <?php foreach ($data['student'] as $key => $student) : ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $student->name; ?></td>
                                        <td><?php echo $student->college_name; ?></td>
                                        <td><?php echo $student->current_cgpa; ?></td>
                                        <td><span class="badge badge-pill alert-<?php echo $student->account_status == 1 ? 'success' : ($student->account_status == 2 ? 'danger' : 'warning'); ?>"><?php echo $student->account_status == 1 ? 'Active' : ($student->account_status == 2 ? 'Banned' : ($student->account_status == 3 ? 'Rejected' : 'Inactive')); ?></span></td>
                                        <td>
                                            <?php if (in_array($student->account_status, [0, 2, 3])) : ?>
                                                <a href="/admin/activate/<?php echo $student->id; ?>" class="btn btn-success">Activate</a>
                                            <?php endif ?>
                                            <?php if ($student->account_status == 0) : ?>
                                                <a href="/admin/reject/<?php echo $student->id; ?>" class="btn btn-danger">Reject</a>
                                            <?php endif ?>
                                            <?php if ($student->account_status == 1) : ?>
                                                <a href="/admin/ban/<?php echo $student->id; ?>" class="btn btn-danger">Ban</a>
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <ul>
                                                <?php if ($student->active_backlogs != 0) : ?>
                                                    <li><?php echo "Student has " . $student->active_backlogs . " active backlogs."; ?></li>
                                                <?php endif ?>
                                                <?php if ($student->tenth_marks < 60) : ?>
                                                    <li><?php echo "Student has less than 60% in 10th."; ?></li>
                                                <?php endif ?>
                                                <?php if ($student->twelveth_marks < 60) : ?>
                                                    <li><?php echo "Student has less than 60% in 12th."; ?></li>
                                                <?php endif ?>
                                            </ul>
                                        </td>
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
