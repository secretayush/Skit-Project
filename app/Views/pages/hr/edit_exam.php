<?php
require $_ENV['ROOT'] . '/Views/partials/head.php';
?>
<div class="d-flex align-items-stretch admin-panel">
    <?php
    require $_ENV['ROOT'] . '/Views/pages/hr/partials/sidebar.php';
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-2">
                <h1>List of prepared exams -</h1>
                <div class="row my-2 shadow">
                    <table class="table mb-0">
                        <thead class="bg-secondary">
                            <tr>
                                <th scope="col">Sr.NO</th>
                                <th scope="col">Job post</th>
                                <th scope="col">Edit paper</th>
                                <th scope="col">Remark from admin</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>ayush</td>
                                <td> <a href="#" class="badge bg-success text-primary">edit</a> </td>
                                <td>Go go</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once $_ENV['ROOT'] . '/Views/partials/footer.php';
?>
