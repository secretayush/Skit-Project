<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>

<div class="content-wrapper">
    <div class="container">
        <div class="row justify-content-center my-3">
            <!-- Job post -->
            <div class="col-xl-5 my-5">
                <div class="card shadow bg-light rounded" id="company_card">
                    <h1 class="text-center" style="font-family: 'Kalam', cursive">Job Post</h1>
                    <div class="card-body py-md-4">
                        <form data-parsley-validate method="POST" action="" enctype="multipart/form-data" id="job_post_form">
                            <div class="form-group">
                                <label for="job_title"><b>Job Title-</b><span class="red"> *</span></label>
                                <input type="text" class="form-control" id="job_title" name="job_title" placeholder="Job title..." required data-parsley-trigger="change" >
                                <i class="fa fa-exclamation-circle job_title" aria-hidden="true"></i>
                                <i class="fa fa-check-circle job_title" aria-hidden="true"></i>
                            </div>
                            <div class="form-group">
                                <label for="expire_date"><b>Expire date-</b><span class="red"> *</span></label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                                <i class="fa fa-exclamation-circle expiry_date" aria-hidden="true"></i>
                                <i class="fa fa-check-circle expiry_date" aria-hidden="true"></i>
                            </div>
                            <div class=" form-group">
                                <label for="job_salary"><b>Salary-</b></label>
                                <input type="text" class="form-control" id="job_salary" name="job_salary" placeholder="Job Salary...">
                            </div>
                            <div class="form-group">
                                <label for="job_openings"><b>Number of Job openings-</b></label>
                                <input type="text" class="form-control" id="job_openings" name="job_openings" placeholder="Number of job openings...">
                            </div>
                            <div class="form-group">
                                <label for="desc"><b>Description-</b><span class="red"> *</span></label>
                                <textarea class="md-textarea form-control" id="desc" name="desc" rows="4" placeholder="Job description..." spellcheck="false" required data-parsley-trigger="change"></textarea>
                                <i class="fa fa-exclamation-circle desc" aria-hidden="true"></i>
                                <i class="fa fa-check-circle desc" aria-hidden="true"></i>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="post_job">Post Job!</button>
                            </div>
                        </form>
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
