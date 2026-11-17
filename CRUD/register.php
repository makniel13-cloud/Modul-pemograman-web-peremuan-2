<?php
require_once("config.php");

// If already logged in, redirect to index
if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

$error = '';
$nama = '';
$username = '';

// Process register form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($nama) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Semua field wajib diisi.';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal terdiri dari 3 karakter.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = 'Username hanya boleh berisi huruf, angka, dan underscore (_).';
    } elseif (strlen($password) < 5) {
        $error = 'Password minimal terdiri dari 5 karakter.';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok dengan password.';
    } else {
        // Check if username already exists
        $checkStmt = mysqli_prepare($mysqli, "SELECT id FROM users WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($checkStmt, "s", $username);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $error = 'Username sudah terdaftar. Silakan pilih username lain.';
        } else {
            // Hash password and insert
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = mysqli_prepare($mysqli, "INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, 'user')");
            mysqli_stmt_bind_param($insertStmt, "sss", $nama, $username, $hashedPassword);

            if (mysqli_stmt_execute($insertStmt)) {
                mysqli_stmt_close($insertStmt);
                mysqli_stmt_close($checkStmt);
                header("Location: login.php?msg=registered");
                exit();
            } else {
                $error = 'Terjadi kesalahan sistem saat mendaftarkan akun. Silakan coba lagi.';
            }
            mysqli_stmt_close($insertStmt);
        }
        mysqli_stmt_close($checkStmt);
    }
}

$page_title = 'Registrasi Akun Baru - SIM RS';
include("includes/header.php");
?>

<div class="auth-wrapper">
    <!-- Quick Theme Switcher on Auth Screen -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="dropdown theme-selector-dropdown">
            <button class="theme-pill-btn dropdown-toggle" type="button" id="authThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-palette-fill text-warning"></i>
                <span id="currentThemeLabel"><?php echo htmlspecialchars($theme_names[$current_theme] ?? 'Pilih Tema'); ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" aria-labelledby="authThemeDropdown" style="background: var(--theme-surface);">
                <li><button class="dropdown-item py-2 rounded-2" type="button" onclick="switchTheme('theme-1')">🌿 Theme 1 (Hopewell Modern)</button></li>
                <li><button class="dropdown-item py-2 rounded-2" type="button" onclick="switchTheme('theme-2')">🏛️ Theme 2 (Hopewell Heritage)</button></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li><button class="dropdown-item py-2 rounded-2" type="button" onclick="switchTheme('theme-dark')">🌙 Mode Gelap (Dark Mode)</button></li>
            </ul>
        </div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <div class="simrs-brand-icon mx-auto mb-3" style="width: 58px; height: 58px; font-size: 1.8rem;">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h3 class="mb-1">Daftar Akun Baru</h3>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Buat akun untuk mengelola Data Alat Elektromedis</p>
        </div>

        <div class="auth-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div><?php echo htmlspecialchars($error); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" autocomplete="off">
                <div class="mb-3">
                    <label for="nama" class="form-label-custom">
                        <i class="bi bi-card-heading text-muted"></i> Nama Lengkap
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                        <input type="text" class="form-control form-control-custom" id="nama" name="nama" placeholder="Contoh: Dr. Budi Santoso" required value="<?php echo htmlspecialchars($nama); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label-custom">
                        <i class="bi bi-person-fill text-muted"></i> Username
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="text" class="form-control form-control-custom" id="username" name="username" placeholder="Contoh: budi_santoso" required value="<?php echo htmlspecialchars($username); ?>">
                    </div>
                    <small class="text-muted" style="font-size: 0.78rem;">Hanya huruf, angka, dan garis bawah (_).</small>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label-custom">
                        <i class="bi bi-shield-lock-fill text-muted"></i> Password
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control form-control-custom" id="password" name="password" placeholder="Minimal 5 karakter" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="form-label-custom">
                        <i class="bi bi-check2-circle text-muted"></i> Konfirmasi Password
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control form-control-custom" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" name="register" class="btn-simrs-primary w-100 py-2 mb-2">
                    <i class="bi bi-person-check-fill"></i> Selesaikan Pendaftaran
                </button>
            </form>
        </div>

        <div class="auth-footer">
            Sudah memiliki akun? <a href="login.php" class="fw-semibold">Masuk disini <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
