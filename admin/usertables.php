<?php
    $title="User Tables";
    include("../include/header.php");
    include("../include/sidebar.php");
    include("../include/navbar.php");
    include("../config/action.php");

    $result = showAll($obj, "*", "Accounts", "");
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">User Tables</h1>

    <div class="alert alert-warning" role="alert" id="messageForAccount"  style="text-align: center; display:none;"></div>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <!--<div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">User Tables</h6>
    </div>-->
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>CNIC</th>
                <th>Phone</th>
                <th>Operations</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if($result) {
                while($row=mysqli_fetch_array($result)) {
                    if($row['role']!=='1') {
            ?>
                        <tr id="row<?php echo $row['id']; ?>">
                          <td data-target="firstName"><?php echo $row['firstname']; ?></td>
                          <td data-target="lastName"><?php echo $row['lastname']; ?></td>
                          <td><?php echo $row['email']; ?></td>
                          <td data-target="cnic"><?php echo $row['cnic']; ?></td>
                          <td data-target="phone"><?php echo $row['phone']; ?></td>
                            <?php //$firstname = $row['firstname']; ?>
                            <!--data-toggle="modal" data-target="#modalUpdateAccount"-->
                          <td><a class="btn btn-outline-primary"  data-role="update" data-id="<?php echo $row['id']; ?>">Update</a>
                            <a class="btn btn-outline-danger" onclick="deleteAccount('<?php echo $row['id']; ?>')">Delete</a></td>
                        </tr>
            <?php
                    }
                }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

    <div class="modal fade" id="modalUpdateAccount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div id="submitUpdateAccountForm" class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Update Account</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <input id="id" type="hidden" class="form-control">
                    <div class="md-form mb-5">
                        <i class="fas fa-user prefix grey-text"></i>
                        <input id="firstName" type="text" name="firstName" class="form-control validate">
                        <label data-error="wrong" data-success="right" for="orangeForm-name">First Name</label>
                    </div>
                    <div class="md-form mb-5">
                        <i class="fas fa-user prefix grey-text"></i>
                        <input id="lastName" type="text" name="lastName" class="form-control validate">
                        <label data-error="wrong" data-success="right" for="orangeForm-email">Last Name</label>
                    </div>

                    <div class="md-form mb-4">
                        <i class="fas fa-id-card prefix grey-text"></i>
                        <input id="cnic" type="text" name="cnic" class="form-control validate">
                        <label data-error="wrong" data-success="right" for="orangeForm-pass">CNIC</label>
                    </div>

                    <div class="md-form mb-4">
                        <i class="fas fa-phone prefix grey-text"></i>
                        <input id="phone" type="text" name="phone" class="form-control validate">
                        <label data-error="wrong" data-success="right" for="orangeForm-pass">Phone</label>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <a id="updateAccount" class="btn btn-outline-primary">Update</a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<?php
    include("../include/footer.php");
?>