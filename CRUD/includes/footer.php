<footer class="simrs-footer mt-auto">
    <div class="container text-center">
        <div class="row align-items-center gy-2">
            <div class="col-md-6 text-md-start">
                <span class="fw-semibold">SIM RS Elektromedis</span> &copy; <?php echo date('Y'); ?> &mdash; Sistem Pengelolaan Data Alat Rumah Sakit
            </div>
            <div class="col-md-6 text-md-end">
                <span class="badge bg-light text-secondary border">Bootstrap 5</span>
                <span class="badge bg-light text-secondary border">PHP & MySQL</span>
                <span class="badge bg-light text-success border">Hopewell Themes</span>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 Bundle JS -->
<script src="assets/bootstrap.bundle.min.js"></script>

<!-- Theme Switching Script -->
<script>
function switchTheme(themeName) {
    // 1. Update body class
    document.body.classList.remove('theme-1', 'theme-2', 'theme-dark');
    document.body.classList.add(themeName);

    // 2. Save cookie for PHP backend
    document.cookie = "app_theme=" + encodeURIComponent(themeName) + "; path=/; max-age=" + (86400 * 30);
    localStorage.setItem("app_theme", themeName);

    // 3. Update active theme label in UI if present
    const labels = {
        'theme-1': '🌿 Modern Green',
        'theme-2': '🏛️ Heritage Gold',
        'theme-dark': '🌙 Mode Gelap'
    };
    const labelElem = document.getElementById('currentThemeLabel');
    if (labelElem && labels[themeName]) {
        labelElem.innerText = labels[themeName];
    }

    // 4. Send background sync to update PHP session
    fetch('set_theme.php?theme=' + encodeURIComponent(themeName))
        .catch(() => {});
}

// Auto-dismiss bootstrap alerts after 4 seconds
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 4000);
    });
});
</script>
</body>
</html>
