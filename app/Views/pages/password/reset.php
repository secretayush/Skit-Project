<?php
require $_ENV['ROOT'] . '/Views/partials/head.php';
?>
<div class="container" id="login">
    <main class="form-signin shadow bg-light rounded p-5">
        <form data-parsley-validate method="POST" action="/password/reset/<?php echo $data['reset_email'] . '/' . $data['reset_token']; ?>" id="resetform">

            <div class="text-center">
                <img src="/Images/logo.png" alt="" height="50px" width="200px">
                <h1 class="h3 mb-3 fw-normal" style="font-family: 'Berkshire Swash', cursive;">Reset Password</h1>
            </div>

            <div class="form-group">
                <label for="floatingInput">Password:</label>
                <input type="password" class="form-control" name="password" id="floatingInput1" required data-parsley-pattern="^.{6,}$" data-parsley-trigger="change">
                <i class="fa fa-exclamation-circle confirm_password" aria-hidden="true"></i>
                <i class="fa fa-check-circle confirm_password" aria-hidden="true"></i>
            </div>

            <div class="form-group" style="margin-top : 25px">
                <label for="floatingInput">Confirm Password:</label>
                <input type="password" class="form-control" name="confirm_password" id="floatingInput" required data-parsley-equalto="#password" data-parsley-trigger="change">
                <i class="fa fa-exclamation-circle confirm_password" aria-hidden="true"></i>
                <i class="fa fa-check-circle confirm_password" aria-hidden="true"></i>
            </div>
            <button class="w-100 mt-3 btn btn-lg btn-primary" type="submit">Reset</button>
        </form>
    </main>
</div>
<?php
require $_ENV['ROOT'] . '/Views/partials/footer.php';
?>
