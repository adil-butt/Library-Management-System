<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("Location:../auth/login.php");
    exit();
} else {
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    if ($_SESSION['user']['role'] === "2" && strpos($url, 'lms/admin') !== false) {
        header("Location:../student/dashboard.php");
        exit;
    } elseif ($_SESSION['user']['role'] === "1" && strpos($url, 'lms/student') !== false) {
        header("Location:../admin/dashboard.php");
        exit;
    }
}
// output: localhost
$hostName = $_SERVER['HTTP_HOST'];

// output: http://
$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $title; ?></title>

  <!-- Custom fonts for this template-->
  <link href="<?php echo $protocol . $hostName ?>/lms/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo $protocol . $hostName ?>/lms/assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="<?php echo $protocol . $hostName ?>/lms/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<body id="page-top">
 <!-- Page Wrapper -->
  <div id="wrapper">
