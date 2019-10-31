<?php
    $title = "Registration";
    include("include/header.php");
    include("../config/action.php");
?>
<div class="card o-hidden border-0 shadow-lg my-5">
			<div class="card-body p-0">
				<!-- Nested Row within Card Body -->
				<div class="row">
					<div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
					<div class="col-lg-7">
						<div class="p-5">
							<div class="text-center">
								<h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
							</div>
							<?php
									if(!empty($msg)){
                            ?>
                            <div class='alert alert-warning' role='alert'><?php echo $msg; ?></div>
                            <?php
									}
							?>
							<form id="regForm" class="user" method="post" action="">
								<input type="hidden" id="reg" name="reg">
								<div class="form-group row">
									<div class="col-sm-6 mb-3 mb-sm-0">
										<input type="text" class="form-control form-control-user" id="firstName" name="firstName" placeholder="First Name">
										<!--<span id="fNameError" style="display:none;">Fill First name field</span>-->
									</div>
									<div class="col-sm-6">
										<input type="text" class="form-control form-control-user" id="lastName" name="lastName" placeholder="Last Name">
									</div>
								</div>
								<div class="form-group">
									<input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address">
								</div>
								<div class="form-group row">
									<div class="col-sm-6 mb-3 mb-sm-0">
										<input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
									</div>
									<div class="col-sm-6">
										<input type="password" class="form-control form-control-user" id="repeatPassword" name="repeatPassword" placeholder="Repeat Password">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-6 mb-3 mb-sm-0">
										<input type="text" class="form-control form-control-user" id="cnic" name="cnic" placeholder="CNIC">
									</div>
									<div class="col-sm-6">
										<input type="text" class="form-control form-control-user" id="phone" name="phone" placeholder="Phone Number (format: 03XXXXXXXXX)">
									</div>
								</div>
								<button id="createAccountBtn" type="button" name="button" class="btn btn-primary btn-user btn-block">
									Register Account
								</button>
								<hr>
							</form
							<hr>
							<div class="text-center">
								<a class="small" href="forgot-password.php">Forgot Password?</a>
							</div>
							<div class="text-center">
								<a class="small" href="login.php">Already have an account? Login!</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
<?php
	include("include/footer.php");
?>
