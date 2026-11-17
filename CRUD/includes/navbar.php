<?php
$user = current_user();
$current_theme = get_active_theme();
$active_page = basename($_SERVER['PHP_SELF']);

$theme_names = [
    'theme-1' => '🌿 Modern Green',
    'theme-2' => '🏛️ Heritage Gold',
    'theme-dark' => '🌙 Mode Gelap'
];
$active_theme_label = $theme_names[$current_theme] ?? '🌿 Modern Green';
?>
<nav class="navbar navbar-expand-lg simrs-navbar mb-4">
    <div class="container">
        <a class="simrs-brand" href="index.php">
            <div class="simrs-brand-icon">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <div class="simrs-brand-title">
                <span class="main-title">SIM RS Elektromedis</span>
                <span class="sub-title">Sistem Data Alat Rumah Sakit</span>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#simrsNavCollapse" aria-label="Toggle navigation">
            <i class="bi bi-list fs-2" style="color: var(--theme-text);"></i>
        </button>

        <div class="collapse navbar-collapse" id="simrsNavCollapse">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold <?php echo ($active_page == 'index.php') ? 'text-primary' : ''; ?>" href="index.php" style="color: var(--theme-text);">
                        <i class="bi bi-grid-fill me-1"></i> Data Alat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold <?php echo ($active_page == 'add.php') ? 'text-primary' : ''; ?>" href="add.php" style="color: var(--theme-text);">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Alat
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center flex-wrap gap-2 mt-3 mt-lg-0">
                <!-- Theme Switcher Dropdown -->
                <div class="dropdown theme-selector-dropdown">
                    <button class="theme-pill-btn dropdown-toggle" type="button" id="themeDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-palette-fill text-warning"></i>
                        <span id="currentThemeLabel"><?php echo htmlspecialchars($active_theme_label); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" aria-labelledby="themeDropdownBtn" style="border-radius: var(--theme-radius-sm); background: var(--theme-surface);">
                        <li>
                            <button class="dropdown-item py-2 rounded-2 d-flex align-items-center justify-content-between <?php echo ($current_theme == 'theme-1') ? 'active fw-bold' : ''; ?>" type="button" onclick="switchTheme('theme-1')">
                                <span>🌿 Theme 1 (Hopewell Modern)</span>
                                <?php if($current_theme == 'theme-1'): ?><i class="bi bi-check2"></i><?php endif; ?>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item py-2 rounded-2 d-flex align-items-center justify-content-between <?php echo ($current_theme == 'theme-2') ? 'active fw-bold' : ''; ?>" type="button" onclick="switchTheme('theme-2')">
                                <span>🏛️ Theme 2 (Hopewell Heritage)</span>
                                <?php if($current_theme == 'theme-2'): ?><i class="bi bi-check2"></i><?php endif; ?>
                            </button>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <button class="dropdown-item py-2 rounded-2 d-flex align-items-center justify-content-between <?php echo ($current_theme == 'theme-dark') ? 'active fw-bold' : ''; ?>" type="button" onclick="switchTheme('theme-dark')">
                                <span>🌙 Mode Gelap (Dark Mode)</span>
                                <?php if($current_theme == 'theme-dark'): ?><i class="bi bi-check2"></i><?php endif; ?>
                            </button>
                        </li>
                    </ul>
                </div>

                <?php if ($user): ?>
                    <!-- User Profile & Logout -->
                    <div class="user-pill ms-lg-2">
                        <div class="user-avatar-circle">
                            <?php echo strtoupper(substr($user['nama'], 0, 1)); ?>
                        </div>
                        <div class="d-none d-sm-block text-start">
                            <div class="fw-bold" style="font-size: 0.85rem; line-height: 1.1; color: var(--theme-text);">
                                <?php echo htmlspecialchars($user['nama']); ?>
                            </div>
                            <small class="text-muted" style="font-size: 0.72rem;">@<?php echo htmlspecialchars($user['username']); ?></small>
                        </div>
                        <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2 rounded-pill px-2 py-1" title="Keluar / Logout" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn-simrs-primary py-2 px-3">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
