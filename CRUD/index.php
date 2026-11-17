<?php
require_once("auth_check.php");

$user = current_user();

// Handle search and filtering
$search = trim($_GET['search'] ?? '');
$filter_lokasi = trim($_GET['filter_lokasi'] ?? '');
$filter_merek = trim($_GET['filter_merek'] ?? '');

$where_clauses = [];
$params = [];
$types = "";

if (!empty($search)) {
    $where_clauses[] = "(nama_alat LIKE ? OR merek LIKE ? OR no_seri LIKE ? OR lokasi LIKE ?)";
    $search_param = "%" . $search . "%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ssss";
}

if (!empty($filter_lokasi)) {
    $where_clauses[] = "lokasi = ?";
    $params[] = $filter_lokasi;
    $types .= "s";
}

if (!empty($filter_merek)) {
    $where_clauses[] = "merek = ?";
    $params[] = $filter_merek;
    $types .= "s";
}

$query = "SELECT * FROM alat";
if (count($where_clauses) > 0) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}
$query .= " ORDER BY id DESC";

if (count($params) > 0) {
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($mysqli, $query);
}

// Stats queries
$total_alat_res = mysqli_query($mysqli, "SELECT COUNT(*) FROM alat");
$total_alat = $total_alat_res ? mysqli_fetch_row($total_alat_res)[0] : 0;

$total_merek_res = mysqli_query($mysqli, "SELECT COUNT(DISTINCT merek) FROM alat");
$total_merek = $total_merek_res ? mysqli_fetch_row($total_merek_res)[0] : 0;

$total_lokasi_res = mysqli_query($mysqli, "SELECT COUNT(DISTINCT lokasi) FROM alat");
$total_lokasi = $total_lokasi_res ? mysqli_fetch_row($total_lokasi_res)[0] : 0;

$tahun_terbaru_res = mysqli_query($mysqli, "SELECT MAX(tahun) FROM alat");
$tahun_terbaru = ($tahun_terbaru_res && $row = mysqli_fetch_row($tahun_terbaru_res)) ? ($row[0] ?: '-') : '-';

// Distinct locations and brands for filter dropdowns
$lokasi_options = mysqli_query($mysqli, "SELECT DISTINCT lokasi FROM alat WHERE lokasi != '' ORDER BY lokasi ASC");
$merek_options = mysqli_query($mysqli, "SELECT DISTINCT merek FROM alat WHERE lokasi != '' ORDER BY merek ASC");

$page_title = 'Dashboard SIM RS - Data Alat Elektromedis';
include("includes/header.php");
include("includes/navbar.php");
?>

<div class="container pb-5">
    <!-- Success & Info Alerts -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'welcome'): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-emoji-smile fs-4 me-3"></i>
                <div>
                    <strong>Selamat Datang, <?php echo htmlspecialchars($user['nama']); ?>!</strong> Anda berhasil masuk ke Sistem Informasi Manajemen Data Alat Elektromedis.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['msg'] === 'added'): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div><strong>Berhasil!</strong> Data alat baru berhasil disimpan ke database.</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['msg'] === 'updated'): ?>
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div><strong>Pembaruan Berhasil!</strong> Perubahan data alat telah disimpan.</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-trash-fill fs-4 me-3"></i>
                <div><strong>Data Dihapus!</strong> Data alat terpilih berhasil dihapus dari sistem.</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="badge rounded-pill px-3 py-2 mb-3" style="background: var(--theme-primary-light); color: var(--theme-primary); font-weight: 700;">
                    <i class="bi bi-shield-check me-1"></i> SIMRS Elektromedis v2.0
                </div>
                <h2 class="mb-2">Halo, <?php echo htmlspecialchars($user['nama']); ?></h2>
                <p class="mb-0 text-muted" style="max-width: 620px;">
                    Kelola dan pantau seluruh data inventaris peralatan elektromedis rumah sakit secara akurat, terstruktur, dan real-time.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="add.php" class="btn-simrs-primary shadow">
                    <i class="bi bi-plus-lg fs-5"></i> Tambah Alat Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-cpu-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($total_alat); ?></div>
                    <div class="stat-label">Total Alat</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($total_merek); ?></div>
                    <div class="stat-label">Merek Terdaftar</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon amber">
                    <i class="bi bi-building-fill-check"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($total_lokasi); ?></div>
                    <div class="stat-label">Ruangan / Poli</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo htmlspecialchars($tahun_terbaru); ?></div>
                    <div class="stat-label">Tahun Terbaru</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="simrs-card mb-4">
        <div class="simrs-card-body p-3">
            <form action="index.php" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-custom">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari nama alat, merek, no seri, atau lokasi..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <select name="filter_lokasi" class="form-select form-select-custom">
                        <option value="">Semua Lokasi / Poli</option>
                        <?php if ($lokasi_options): ?>
                            <?php while ($loc = mysqli_fetch_assoc($lokasi_options)): ?>
                                <option value="<?php echo htmlspecialchars($loc['lokasi']); ?>" <?php echo ($filter_lokasi === $loc['lokasi']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($loc['lokasi']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-md-2">
                    <select name="filter_merek" class="form-select form-select-custom">
                        <option value="">Semua Merek</option>
                        <?php if ($merek_options): ?>
                            <?php while ($mrk = mysqli_fetch_assoc($merek_options)): ?>
                                <option value="<?php echo htmlspecialchars($mrk['merek']); ?>" <?php echo ($filter_merek === $mrk['merek']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($mrk['merek']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1" style="border-radius: var(--theme-radius-sm); background: var(--theme-primary); border-color: var(--theme-primary);">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    <?php if (!empty($search) || !empty($filter_lokasi) || !empty($filter_merek)): ?>
                        <a href="index.php" class="btn btn-outline-secondary" style="border-radius: var(--theme-radius-sm);" title="Reset Filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Data Table Card -->
    <div class="simrs-card">
        <div class="simrs-card-header">
            <div>
                <h4 class="mb-0">Daftar Data Alat Elektromedis</h4>
                <small class="text-muted">
                    Menampilkan data inventaris aktif rumah sakit
                    <?php if (!empty($search) || !empty($filter_lokasi) || !empty($filter_merek)): ?>
                        &mdash; <span class="badge bg-primary">Hasil filter pencarian</span>
                    <?php endif; ?>
                </small>
            </div>
            <div>
                <a href="add.php" class="btn-simrs-primary btn-sm py-2 px-3">
                    <i class="bi bi-plus-circle"></i> Tambah Alat
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table simrs-table">
                <thead>
                    <tr>
                        <th style="width: 60px;" class="text-center">No</th>
                        <th>Nama Alat</th>
                        <th>Tahun</th>
                        <th>Merek</th>
                        <th>Lokasi / Ruangan</th>
                        <th>No. Seri</th>
                        <th style="width: 140px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $has_rows = false;
                    while ($user_data = mysqli_fetch_array($result)) {
                        $has_rows = true;
                        $alat_id = (int)$user_data['id'];
                        $nama_alat = htmlspecialchars($user_data['nama_alat']);
                        $tahun = htmlspecialchars($user_data['tahun']);
                        $merek = htmlspecialchars($user_data['merek']);
                        $lokasi = htmlspecialchars($user_data['lokasi']);
                        $no_seri = htmlspecialchars($user_data['no_seri']);
                    ?>
                    <tr>
                        <td class="text-center fw-semibold text-muted"><?php echo $i; ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: var(--theme-primary-light); color: var(--theme-primary); font-size: 0.95rem; flex-shrink: 0;">
                                    <i class="bi bi-activity"></i>
                                </div>
                                <span class="fw-bold" style="color: var(--theme-text);"><?php echo $nama_alat; ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-calendar3 me-1"></i> <?php echo $tahun; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-brand">
                                <?php echo $merek; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-location">
                                <i class="bi bi-geo-alt-fill"></i> <?php echo $lokasi; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-sn">
                                <?php echo $no_seri; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="edit.php?id=<?php echo $alat_id; ?>" class="btn btn-outline-primary" style="border-radius: var(--theme-radius-sm) 0 0 var(--theme-radius-sm);" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" style="border-radius: 0 var(--theme-radius-sm) var(--theme-radius-sm) 0;" title="Hapus Data" onclick="confirmDelete(<?php echo $alat_id; ?>, '<?php echo addslashes($nama_alat); ?>', '<?php echo addslashes($no_seri); ?>')">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php
                        $i++;
                    }
                    ?>

                    <?php if (!$has_rows): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <h5 class="text-muted">Tidak Ada Data Alat Ditemukan</h5>
                                <p class="text-muted mb-3" style="font-size: 0.9rem;">
                                    <?php if (!empty($search) || !empty($filter_lokasi) || !empty($filter_merek)): ?>
                                        Tidak ada data yang cocok dengan kriteria filter pencarian.
                                    <?php else: ?>
                                        Belum ada data alat elektromedis yang tersimpan.
                                    <?php endif; ?>
                                </p>
                                <a href="add.php" class="btn-simrs-primary btn-sm">
                                    <i class="bi bi-plus-lg"></i> Tambah Alat Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Data -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--theme-radius); background: var(--theme-surface); border: 1px solid var(--theme-border);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-heading text-danger" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-2">Apakah Anda yakin ingin menghapus data alat elektromedis berikut?</p>
                <div class="p-3 rounded-3" style="background: var(--theme-surface-subtle); border: 1px solid var(--theme-border);">
                    <div class="fw-bold mb-1" id="deleteModalNamaAlat" style="color: var(--theme-text);"></div>
                    <small class="text-muted">No. Seri: <span id="deleteModalNoSeri" class="badge-sn"></span></small>
                </div>
                <small class="text-danger mt-2 d-block">
                    <i class="bi bi-info-circle me-1"></i> Tindakan ini tidak dapat dibatalkan. Data akan dihapus secara permanen dari basis data.
                </small>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: var(--theme-radius-sm);">Batal</button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger" style="border-radius: var(--theme-radius-sm);">
                    <i class="bi bi-trash3-fill me-1"></i> Ya, Hapus Data
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, namaAlat, noSeri) {
    document.getElementById('deleteModalNamaAlat').innerText = namaAlat;
    document.getElementById('deleteModalNoSeri').innerText = noSeri;
    document.getElementById('deleteConfirmBtn').href = 'delete.php?id=' + id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>

<?php include("includes/footer.php"); ?>
