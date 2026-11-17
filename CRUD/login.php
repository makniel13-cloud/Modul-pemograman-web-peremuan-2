<?php
require_once("config.php");

// If already logged in, redirect to index
if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'unauthorized') {
        $error = 'Silakan login terlebih dahulu untuk mengakses sistem.';
    } elseif ($_GET['msg'] === 'logout') {
        $success = 'Anda telah berhasil keluar dari sistem.';
    } elseif ($_GET['msg'] === 'registered') {
        $success = 'Registrasi berhasil! Silakan masuk menggunakan akun baru Anda.';
    }
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Mohon isi username dan password.';
    } else {
        $stmt = mysqli_prepare($mysqli, "SELECT id, username, nama, password, role FROM users WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];

                header("Location: index.php?msg=welcome");
                exit();
            } else {
                $error = 'Password yang Anda masukkan salah.';
            }
        } else {
            $error = 'Username tidak ditemukan.';
        }
        mysqli_stmt_close($stmt);
    }
}

$page_title = 'Login - SIM RS Data Alat Elektromedis';
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
                <i class="bi bi-hospital"></i>
            </div>
            <h3 class="mb-1">Masuk ke SIMRS</h3>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Sistem Informasi Manajemen Data Alat Elektromedis</p>
        </div>

        <div class="auth-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div><?php echo htmlspecialchars($error); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                    <div><?php echo htmlspecialchars($success); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" autocomplete="off">
                <div class="mb-3">
                    <label for="username" class="form-label-custom">
                        <i class="bi bi-person-fill text-muted"></i> Username
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control form-control-custom" id="username" name="username" placeholder="Masukkan username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label-custom">
                        <i class="bi bi-shield-lock-fill text-muted"></i> Password
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control form-control-custom" id="password" name="password" placeholder="Masukkan password" required>
                        <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePasswordBtn" style="border-color: var(--theme-border); background: var(--theme-surface-subtle);" onclick="togglePasswordVisibility()">
                            <i class="bi bi-eye-fill" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-simrs-primary w-100 py-2 mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                </button>
            </form>

            <div class="p-3 rounded-3" style="background: var(--theme-surface-subtle); border: 1px dashed var(--theme-border); font-size: 0.85rem;">
                <div class="fw-bold mb-1 text-primary"><i class="bi bi-info-circle-fill me-1"></i> Akun Pengujian (Default):</div>
                <div class="d-flex justify-content-between text-muted">
                    <span>Username: <code>admin</code></span>
                    <span>Password: <code>admin123</code></span>
                </div>
            </div>
        </div>

        <div class="auth-footer">
            Belum memiliki akun? <a href="register.php" class="fw-semibold">Daftar Akun Baru <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
    }
}
</script>

<?php include("includes/footer.php"); ?>
