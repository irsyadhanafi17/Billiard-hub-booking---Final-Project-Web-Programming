<?php
require_once(__DIR__.'/../class/class.BilliardTable.php');
require_once(__DIR__.'/../class/class.Outlet.php');

$objTable  = new BilliardTable();
$objOutlet = new Outlet();
$arrOutlet = $objOutlet->SelectAllOutlet();

if (isset($_POST['btnAdd'])) {
    $objTable->outlet_id      = $_POST['outlet_id'];
    $objTable->table_number   = $_POST['table_number'];
    $objTable->class_type     = $_POST['class_type'];
    $objTable->price_per_hour = $_POST['price_per_hour'];
    $objTable->status         = $_POST['status'];
    $objTable->AddTable();
    echo "<script>alert('" . addslashes($objTable->message) . "');</script>";
    echo '<script>window.location="dashboardadmin.php?p=mejalist";</script>'; exit();
}

if (isset($_POST['btnUpdate'])) {
    $objTable->table_id       = $_POST['table_id'];
    $objTable->class_type     = $_POST['class_type'];
    $objTable->price_per_hour = $_POST['price_per_hour'];
    $objTable->status         = $_POST['status'];
    $objTable->UpdateTable();
    echo "<script>alert('" . addslashes($objTable->message) . "');</script>";
    echo '<script>window.location="dashboardadmin.php?p=mejalist";</script>'; exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $objTable->table_id = (int)$_GET['id'];
    $objTable->DeleteTable();
    echo "<script>alert('" . addslashes($objTable->message) . "');</script>";
    echo '<script>window.location="dashboardadmin.php?p=mejalist";</script>'; exit();
}

$filterOutlet = isset($_GET['outlet']) ? (int)$_GET['outlet'] : 0;
$allTables    = $filterOutlet ? $objTable->SelectAllTablesByOutlet($filterOutlet) : $objTable->SelectAllTables();
?>

<style>
.ml-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.ml-header h3{font-family:'Boldonse',sans-serif!important;font-size:20px!important;color:#fff;margin:0}
.btn-add-primary{background:#8bd100;color:#000;font-weight:700;font-size:12px;padding:10px 20px;border-radius:8px;border:none;cursor:pointer;letter-spacing:0.5px;transition:all 0.2s;display:inline-flex;align-items:center;gap:6px}
.btn-add-primary:hover{background:#fff}
.filter-bar{display:flex;gap:10px;margin-bottom:18px;align-items:center;flex-wrap:wrap}
.filter-bar select,.filter-bar a{background:#1f0410;border:1px solid #2f0618;color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;text-decoration:none;cursor:pointer;transition:border-color 0.2s}
.filter-bar select{height:38px}
.filter-bar select:focus{outline:none;border-color:#8bd100}
.filter-bar a:hover{border-color:#8bd100;color:#8bd100}
.table-wrap{background:#1f0410;border:1px solid #2f0618;border-radius:14px;overflow:hidden;margin-bottom:20px}
.table-wrap .table>thead>tr>th{background:#2f0618;color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:12px 14px;font-family:'DM Sans',sans-serif;font-weight:600}
.table-wrap .table>tbody>tr>td{border-color:#2f0618;color:#EDE8DC;padding:11px 14px;font-size:13px;vertical-align:middle}
.table-wrap .table-hover>tbody>tr:hover>td{background:rgba(255,255,255,0.02)}
.status-dot{display:inline-flex;align-items:center;gap:6px;font-size:12px}
.dot{width:8px;height:8px;border-radius:50%;display:inline-block}
.dot-available{background:#8bd100}
.dot-booked{background:#ffaa00}
.dot-maintenance{background:#ff6666}
.class-chip{display:inline-block;padding:3px 10px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase}
.chip-regular{background:#1a3a2a;color:#5acc8a}
.chip-vip{background:#1a1a3a;color:#8a8aff}
.chip-vvip{background:#3a1a00;color:#ffaa00}
.btn-action{font-size:11px;padding:4px 10px;border-radius:6px;cursor:pointer;text-decoration:none;transition:all 0.2s;display:inline-block;border:1px solid}
.btn-edit{background:#1a1a3a;border-color:#3a3a8a;color:#8a8aff}
.btn-edit:hover{background:#8a8aff;color:#000;text-decoration:none}
.btn-del{background:#3a0a0a;border-color:#8a2020;color:#ff8888;margin-left:4px}
.btn-del:hover{background:#ff8888;color:#000;text-decoration:none}
/* Modal */
.modal-dark .modal-content{background:#1f0410;border:1px solid #2f0618;border-radius:16px;color:#fff}
.modal-dark .modal-header{background:#2f0618;border-radius:14px 14px 0 0;border-bottom:1px solid #3f0828;padding:18px 24px}
.modal-dark .modal-header h4{color:#fff;margin:0;font-family:'DM Sans',sans-serif;font-weight:700}
.modal-dark .modal-body{padding:24px}
.modal-dark .modal-footer{border-top:1px solid #2f0618;padding:16px 24px}
.mform-group{margin-bottom:16px}
.mform-group label{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase;margin-bottom:6px;display:block;font-family:'DM Sans',sans-serif}
.mform-control{background:#0d0106;border:1px solid #2f0618;border-radius:8px;height:42px;color:#fff;font-size:14px;padding:0 14px;width:100%;transition:border-color 0.2s;box-sizing:border-box}
.mform-control:focus{border-color:#8bd100;outline:none}
.mform-control option{background:#0d0106;color:#fff}
.btn-save{background:#8bd100;color:#000;font-weight:700;font-size:13px;padding:10px 28px;border-radius:8px;border:none;cursor:pointer;transition:all 0.2s}
.btn-save:hover{background:#fff}
.btn-cancel-m{background:transparent;border:1px solid #2f0618;color:#8A7E6C;font-size:13px;padding:10px 20px;border-radius:8px;cursor:pointer}
.btn-cancel-m:hover{color:#fff;border-color:#555}
</style>

<div class="ml-header">
    <h3>🎱 Kelola Meja Billiard</h3>
    <button class="btn-add-primary" data-toggle="modal" data-target="#modalAdd">
        <span class="glyphicon glyphicon-plus"></span> Tambah Meja Baru
    </button>
</div>

<div class="filter-bar">
    <select onchange="window.location='dashboardadmin.php?p=mejalist&outlet='+this.value">
        <option value="0" <?= !$filterOutlet ? 'selected' : '' ?>>Semua Outlet</option>
        <?php foreach ($arrOutlet as $ol): ?>
        <option value="<?= $ol->outlet_id ?>" <?= $filterOutlet == $ol->outlet_id ? 'selected' : '' ?>><?= htmlspecialchars($ol->outlet_name) ?></option>
        <?php endforeach; ?>
    </select>
    <span style="color:#555;font-size:13px"><?= count($allTables) ?> meja ditemukan</span>
</div>

<div class="table-wrap">
    <table class="table table-hover" style="margin:0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Outlet</th>
                <th>No. Meja</th>
                <th>Kelas</th>
                <th>Harga/Jam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($allTables)): ?>
            <tr><td colspan="7" style="text-align:center;color:#555;padding:40px">Belum ada meja terdaftar.</td></tr>
            <?php else: $no=1; foreach ($allTables as $t): ?>
            <tr>
                <td style="color:#555"><?= $no++ ?></td>
                <td style="color:#fff;font-weight:600">
                    <?php
                    foreach ($arrOutlet as $ol) {
                        if ($ol->outlet_id == $t->outlet_id) { echo htmlspecialchars($ol->outlet_name); break; }
                    }
                    ?>
                </td>
                <td><strong><?= htmlspecialchars($t->table_number) ?></strong></td>
                <td>
                    <?php
                    $cc = $t->class_type == 'Regular Floor' ? 'chip-regular' : ($t->class_type == 'VIP Smoking' ? 'chip-vip' : 'chip-vvip');
                    ?>
                    <span class="class-chip <?= $cc ?>"><?= htmlspecialchars($t->class_type) ?></span>
                </td>
                <td style="color:#8bd100;font-weight:700">Rp <?= number_format($t->price_per_hour,0,',','.') ?></td>
                <td>
                    <?php
                    $dc = $t->status == 'Available' ? 'dot-available' : ($t->status == 'Booked' ? 'dot-booked' : 'dot-maintenance');
                    ?>
                    <span class="status-dot"><span class="dot <?= $dc ?>"></span><?= htmlspecialchars($t->status) ?></span>
                </td>
                <td>
                    <a class="btn-action btn-edit" href="#"
                       onclick="openEdit(<?= $t->table_id ?>, '<?= htmlspecialchars($t->class_type) ?>', <?= $t->price_per_hour ?>, '<?= $t->status ?>'); return false;">
                       ✎ Edit
                    </a>
                    <a class="btn-action btn-del"
                       href="dashboardadmin.php?p=mejalist&action=delete&id=<?= $t->table_id ?>"
                       onclick="return confirm('Hapus meja ini? Pastikan tidak ada booking aktif.')">
                       ✕ Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade modal-dark" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>➕ Tambah Meja Baru</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mform-group">
                        <label>Outlet</label>
                        <select name="outlet_id" class="mform-control" required>
                            <option value="">-- Pilih Outlet --</option>
                            <?php foreach ($arrOutlet as $ol): ?>
                            <option value="<?= $ol->outlet_id ?>"><?= htmlspecialchars($ol->outlet_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mform-group">
                        <label>Nomor Meja</label>
                        <input type="text" name="table_number" class="mform-control" placeholder="cth: A1, B2, VIP-1" required>
                    </div>
                    <div class="mform-group">
                        <label>Kelas Meja</label>
                        <select name="class_type" class="mform-control" required>
                            <option value="Regular Floor">Regular Floor</option>
                            <option value="VIP Smoking">VIP Smoking</option>
                            <option value="VVIP">VVIP</option>
                        </select>
                    </div>
                    <div class="mform-group">
                        <label>Harga per Jam (Rp)</label>
                        <input type="number" name="price_per_hour" class="mform-control" placeholder="35000" min="1000" step="1000" required>
                    </div>
                    <div class="mform-group">
                        <label>Status Awal</label>
                        <select name="status" class="mform-control">
                            <option value="Available">Available</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" class="btn-cancel-m" data-dismiss="modal">Batal</button>
                    <button type="submit" name="btnAdd" class="btn-save">💾 Simpan Meja</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-dark" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>✎ Edit Meja</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="table_id" id="editTableId">
                    <div class="mform-group">
                        <label>Kelas Meja</label>
                        <select name="class_type" id="editClassType" class="mform-control" required>
                            <option value="Regular Floor">Regular Floor</option>
                            <option value="VIP Smoking">VIP Smoking</option>
                            <option value="VVIP">VVIP</option>
                        </select>
                    </div>
                    <div class="mform-group">
                        <label>Harga per Jam (Rp)</label>
                        <input type="number" name="price_per_hour" id="editPrice" class="mform-control" min="1000" step="1000" required>
                    </div>
                    <div class="mform-group">
                        <label>Status</label>
                        <select name="status" id="editStatus" class="mform-control">
                            <option value="Available">Available</option>
                            <option value="Booked">Booked</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" class="btn-cancel-m" data-dismiss="modal">Batal</button>
                    <button type="submit" name="btnUpdate" class="btn-save">💾 Update Meja</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEdit(id, classType, price, status) {
    document.getElementById('editTableId').value  = id;
    document.getElementById('editClassType').value = classType;
    document.getElementById('editPrice').value     = price;
    document.getElementById('editStatus').value    = status;
    $('#modalEdit').modal('show');
}
</script>
