<?php
require_once __DIR__ . '/includes/auth.php'; // cek sesi login
require_once __DIR__ . '/config/db.php'; 

if (isset($_GET['page']) && $_GET['page'] === 'hapus-paket-pembelajaran') {
    $message = '';
    $message_type = '';
    
    // Sertakan skrip penghapusan yang hanya berisi logika database
    require_once __DIR__ . '/pages/hapus-paket-pembelajaran.php';

    // Setelah proses penghapusan selesai, lakukan pengalihan
    // Anda bisa menempatkan kode pengalihan di sini dengan aman
    header("Location: home.php?page=paket-pembelajaran&message=" . urlencode($message) . "&message_type=" . urlencode($message_type));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>E-Rapor - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        switch($_SESSION['role']){
    case 'admin':
        include "includes/sidebar-admin.php";
        break;
    case 'guru':
        if (isset($_GET['page']) && strpos($_GET['page'], 'hode/') === 0) {
            include "includes/sidebar-hode.php";
        } else {
            include "includes/sidebar-guru.php";
        }
        break;
    case 'siswa':
        include "includes/sidebar-siswa.php";
        break;
    case 'wali_kelas':
        include "includes/sidebar-wali.php";
        break;
    case 'kepsek':
        include "includes/sidebar-kepsek.php";
        break;
    case 'ortu':
        include "includes/sidebar-ortu.php";
        break;
    default:
        echo "includes/Role tidak dikenal.";
    }
    ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'includes/topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <?php
// cek apakah ada parameter page di URL
$page = isset($_GET['page']) ? $_GET['page'] : null;

if ($page) {
    // file halaman ada di folder pages/
    $file = "pages/" . $page . ".php";

    if (file_exists($file)) {
        include $file;
    } else {
        echo "<div class='container-fluid'><h3>Halaman tidak ditemukan!</h3></div>";
    }
    } else {
    // kalau tidak ada ?page= tampilkan dashboard sesuai role
    switch ($_SESSION['role']) {
        case 'admin':
            include "pages/dashboard-admin.php";
            break;
        case 'guru':
            include "pages/guru/dashboard-guru.php";
            break;
        case 'siswa':
            include "pages/dashboard-siswa.php";
            break;
        case 'wali_kelas':
            include "pages/dashboard-wali.php";
            break;
        case 'kepsek':
            include "pages/dashboard-kepsek.php";
            break;
        case 'ortu':
            include "pages/dashboard-ortu.php";
            break;
        default:
            echo "<div class='container-fluid'><h3>Role tidak dikenal.</h3></div>";
        }
    }
    ?>

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include 'includes/footer.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include 'includes/logout_modal.php'; ?>

    <!-- Scripts -->
    <?php include 'includes/scripts.php'; ?>

</body>
</html>
