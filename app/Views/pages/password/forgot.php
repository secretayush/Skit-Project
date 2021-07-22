<?php
require $_ENV['ROOT'] . '/Views/partials/head.php';
?>
<div class="container" id="login">
    <main class="form-signin shadow bg-light rounded p-5">
        <form method="POST" action="/password/forgot">

            <div class="text-center">
                <img src="/Images/logo.png" alt="" height="50px" width="200px">
                <h1 class="h3 mb-3 fw-normal" style="font-family: 'Berkshire Swash', cursive;">Forgot Password</h1>
            </div>

            <div class="form-floating">
                <input type="email" class="form-control <?php if($data['email_error']): ?> is-invalid <?php endif; ?>" name="email" id="floatingInput" placeholder="name@example.com" requirede>
                <label for="floatingInput">Email address</label>
                <?php if (isset($data['email_error'])): ?>
                <div class="invalid-feedback" style="display: block;">
                  <?php echo $data['email_error']; ?>
                </div>
              <?php endif ?>
            </div>
            <button class="w-100 mt-3 btn btn-lg btn-primary" type="submit">Reset</button>
            <div class="text-muted my-3">
               <a href="/auth/login">Sign In</a>
            </div>
        </form>
    </main>
</div>
<?php
require $_ENV['ROOT'] . '/Views/partials/footer.php';
?>
