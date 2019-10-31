<?php
		$title="Profile";
		include("../include/header.php");
		include("../include/sidebar.php");
		include("../include/navbar.php");
        include("../config/action.php");
?>
<!-- Begin Page Content -->
  <div class="container-fluid">
        <?php
        if(!empty($msg)){
        ?>
            <div id="adminProfileMsg" style="text-align: center" class='alert alert-warning' role='alert'><?php echo $msg; ?></div>
        <?php
        }
        ?>
    <form id="updateProfileForm" class="user" method="post" action="">
      <input type="hidden" id="adminUpdate" name="adminUpdate">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="firstName">First Name</label>
          <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" value="<?PHP echo $_SESSION['user']['firstname']; ?>">
        </div>
        <div class="form-group col-md-6">
          <label for="lastName">Last Name</label>
          <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name" value="<?PHP echo $_SESSION['user']['lastname']; ?>">
        </div>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="text" class="form-control" readonly value="<?PHP echo $_SESSION['user']['email']; ?>">
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
        </div>
        <div class="form-group col-md-6">
          <label for="repeatPassword">Repeat Password</label>
          <input type="password" class="form-control" id="repeatPassword" name="repeatPassword" placeholder="Repeat Password">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="cnic">CNIC</label>
          <input type="text" class="form-control" id="cnic" name="cnic" placeholder="Enter CNIC" value="<?PHP echo $_SESSION['user']['cnic']; ?>">
        </div>
        <div class="form-group col-md-6">
          <label for="phone">Phone</label>
          <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone" value="<?PHP echo $_SESSION['user']['phone']; ?>">
        </div>
      </div>
      <button type="button" id="updateAdminButton" class="button btn btn-primary btn-lg btn-block">Update</button>
    </form>
  </div>
<?php
		include("../include/footer.php");
?>
