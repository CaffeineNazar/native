<?php
require_once __DIR__ . '/includes/auth.php'; // cek sesi login
require_once __DIR__ . '/config/db.php'; 

// Asumsi peran pengguna (user role) dan id_user disimpan dalam sesi setelah login.
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$user_id   = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Variabel untuk menyimpan status HRT
$is_hrt = 0; // Default: bukan HRT
$is_hrt_kelas_xii = false; // Default: HRT bukan kelas XII

// Variabel untuk menyimpan info kelas siswa
$nama_kelas = '';
$is_kelas_xii = false;

// Jika pengguna adalah guru, cek status HRT dan kelas yang diajar di database
if ($user_role === 'guru' && $user_id !== null) {
    // Pertama, ambil is_hrt dan id_guru
    $sql_hrt = "SELECT is_hrt, id_guru, id_kelas FROM guru WHERE id_user = ?";
    $stmt_hrt = $koneksi->prepare($sql_hrt);
    $stmt_hrt->bind_param("i", $user_id);
    $stmt_hrt->execute();
    $result_hrt = $stmt_hrt->get_result();
    
    if ($result_hrt->num_rows > 0) {
        $row_hrt = $result_hrt->fetch_assoc();
        $is_hrt  = (int)$row_hrt['is_hrt'];
        $id_kelas_guru = $row_hrt['id_kelas'];
        
        // Jika guru adalah HRT dan memiliki id_kelas, cek nama kelas
        if ($is_hrt == 1 && !empty($id_kelas_guru)) {
            $sql_kelas_guru = "SELECT nama_kelas FROM kelas WHERE id_kelas = ?";
            $stmt_kelas_guru = $koneksi->prepare($sql_kelas_guru);
            $stmt_kelas_guru->bind_param("i", $id_kelas_guru);
            $stmt_kelas_guru->execute();
            $result_kelas_guru = $stmt_kelas_guru->get_result();
            
            if ($result_kelas_guru->num_rows > 0) {
                $row_kelas_guru = $result_kelas_guru->fetch_assoc();
                $nama_kelas_guru = $row_kelas_guru['nama_kelas'];
                // Cek apakah guru HRT mengajar kelas XII
                $is_hrt_kelas_xii = (strpos($nama_kelas_guru, 'XII') === 0);
            }
            $stmt_kelas_guru->close();
        }
    }
    $stmt_hrt->close();
}

// Jika pengguna adalah siswa, cek nama kelas
if ($user_role === 'siswa' && $user_id !== null) {
    $sql_kelas = "SELECT k.nama_kelas 
                  FROM siswa s 
                  JOIN kelas k ON s.id_kelas = k.id_kelas 
                  WHERE s.id_user = ?";
    $stmt_kelas = $koneksi->prepare($sql_kelas);
    $stmt_kelas->bind_param("i", $user_id);
    $stmt_kelas->execute();
    $result_kelas = $stmt_kelas->get_result();
    
    if ($result_kelas->num_rows > 0) {
        $row_kelas = $result_kelas->fetch_assoc();
        $nama_kelas = $row_kelas['nama_kelas'];
        // Cek apakah nama kelas dimulai dengan "XII"
        $is_kelas_xii = (strpos($nama_kelas, 'XII') === 0);
    }
    $stmt_kelas->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/head.php'; ?>
    <!-- Google Font modern untuk heading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;800&display=swap" rel="stylesheet">
    <link href="assets/css/menu.css" rel="stylesheet">
</head>

<body>

    <div class="container py-5">
        <div class="main-card card border-0 shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="logo-wrapper">
                        <img src="assets/img/logo.png" alt="Logo GIBS">
                    </div>
                    <h1 class="main-title">GIBS Management System</h1>
                    <p class="subtitle">Selamat Datang di Portal Manajemen GIBS</p>
                    <div class="divider-line"></div>
                </div>
                
                <!-- Grid: jarak antar kotak lebih besar -->
                <div class="row g-5 mt-4 mb-2">
                    <!-- E-Rapor (aktif untuk admin & guru, coming soon untuk siswa) -->
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-5">
                        <?php if ($user_role === 'siswa') { ?>
                            <a href="#" class="menu-card menu-card-blue disabled">
                                <span class="coming-soon-badge">Coming Soon</span>
                                <div class="icon-wrapper">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="menu-title">E-Rapor</div>
                                <div class="menu-description">Sistem Pelaporan Akademik Digital</div>
                            </a>
                        <?php } else { ?>
                            <a href="home.php" class="menu-card menu-card-blue">
                                <div class="icon-wrapper">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="menu-title">E-Rapor</div>
                                <div class="menu-description">Sistem Pelaporan Akademik Digital</div>
                            </a>
                        <?php } ?>
                    </div>
                    
                    <!-- TKA (untuk siswa kelas XII dan guru HRT kelas XII) -->
                    <?php if (($user_role === 'siswa' && $is_kelas_xii) || ($user_role === 'guru' && $is_hrt_kelas_xii)) { ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-5">
                        <a href="<?php echo ($user_role === 'siswa') ? 'pages/siswa/dashboard-tka.php' : 'pages/guru/dashboard-tka.php'; ?>" class="menu-card menu-card-purple">
                            <div class="icon-wrapper">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="menu-title">TKA</div>
                            <div class="menu-description">Tes Kemampuan Akademik</div>
                        </a>
                    </div>
                    <?php } ?>
                    
                    <!-- Vehicle Request (Coming Soon) -->
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-5">
                        <a href="#" class="menu-card menu-card-gold disabled">
                            <span class="coming-soon-badge">Coming Soon</span>
                            <div class="icon-wrapper">
                                <i class="fas fa-car-side"></i>
                            </div>
                            <div class="menu-title">Vehicle Request</div>
                            <div class="menu-description">Permintaan Kendaraan Sekolah</div>
                        </a>
                    </div>
                    
                    <!-- Checkout Permission (Coming Soon, sesuai role) -->
                    <?php 
                    if ($user_role === 'admin' || $user_role === 'siswa' || $is_hrt == 1) {
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-5">
                        <a href="#" class="menu-card menu-card-teal disabled">
                            <span class="coming-soon-badge">Coming Soon</span>
                            <div class="icon-wrapper">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div class="menu-title">Checkout Permission</div>
                            <div class="menu-description">Izin Keluar Area Sekolah</div>
                        </a>
                    </div>
                    <?php } ?>
                    
                    <?php 
                    // Menu tambahan khusus guru: semua masih Coming Soon
                    if ($user_role === 'guru') {
                    ?>
                    <!-- Presensi -->
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-5">
                        <a href="#" class="menu-card menu-card-green disabled">
                            <span class="coming-soon-badge">Coming Soon</span>
                            <div class="icon-wrapper">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="menu-title">Presensi</div>
                            <div class="menu-description">Kelola Kehadiran Siswa</div>
                        </a>
                    </div>
                    
                    <!-- Jadwal Mengajar -->
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-5">
                        <a href="#" class="menu-card menu-card-purple disabled">
                            <span class="coming-soon-badge">Coming Soon</span>
                            <div class="icon-wrapper">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="menu-title">Jadwal Mengajar</div>
                            <div class="menu-description">Lihat & Kelola Jadwal Mengajar</div>
                        </a>
                    </div>
                    
                    <!-- Administrasi -->
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-5">
                        <a href="#" class="menu-card menu-card-orange disabled">
                            <span class="coming-soon-badge">Coming Soon</span>
                            <div class="icon-wrapper">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <div class="menu-title">Administrasi</div>
                            <div class="menu-description">Dokumen & Administrasi Guru</div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>
</body>

</html>