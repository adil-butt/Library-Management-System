<?php
$title="Books Tables";
include("../include/header.php");
include("../include/sidebar.php");
include("../include/navbar.php");
include("../config/action.php");

$select="books.*, accounts.`firstname`, accounts.`lastname`";
$tableName="books JOIN accounts ON books.`adminid` = accounts.`id`";
//$addJoin="JOIN accounts ON books.`adminid` = accounts.`id`";
$whereClause="";

$result = showAll($obj, $select, $tableName, $whereClause);

?>

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->

    <?php
    $customId = "";
    $customIcon = "";
    $customMessage = "";
    $additionInAddedByColumn = "";
    $customColumn = "";
    $customColumnValue = "";
    if($_SESSION['user']['role'] === '1') {
        $customId = "addBookButton";
        $customIcon = "fa-book";
        $customMessage = "Add";
        $customColumn = "Operations";
        $customColumnValue = "";
    } elseif($_SESSION['user']['role'] === '2') {
        $customId = "viewRequestButton";
        $customIcon = "fa-info-circle";
        $customMessage = "View your requests";
        $additionInAddedByColumn = "<b> (Admin)</b>";
        $customColumn = "Status";
        $customColumnValue = "Operations";
    }
    ?>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Books Tables</h1>
            <a id="<?php echo $customId; ?>" href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas <?php echo $customIcon; ?> fa-sm text-white-50"></i> <?php echo $customMessage; ?></a>
        </div>

        <div class="alert alert-warning" role="alert" id="messageForBook"  style="text-align: center; display:none;"></div>

        <div class="d-flex justify-content-center">
            <div id="requestLoader" class="spinner-border" role="status" style="display:none;">
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
                            <th>Book Name</th>
                            <th>Rack Number</th>
                            <th>Author Name</th>
                            <th>Price</th>
                            <th>Purchase Date</th>
                            <th>Added By</th>
                            <th><?php echo $customColumn; ?></th>
                        </tr>
                        </thead>
                        <tbody id="bookRow">
                        <?php
                        if($result) {
                            while($row=mysqli_fetch_array($result)) {
                        ?>
                                    <tr id="bookRow<?php echo $row['id']; ?>">
                                        <td data-target="bookName"><?php echo $row['bookname']; ?></td>
                                        <td data-target="rackNumber"><?php echo $row['racknumber']; ?></td>
                                        <td data-target="authorName"><?php echo $row['authorname']; ?></td>
                                        <td data-target="price"><?php echo $row['price']; ?></td>
                                        <td data-target="purchaseDate"><?php echo $row['datepurchase']; ?></td>
                                        <td><?php echo $row['firstname']." ".$row['lastname'].$additionInAddedByColumn?></td>
                                        <!--data-toggle="modal" data-target="#modalUpdateAccount"-->
                                        <td><?php if($_SESSION['user']['role'] === '1') {
                                            ?>
                                                <a class="btn btn-outline-primary"  data-role="updateBook" data-bookid="<?php echo $row['id']; ?>">Update</a>
                                                <a class="btn btn-outline-danger" onclick="deleteBook('<?php echo $row['id']; ?>')">Delete</a>
                                            <?php
                                            } elseif($_SESSION['user']['role'] === '2') {
                                                if($row['flag'] === '0') {
                                                    echo 'Available';
                                            ?>
                                                    <div class="d-sm-flex align-items-center justify-content-between">
                                                    <button type="button" href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm issueBookRequest" data-bookid="<?php echo $row['id']; ?>" data-bookname="<?php echo $row['bookname']; ?>">Make an Issue Request</button>
                                                    </div>
                                            <?php
                                                } elseif($row['flag'] === '1') {
                                                    echo 'Not Available';
                                            ?>
                                                    <div class="d-sm-flex align-items-center justify-content-between">
                                                    <button type="button" href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" disabled>Make an Issue Request</button>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </td>
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

    <!-- /.container-fluid -->

    <!-- Issue Request Modal -->
    <div class="modal fade" id="issueBookModal" tabindex="-1" role="dialog" aria-labelledby="issueBookModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalTitle">Request for Issue a book</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure to make a request for this book?
                </div>
                <div class="modal-footer">
                    <input id="bookIssueId" type="hidden" class="form-control">
                    <input id="bookIssueName" type="hidden" class="form-control">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="issueRequestButton" type="button" class="btn btn-primary">Issue</button>
                </div>
            </div>
        </div>
    </div>

    <!-- book modal -->
    <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input id="bookId" type="hidden" class="form-control">
                        <div class="form-group">
                            <label>Book Name</label>
                            <input id="bookName" type="text" class="form-control" placeholder="Eg: Computer Science">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Rack Number</label>
                                <input id="rackNumber" type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Author Name</label>
                                <input id="authorName" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Price</label>
                                <input id="bookPrice" type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Date Purchase</label>
                                <input id="purchaseDate" type="date" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="submitBookButton" type="button" class="btn btn-primary"></button>
                </div>
            </div>
        </div>
    </div>
<?php
include("../include/footer.php");
?>