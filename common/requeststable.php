<?php
$title="Requests Table";
include("../include/header.php");
include("../include/sidebar.php");
include("../include/navbar.php");
include("../config/action.php");

$select="requests.*, accounts.`firstname`, accounts.`lastname` , books.`bookname`";
if($_SESSION['user']['role'] === '1') {
    $tableName = "requests JOIN accounts ON requests.`studentid` = accounts.`id` JOIN books ON requests.`bookid` = books.`id` ";
} elseif($_SESSION['user']['role'] === '2') {
    $tableName = "requests JOIN accounts ON requests.`studentid` = accounts.`id` JOIN books ON requests.`bookid` = books.`id` WHERE studentid='".$_SESSION['user']['id']."' ";
}
$whereClause = "";

$result = showAll($obj, $select, $tableName, $whereClause);
?>

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Requests Tables</h1>

        <div class="d-flex justify-content-center">
            <div id="approveOrRejectLoader" class="spinner-border" role="status" style="display:none;">
            </div>
        </div>

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
                            <th>Student Name</th>
                            <th>Book Name</th>
                            <th>Status</th>
                            <?php
                                if($_SESSION['user']['role'] === "1") {
                                    echo '<th>Operations</th>';
                                }
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if($result) {
                            while($row=mysqli_fetch_array($result)) {
                                ?>
                                    <tr id="requestRow<?php echo $row['id']; ?>">
                                        <td><?php echo $row['firstname']." ".$row['lastname']; ?></td>
                                        <td><?php echo $row['bookname']; ?></td>
                                        <td data-target="status">
                                            <?php
                                            if($row['status'] === '0') {
                                                echo "Pending";
                                                $disabled = "";
                                                $collectDisabled = "disabled";
                                            } elseif($row['status'] === '1') {
                                                echo "Approved";
                                                $disabled = "disabled";
                                                $collectDisabled = "";
                                            } elseif($row['status'] === '2') {
                                                echo "Rejected";
                                                $disabled = "disabled";
                                                $collectDisabled = "disabled";
                                            }  elseif($row['status'] === '3') {
                                                echo "Approved and Collected";
                                                $disabled = "disabled";
                                                $collectDisabled = "disabled";
                                            }
                                            ?>
                                        </td>
                                            <?php
                                            if($_SESSION['user']['role'] === "1") {
                                            ?>
                                                <td>
                                                    <button id="approveButton<?php echo $row['id']; ?>" class="btn btn-outline-primary" data-role="approveBookRequest" data-requestid="<?php echo $row['id']; ?>" data-requestbookid="<?php echo $row['bookid']; ?>" data-requeststudentid="<?php echo $row['studentid']; ?>" <?php echo $disabled; ?>>Approve</button>
                                                    <button id="rejectButton<?php echo $row['id']; ?>" class="btn btn-outline-danger" onclick="rejectBookRequest('<?php echo $row['id']; ?>', '<?php echo $row['studentid']; ?>', '<?php echo $row['bookid']; ?>')"<?php echo $disabled; ?>>Reject</button>
                                                    <button id="collectButton<?php echo $row['id']; ?>" class="btn btn-outline-secondary" onclick="collectBook('<?php echo $row['id']; ?>', '<?php echo $row['studentid']; ?>', '<?php echo $row['bookid']; ?>')"<?php echo $collectDisabled; ?>>Collect</button>
                                                    <div class="alert alert-warning" role="alert" id="messageForRequestsBook<?php echo $row['id']; ?>" style="display:none;"></div>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                    </tr>
                                    <?php
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
?><?php
