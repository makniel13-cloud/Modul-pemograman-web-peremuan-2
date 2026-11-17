<?php
require_once("auth_check.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
if ($id <= 0) {
    header("Location: index.php");
    exit();
}

$error = '';

// Check if form is submitted for update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $nama_alat = trim($_POST['nama_alat'] ?? '');
    $tahun     = trim($_POST['tahun'] ?? '');
    $merek     = trim($_POST['merek'] ?? '');
    $lokasi    = trim($_POST['lokasi'] ?? '');
    $no_seri   = trim($_POST['no_seri'] ?? '');

    if (empty($nama_alat) || empty($tahun) || empty($merek) || empty($lokasi) || empty($no_seri)) {
        $error = 'Semua field formulir wajib diisi dengan lengkap.';
    } else {
        $update_stmt = mysqli_prepare($mysqli, "UPDATE alat SET nama_alat = ?, tahun = ?, merek = ?, lokasi = ?, no_seri = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_stmt, "sssssi", $nama_alat, $tahun, $merek, $lokasi, $no_seri, $id);

        if (mysqli_stmt_execute($update_stmt)) {
            mysqli_stmt_close($update_stmt);
            header("Location: index.php?msg=updated");
            exit();
        } else {
            $error = 'Gagal memperbarui data: ' . mysqli_error($mysqli);
        }
        mysqli_stmt_close($update_stmt);
    }
}

// Fetch existing alat data
$fetch_stmt = mysqli_prepare($mysqli, "SELECT * FROM alat WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($fetch_stmt, "i", $id);
mysqli_stmt_execute($fetch_stmt);
$result = mysqli_stmt_get_result($fetch_stmt);
$user_data = mysqli_fetch_assoc($result);
mysqli_stmt_close($fetch_stmt);

if (!$user_data) {
    header("Location: index.php");
    exit();
}

$nama_alat = $user_data['nama_alat'];
$tahun     = $user_data['tahun'];
$merek     = $user_data['merek'];
$lokasi    = $user_data['lokasi'];
$no_seri   = $user_data['no_seri'];

$page_title = 'Edit Data Alat Elektromedis - SIM RS';
include("includes/header.php");
include("includes/navbar.php");
?>

<div class="container pb-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door-fill"></i> Data Alat</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Alat #<?php echo $id; ?></li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="simrs-card">
                <div class="simrs-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="simrs-brand-icon" style="width: 44px; height: 44px; font-size: 1.3rem;">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Edit Data Alat Elektromedis</h4>
                            <small class="text-muted">Perbarui rincian spesifikasi atau lokasi alat medis</small>
                        </div>
                    </div>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--theme-radius-sm);">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="simrs-card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                            <div><?php echo htmlspecialchars($error); ?></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="edit.php" method="POST" autocomplete="off">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">

                        <div class="row g-3">
                            <!-- Nama Alat -->
                            <div class="col-12">
                                <label for="nama_alat" class="form-label-custom">
                                    <i class="bi bi-activity text-primary"></i> Nama Alat Elektromedis <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-cpu"></i></span>
                                    <input type="text" class="form-control form-control-custom" id="nama_alat" name="nama_alat" value="<?php echo htmlspecialchars($nama_alat); ?>" required>
                                </div>
                            </div>

                            <!-- Merek & Tahun -->
                            <div class="col-md-6">
                                <label for="merek" class="form-label-custom">
                                    <i class="bi bi-tags text-primary"></i> Merek / Pabrikan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" class="form-control form-control-custom" id="merek" name="merek" value="<?php echo htmlspecialchars($merek); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="tahun" class="form-label-custom">
                                    <i class="bi bi-calendar3 text-primary"></i> Tahun Pengadaan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                    <input type="number" min="1990" max="<?php echo date('Y') + 1; ?>" class="form-control form-control-custom" id="tahun" name="tahun" value="<?php echo htmlspecialchars($tahun); ?>" required>
                                </div>
                            </div>

                            <!-- Lokasi & No Seri -->
                            <div class="col-md-6">
                                <label for="lokasi" class="form-label-custom">
                                    <i class="bi bi-geo-alt text-primary"></i> Lokasi / Ruangan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control form-control-custom" id="lokasi" name="lokasi" value="<?php echo htmlspecialchars($lokasi); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="no_seri" class="form-label-custom">
                                    <i class="bi bi-upc-scan text-primary"></i> Nomor Seri Alat <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                                    <input type="text" class="form-control form-control-custom font-monospace" id="no_seri" name="no_seri" value="<?php echo htmlspecialchars($no_seri); ?>" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: var(--theme-border);">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-outline-secondary px-4 py-2" style="border-radius: var(--theme-radius-sm);">
                                Batal
                            </a>
                            <button type="submit" name="update" class="btn-simrs-primary px-4 py-2">
                                <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
