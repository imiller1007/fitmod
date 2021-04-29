<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container mt-5" id="card-section">

    <div class="row">
        <div class="offset-lg-3 offset-xs-0 col-xs-12 col-sm-12 col-lg-6">

            <div class="card">
                <div class="card-body">
                    <h3 class="card-title WOtitle text-center">Sign Up</h3>
                    <br>
                    <form action="<?php echo URLROOT; ?>/users/register" method="post">
                        <div class="form-group mb-3">
                            <label for="user_email">Email</label>
                            <input type="email" name="user_email" class="form-control form-control <?php echo (!empty($data['user_email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['user_email']; ?>">
                            <span class="invalid-feedback"><?php echo $data['user_email_err']; ?></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="user_first">First Name</label>
                            <input type="text" name="user_first" class="form-control form-control <?php echo (!empty($data['user_first_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['user_first']; ?>">
                            <span class="invalid-feedback"><?php echo $data['user_first_err']; ?></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="user_last">Last Name</label>
                            <input type="text" name="user_last" class="form-control form-control <?php echo (!empty($data['user_last_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['user_last']; ?>">
                            <span class="invalid-feedback"><?php echo $data['user_last_err']; ?></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <div class="input-group" id="show_hide_password">
                                <input type="password" name="password" class="form-control form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                                    <span class="input-group-text"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                            </div>
                            
                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_pass">Confirm Password</label>
                            <div class="input-group" id="show_hide_password">
                                <input type="password" name="confirm_pass" class="form-control form-control <?php echo (!empty($data['confirm_pass_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_pass']; ?>">
                                    <span class="input-group-text"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                                <span class="invalid-feedback"><?php echo $data['confirm_pass_err']; ?></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark">Sign up</button>
                    </form>
                    <br>
                    <p>Already have an account? <a href="<?php echo URLROOT; ?>/users/login" class="dark-link">Click here</a> to log in!</p>
                </div>
            </div>

        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>