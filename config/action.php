<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include("dboperations.php");
$obj = new DbOperations($conn);

if(isset($_GET['logout'])) {
    session_start();
    unset($_SESSION['user']);
    session_destroy($_SESSION['user']);
    header("Location:../auth/login.php");
    exit();
}

if(isset($_POST['reg'])) {
    $msg = accountRegistration($obj);
} elseif(isset($_POST['loginButton'])) {
    $msg = login($obj);
} elseif(isset($_POST['adminUpdate'])) {
    $msg = accountUpdate($obj);
} elseif(isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];
    $whereClause="WHERE id='$deleteId'";
    $result = $obj->delete("accounts", $whereClause);
    if($result) {
        $response = array(
            'status' => 1,
            'statusMessage' => "Deleted Successfully"
        );
    } else {
        $response = array(
            'status' => 0,
            'statusMessage' => mysqli_error($obj->getConn())
        );
    }
    echo json_encode($response);
} elseif(isset($_POST['updateId'])) {
    echo json_encode(userAccountUpdate($obj));
} elseif(isset($_POST['newBookName'])) {
    echo json_encode(addBook($obj));
} elseif(isset($_POST['deleteBookId'])) {
    $id = $_POST['deleteBookId'];
    $whereClause="WHERE id='$id'";
    $result = $obj->delete("books", $whereClause);
    if($result) {
        $response = array(
            'status' => 1,
            'statusMessage' => "Deleted Successfully"
        );
    } else {
        $response = array(
            'status' => 0,
            'statusMessage' => mysqli_error($obj->getConn())
        );
    }
    echo json_encode($response);
} elseif(isset($_POST['updateBookId'])) {
    echo json_encode(updateBook($obj));
} elseif(isset($_POST['bookIssueId'])) {
    echo json_encode(issueBook($obj));
} elseif(isset($_POST['rejectRequestId'])) {
    echo json_encode(rejectBookRequest($obj));
} elseif(isset($_POST['approveRequestId'])) {
    echo json_encode(approveBookRequest($obj));
} elseif(isset($_POST['collectId'])) {
    echo json_encode(collectBook($obj));
} elseif(isset($_POST['search'])) {
    if($_POST['search'] !== '') {
        if($_SESSION['user']['role'] === '1') {
            $resultAccounts = $obj->select("*","accounts", "CONCAT(firstname, lastname, email,phone,cnic) LIKE '%".$_POST['search']."%' ");
        }
        $resultBooks = $obj->select("*","books", "CONCAT(bookname,racknumber,authorname) LIKE '%".$_POST['search']."%' ");
    } else {
        $resultAccounts = "";
        $resultBooks = "";
    }
}

function sendMail($subject, $body, $address, $addressName) {
    require '../vendor/autoload.php';
    $mail = new PHPMailer(TRUE);

    /* Open the try/catch block. */
    try {
        $mail->IsSMTP();                           // telling the class to use SMTP
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->Host       = "smtp.gmail.com"; // set the SMTP server
        $mail->Port       = 587;                    // set the SMTP port
        $mail->Username   = "adil.islam@purelogics.net"; // SMTP account username
        $mail->Password   = "purelogics7861";        // SMTP account password
        /* Set the mail sender. */
        $mail->setFrom('adil.islam@purelogics.net', 'LMS');

        /* Set the subject. */
        $mail->Subject = $subject;

        /* Set the mail message body. */
        $mail->Body = $body;

        /* Finally send the mail. */
        //send email
        /* Add a recipient. */
        $mail->addAddress($address, $addressName);
        if($mail->send() !== 0) {
            return true;
        } else {
            return false;
        }
    }
    catch (Exception $e)
    {
        /* PHPMailer exception. */
        return $response = array(
            'status' => 0,
            'statusMessage' => $e->errorMessage()
        );
    }
    catch (\Exception $e)
    {
        /* PHP exception (note the backslash to select the global namespace Exception class). */
        return $response = array(
            'status' => 0,
            'statusMessage' => $e->getMessage()
        );
    }
}

function collectBook($obj) {
    $collectId = $_POST['collectId'];
    $studentId = $_POST['studentId'];
    $bookId = $_POST['bookId'];
    $whereClause2 = " WHERE id=".$collectId." AND status != '1' ";
    $result = $obj->select("*", "requests", $whereClause2);
    $count = mysqli_fetch_assoc($result);
    if($count > 0) {
        return $response = array(
            'status' => 0,
            'statusMessage' => "You do not have the permission to collect this book"
        );
    }
    $whereClause = " WHERE id=".$bookId;
    $columnData=array("flag"=>'0');
    $result = $obj->update("books",$columnData, $whereClause);
    $whereClause2 = " WHERE id=".$collectId;
    $columnData2=array("status"=>'3');
    $result2 = $obj->update("requests",$columnData2, $whereClause2);
    if($result && $result2) {
        $whereClause = " WHERE id=".$studentId;
        $whereClause2 = " WHERE id=".$bookId;
        $result = $obj->select("email, firstname, lastname", "accounts", $whereClause);
        $result2 = $obj->select("bookname", "books", $whereClause2);
        if($result && $result2) {
            $row=mysqli_fetch_array($result);
            $row2=mysqli_fetch_array($result2);
            $studentName = $row['firstname']." ".$row['lastname'];
            $bookName = $row2['bookname'];

            $subject = "Successfully Collected ". $bookName. " Book";
            $body = "Your have successfully returned the ".$bookName." book to admin. Thank You.";
            $address = $row['email'];
            $addressName = $studentName;

            if(sendMail($subject, $body, $address, $addressName)) {
                return $response = array(
                    'status' => 1,
                    'statusMessage' => "Collected Successfully"
                );
            }
        }
    }
    return $response = array(
        'status' => 0,
        'statusMessage' => mysqli_error($obj->getConn())
    );
}

function approveBookRequest($obj){
    $approveRequestId = $_POST['approveRequestId'];
    $studentId = $_POST['studentId'];
    $bookId = $_POST['bookId'];
    $whereClause = " WHERE id=".$approveRequestId." AND status != '0' ";
    $result = $obj->select("*", "requests", $whereClause);
    $count = mysqli_fetch_assoc($result);
    if($count > 0) {
        return $response = array(
            'status' => 0,
            'statusMessage' => "You do not have the permission to approve this book"
        );
    }
    $whereClause = " WHERE id=".$approveRequestId;
    $columnData=array("status"=>'1');
    $result = $obj->update("requests",$columnData, $whereClause);
    if($result) {
        $whereClause = " WHERE id=".$bookId;
        $whereClause2 = " WHERE id=".$studentId;
        $columnData=array("flag"=>'1');
        $result = $obj->update("books",$columnData, $whereClause);
        $result2 = $obj->select("email, firstname, lastname", "accounts", $whereClause2);
        $result3 = $obj->select("bookname", "books", $whereClause);
        if($result && $result2 && $result3) {
            $row=mysqli_fetch_array($result2);
            $row2=mysqli_fetch_array($result3);
            $studentName = $row['firstname']." ".$row['lastname'];
            $bookName = $row2['bookname'];

            $subject = "Approved Request for ". $bookName. " Book";
            $body = "Your request for the ".$bookName." book has been successfully approved. Contact to admin for further processing.";
            $address = $row['email'];
            $addressName = $studentName;

            if(sendMail($subject, $body, $address, $addressName)) {
                return $response = array(
                    'status' => 1,
                    'statusMessage' => "Approved Successfully"
                );
            }
        }
    }
    return $response = array(
        'status' => 0,
        'statusMessage' => mysqli_error($obj->getConn())
    );
}

function rejectBookRequest($obj){
    $rejectRequestId = $_POST['rejectRequestId'];
    $studentId = $_POST['studentId'];
    $bookId = $_POST['bookId'];
    $whereClause = " WHERE id=".$rejectRequestId." AND status != '0' ";
    $result = $obj->select("*", "requests", $whereClause);
    $count = mysqli_fetch_assoc($result);
    if($count > 0) {
        return $response = array(
            'status' => 0,
            'statusMessage' => "You do not have the permission to reject this book"
        );
    }
    $whereClause = " WHERE id=".$rejectRequestId;
    $columnData=array("status"=>'2');
    $result = $obj->update("requests",$columnData, $whereClause);
    if($result) {
        $whereClause = " WHERE id=".$studentId;
        $whereClause2 = " WHERE id=".$bookId;
        $result = $obj->select("email, firstname, lastname", "accounts", $whereClause);
        $result2 = $obj->select("bookname", "books", $whereClause2);
        if($result && $result2) {
            $row=mysqli_fetch_array($result);
            $row2=mysqli_fetch_array($result2);
            $studentName = $row['firstname']." ".$row['lastname'];
            $bookName = $row2['bookname'];

            $subject = "Rejected Request for ". $bookName. " Book";
            $body = "Your request for the ".$bookName." book has rejected. Contact to admin if you have any query.";
            $address = $row['email'];
            $addressName = $studentName;

            if(sendMail($subject, $body, $address, $addressName)) {
                return $response = array(
                    'status' => 1,
                    'statusMessage' => "Rejected Successfully"
                );
            }
        }
    }
    return $response = array(
        'status' => 0,
        'statusMessage' => mysqli_error($obj->getConn())
    );
}

function addBook($obj) {
    $bookName = strip_tags($_POST['newBookName']);
    $rackNumber= strip_tags($_POST['newRackNumber']);
    $authorName = strip_tags($_POST['newAuthorName']);
    $bookPrice = strip_tags($_POST['newBookPrice']);
    $purchaseDate = strip_tags($_POST['newPurchaseDate']);

    if ($bookName  === '' || $rackNumber === '' || $authorName === '' || $bookPrice === '' || $purchaseDate  === '' ||
        !is_numeric($bookPrice)) {
        return $response = array(
            'status' => 0,
            'statusMessage' => 'Something went wrong'
        );
    } else {
        session_start();
        $columnData=array("bookname"=>$bookName,"racknumber"=>$rackNumber,"authorname"=>$authorName,"price"=>$bookPrice,"datepurchase"=>$purchaseDate,"adminid"=>$_SESSION['user']['id']);
        $result = $obj->insert("books",$columnData);
        if($result) {
            $last_id = mysqli_insert_id($obj->getConn());
            return $response = array(
                'status' => 1,
                'statusMessage' => "Added Successfully",
                'id' => $last_id,
                'add' => $_SESSION['user']['firstname']." ".$_SESSION['user']['lastname']
            );
        } else {
            return $response = array(
                'status' => 0,
                'statusMessage' => mysqli_error($obj->getConn())
            );
        }
    }
}

function issueBook($obj) {
    $id = $_POST['bookIssueId'];
    session_start();

    $whereClause=" studentid = '".$_SESSION['user']['id']."' AND bookid= '".$id."' ";
    $requestResult = $obj->select("*","requests",$whereClause);
    $count = mysqli_num_rows($requestResult);

    if($count>0 && $requestResult) {
        while($row = mysqli_fetch_assoc($requestResult)) {
            if($row['status'] === '0') {
                return $response = array(
                    'status' => 0,
                    'statusMessage' => 'You have already requested for this book and your request is pending. kindly wait for approval/rejection'
                );
            }
        }
    }

    $whereClause = "WHERE role='1'";
    $result = $obj->select("email, firstname, lastname","accounts",$whereClause);

    if($result) {
        $name = $_POST['bookIssueName'];
        $studentName = $_SESSION['user']['firstname']." ".$_SESSION['user']['lastname'];
        $subject = "New ". $name . " Book Issue Request From ".$studentName;
        $body = $studentName." has requested for issue ". $name ." book. Kindly respond him/her as soon as possible";
        $check = 0;
        while($row=mysqli_fetch_array($result)) {
            if(sendMail($subject, $body, $row['email'], $row['firstname']." ".$row['lastname'])) {
                $check = 1;
            }
        }

        if($check === 1) {
            /*send mail to student*/
            /*$subject = "Successfully Submit Request for ". $name . " Book";
            $body = "Your request for ". $name ." book is successfully submitted and handled soon. Thank You";
            $address = $_SESSION['user']['email'];
            $addressName = $_SESSION['user']['firstname']." ".$_SESSION['user']['lastname'];
            sendMail($subject, $body, $address, $addressName);*/

            $columnData=array("studentid"=>$_SESSION['user']['id'],"bookid"=>$id);
            $result = $obj->insert("requests",$columnData);
            if($result) {
                return $response = array(
                    'status' => 1,
                    'statusMessage' => 'Your Request has been successfully sent to admin.'
                );
            }
        } else {
            return $response = array(
                'status' => 0,
                'statusMessage' => 'Sorry there is a problem. Your request could not be sent to admin.'
            );
        }
    }
    return $response = array(
        'status' => 0,
        'statusMessage' => mysqli_error($obj->getConn())
    );
}

function updateBook($obj) {
    $id = strip_tags($_POST['updateBookId']);
    $bookName = strip_tags($_POST['updateBookName']);
    $rackNumber= strip_tags($_POST['updateRackNumber']);
    $authorName = strip_tags($_POST['updateAuthorName']);
    $bookPrice = strip_tags($_POST['updateBookPrice']);
    $purchaseDate = strip_tags($_POST['updatePurchaseDate']);

    if ($bookName  === '' || $rackNumber === '' || $authorName === '' || $bookPrice === 0 || $purchaseDate  === '' || !is_numeric($bookPrice)) {
        return $response = array(
            'status' => 0,
            'statusMessage' => 'Something went wrong'
        );
    } else {
        $whereClause = " WHERE id=".$id;
        $columnData=array("bookname"=>$bookName,"racknumber"=>$rackNumber,"authorname"=>$authorName,"price"=>$bookPrice,"datepurchase"=>$purchaseDate);
        $result = $obj->update("books",$columnData, $whereClause);
        if($result) {
            return $response = array(
                'status' => 1,
                'statusMessage' => "Updated Successfully"
            );
        } else {
            return $response = array(
                'status' => 0,
                'statusMessage' => mysqli_error($obj->getConn())
            );
        }
    }
}

function userAccountUpdate($obj) {
    $id = strip_tags($_POST['updateId']);
    $firstName = strip_tags($_POST['firstName']);
    $lastName= strip_tags($_POST['lastName']);
    $cnic = strip_tags($_POST['cnic']);
    $phone = strip_tags($_POST['phone']);

    if ($firstName  === '' || $lastName === '' || $cnic === '' || $phone === ''  || !is_numeric($cnic) ||
        !is_numeric($phone) || strlen($cnic) !== 13 || strlen($phone) !== 11) {
        return $response = array(
            'status' => 0,
            'statusMessage' => 'Something went wrong'
        );
    } else {
        $whereClause = " WHERE id=".$id;
        $columnData=array("firstname"=>$firstName,"lastname"=>$lastName,"cnic"=>$cnic,"phone"=>$phone);
        $result = $obj->update("accounts",$columnData,$whereClause);
        if($result) {
            return $response = array(
                'status' => 1,
                'statusMessage' => "Updated Successfully"
            );
        } else {
            return $response = array(
                'status' => 0,
                'statusMessage' => mysqli_error($obj->getConn())
            );
        }
    }
}

function accountRegistration($obj) {
    $firstName = strip_tags($_POST['firstName']);
    $lastName= strip_tags($_POST['lastName']);
    $email = strip_tags($_POST['email']);
    $password = strip_tags($_POST['password']);
    $repeatPassword = $_POST['repeatPassword'];
    $cnic = strip_tags($_POST['cnic']);
    $phone = strip_tags($_POST['phone']);

    if ($firstName  === '' || $lastName === '' || $email === '' || $password === '' || $repeatPassword === '' ||
    $cnic === '' || $phone === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8
    || $password !== $repeatPassword || !is_numeric($cnic) || !is_numeric($phone) || strlen($cnic) !== 13 ||
    strlen($phone) !== 11) {
        return "Something went wrong";
    } else {
        $password=md5($_POST['password']);

        $columnData=array("firstname"=>$firstName,"lastname"=>$lastName,"email"=>$email,"password"=>$password,"cnic"=>$cnic,"phone"=>$phone,"role"=>"2");

        $result = $obj->insert("accounts",$columnData);

        if($result) {
            return "Account Successfully Created";
        } else {
           return mysqli_error($obj->getConn());
        }
    }
}

function login($obj) {
    $msg = "Invalid Login Credentials";
    $email = $_POST['email'];
    $password = $_POST['password'];

    $whereClause = " email = '".$email."' AND password = '".md5($password)."' ";
    $result = $obj->select("*", "accounts", $whereClause);

    //echo '<pre>';
    //print_r($result);
    //exit;

    $row = mysqli_fetch_assoc($result);
    unset($row["password"]);
    $count = mysqli_num_rows($result);
    if($count >= 1) {
        $_SESSION['user']=$row;
        if($row["role"] === "1") {
            header("location:../admin/dashboard.php");
            exit();
        } elseif($row['role'] === "2") {
            header("location:../student/dashboard.php");
            exit();
        }
    }
    return $msg;
}

function accountUpdate($obj) {
    $msg = "Something went wrong";
    $firstName = strip_tags($_POST['firstName']);
    $lastName= strip_tags($_POST['lastName']);
    if(!empty($_POST['password'])) {
        $password = strip_tags($_POST['password']);
        $repeatPassword = $_POST['repeatPassword'];
        if(strlen($password) < 8 || $password !== $repeatPassword) {
            return $msg;
        } else {
            $password=md5($_POST['password']);
        }
    } else {
      $password = "";
      $repeatPassword = "";
    }
    $cnic = strip_tags($_POST['cnic']);
    $phone = strip_tags($_POST['phone']);
    if ($firstName  === '' || $lastName === '' || $cnic === '' || $phone === ''  || !is_numeric($cnic) ||
    !is_numeric($phone) || strlen($cnic) !== 13 || strlen($phone) !== 11) {
        return $msg;
    } else {
        $whereClause = " WHERE id=".$_SESSION['user']['id'];
        if(!empty($_POST['password'])) {
            $columnData=array("firstname"=>$firstName,"lastname"=>$lastName,"password"=>$password,"cnic"=>$cnic,"phone"=>$phone);
        } else {
            $columnData=array("firstname"=>$firstName,"lastname"=>$lastName,"cnic"=>$cnic,"phone"=>$phone);
        }
        $result = $obj->update("accounts",$columnData,$whereClause);
        // for update session variable
        $result2 = $obj->select("*", "accounts", $whereClause);
        if($result) {
            $row2 = mysqli_fetch_assoc($result2);
            if(!empty($_POST['password'])) {
                unset($row2["password"]);
            }
            $_SESSION['user']=$row2;
            return "Account Successfully Updated";
        } else {
            return $msg;
        }
    }
}

function showAll($obj, $select, $tableName, $whereClause){
    return $obj->select($select, $tableName, $whereClause);
}

?>