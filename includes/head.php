<?php
// include/head.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-Rapor - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.css" rel="stylesheet">

    <style>
        /* Custom style untuk card dan tombol */
        body.bg-gradient-primary {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
        }
        .card {
            border-radius: 20px;
            overflow: hidden;
        }
        .card .card-body {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
        }
        .btn-user {
            font-size: 1.2rem;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            transition: box-shadow 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px rgba(38, 50, 56, 0.08);
        }
        .btn-user:hover:not(.disabled) {
            box-shadow: 0 4px 16px rgba(38, 50, 56, 0.16);
            transform: translateY(-2px) scale(1.03);
        }
        .coming-soon {
            opacity: 0.7;
        }
        .logo-circle {
            width: 15%;
            height: auto;
            aspect-ratio: 1 / 1;
            background: linear-gradient(135deg, #77B5FE 0%, #1E90FF 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transform: perspective  (200px) rotateX(5deg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            box-shadow: 0 2px 8px rgba(38, 50, 56, 0.12);
        }
        .logo-circle i {
            color: #fff;
            font-size: 2.5rem;
        }
    </style>
</head>
<body id="page-top">
