<?php
    $title="Search Item Table";
    include("../include/header.php");
    include("../include/sidebar.php");
    include("../include/navbar.php");
    include("../config/action.php");
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Result</h1>

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
                        <th>Table Name</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($_SESSION['user']['role'] === '1') {
                        if( isset($resultAccounts) && $resultAccounts !== "") {
                            if(mysqli_num_rows($resultAccounts) > 0) {
                                while ($value=mysqli_fetch_array($resultAccounts)) {
                                    ?>
                                    <tr>
                                        <td>User</td>
                                        <td>
                                            First Name: <?php echo $value['firstname']; ?> <br>
                                            Last Name: <?php echo $value['lastname']; ?> <br>
                                            Email: <?php echo $value['email']; ?> <br>
                                            CNIC: <?php echo $value['cnic']; ?> <br>
                                            Phone: <?php echo $value['phone']; ?> <br>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                    if(isset($resultBooks) && $resultBooks !== ""){
                        if(mysqli_num_rows($resultBooks) > 0) {
                            while ($value=mysqli_fetch_array($resultBooks)) {
                                ?>
                                <tr>
                                    <td>Book</td>
                                    <td>
                                        Book Name: <?php echo $value['bookname']; ?> <br>
                                        Rack Number: <?php echo $value['racknumber']; ?> <br>
                                        Author Name: <?php echo $value['authorname']; ?> <br>
                                        Price: <?php echo $value['price']; ?> <br>
                                        Purchase Date: <?php echo $value['datepurchase']; ?> <br>
                                    </td>
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
</div>
<!-- /.container-fluid -->

<?php
    include("../include/footer.php");
?>
