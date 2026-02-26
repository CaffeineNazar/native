<?php
session_start();
require_once __DIR__ . '/config/db.php';

$error = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    $stmt = $koneksi->prepare("SELECT id_user, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            header("Location: menu.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Rapor GIBS</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Elements */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(251, 191, 36, 0.1);
            border-radius: 50%;
            top: -250px;
            right: -250px;
            animation: float 20s infinite ease-in-out;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(251, 191, 36, 0.08);
            border-radius: 50%;
            bottom: -200px;
            left: -200px;
            animation: float 15s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-50px) scale(1.1);
            }
        }

        .login-container {
            position: relative;
            z-index: 10;
            max-width: 450px;
            width: 100%;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
            padding: 50px 40px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo Section */
.logo-section {
    text-align: center;
    margin-bottom: 35px;
}

.logo-wrapper {
    display: inline-block;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.logo-wrapper:hover {
    transform: scale(1.05);
}

.logo-wrapper img {
    width: 40%;
    height: 40%;
    object-fit: contain;
    filter: drop-shadow(0 4px 12px rgba(30, 64, 175, 0.2));
}

.logo-section h1 {
    font-size: 26px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.logo-section p {
    font-size: 14px;
    color: #64748b;
    font-weight: 400;
}


        /* Alert */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.4s ease-in-out;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-left: 4px solid #f59e0b;
            color: #92400e;
            font-size: 14px;
            font-weight: 500;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .alert i {
            font-size: 18px;
            color: #f59e0b;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 500;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px 14px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-control:focus + .input-icon {
            color: #3b82f6;
        }

        /* Remember Me Checkbox */
        .remember-section {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #3b82f6;
        }

        .checkbox-wrapper label {
            color: #64748b;
            font-size: 14px;
            cursor: pointer;
            margin: 0;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(59, 130, 246, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Footer Links */
        .form-footer {
            margin-top: 25px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .form-footer a {
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .form-footer a:hover {
            color: #1e40af;
            text-decoration: underline;
        }

        /* Accent Line */
        .accent-line {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
            margin: 0 auto 20px;
            border-radius: 2px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 35px 25px;
            }

            .logo-section h1 {
                font-size: 22px;
            }

            .logo-wrapper img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <!-- Logo Section -->
<div class="logo-section">
    <div class="logo-wrapper">
        <img src="assets/img/logo_gibs.png" alt="Logo GIBS">
    </div>
    <div class="accent-line"></div>
    <h1>Portal GIBS</h1>
    <p>Sistem Informasi Global Islamic Boarding School</p>
</div>


            <!-- Error Alert -->
            <?php if ($error): ?>
                <div class="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form id="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            class="form-control" 
                            id="username" 
                            name="username" 
                            placeholder="Masukkan username Anda" 
                            required
                            value="<?= htmlspecialchars($username ?? '') ?>"
                            autocomplete="username">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password Anda" 
                            required
                            autocomplete="current-password">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <div class="remember-section">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember_me" name="remember_me">
                        <label for="remember_me">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                    Masuk
                </button>
            </form>

            <!-- Footer -->
            <div class="form-footer">
                <a href="#"><i class="fas fa-key" style="margin-right: 6px;"></i>Lupa Password?</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const rememberMeCheckbox = document.getElementById('remember_me');
            const loginForm = document.getElementById('login-form');

            // Load saved username from localStorage
            if (localStorage.getItem('username')) {
                usernameInput.value = localStorage.getItem('username');
                rememberMeCheckbox.checked = true;
            }

            // Save/remove credentials on form submit
            loginForm.addEventListener('submit', () => {
                if (rememberMeCheckbox.checked) {
                    localStorage.setItem('username', usernameInput.value);
                } else {
                    localStorage.removeItem('username');
                }
            });

            // Add focus effect to inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.01)';
                });
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>
