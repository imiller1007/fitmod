<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container mt-5" id="card-section">

    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6">
        <?php flash('register_success'); ?>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title WOtitle text-center">Log In</h3>
                    <br>
                    <form action="<?php echo URLROOT; ?>/users/login" method="post">
                        <div class="mb-3">
                            <label for="user_email">Email address</label>
                            <input name="user_email" type="email" id="email" class="form-control <?php echo (!empty($data['user_email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['user_email']; ?>" >
                            <span class="invalid-feedback"><?php echo $data['user_email_err']; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <div class="input-group" id="show_hide_password">
                                <input type="password" name="password" class="form-control form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                                    <span class="input-group-text"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                            </div>
                            
                        </div>
                        <button type="submit" class="btn btn-dark">Log in</button>
                    </form>
                    <br>
                    <p>Don't have an account yet? <a href="<?php echo URLROOT; ?>/users/register" class="dark-link">Click here</a> to Sign up!</p>
                </div>
            </div>
            <br>
            <div class="row text-center">
                <a href="#" class="dark-link mx-auto">Forgot my email/password</a>
            </div>
        </div>
    </div>

</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>