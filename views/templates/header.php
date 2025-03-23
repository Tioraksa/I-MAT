<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $title; ?></title>



    <!-- Custom fonts for this template-->
    <link href=" <?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="<?= base_url('assets/'); ?>https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href=" <?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<style>
.custom-btn {
        text-decoration: none;
        display: block;
        padding: 0;
    }

    .custom-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 200%;
        height: 100px;
        border-radius: 12px;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        font-size: 1.3rem;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease-in-out;
    }

    .custom-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #0056b3, #003580);
    }

body {
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: Arial, sans-serif;
}

.container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    padding: 20px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid black;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .popup.active {
            display: block;
        }

        .modal-content {
            border-radius: 10px;
            padding: 20px;
        }

.footer {
    background: #f8f9fa;
    text-align: center;
    padding: 10px;
    width: 100%;
    position: fixed;
    bottom: 0;
}




</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">