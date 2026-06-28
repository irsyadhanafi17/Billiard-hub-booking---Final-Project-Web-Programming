<?php
require_once(__DIR__.'/../class/class.Outlet.php');
require_once(__DIR__.'/../class/class.User.php');

$objOutlet = new Outlet();
$objUser   = new User();
$allUsers  = $objUser->SelectAllUsers();
$managers  = array_filter($allUsers, fn($u) => $u->role == 'manager' || $u->role == 'admin');

if (isset($_POST['btnAdd'])) {
    $objOutlet->outlet_name = $_POST['outlet_name'];
    $objOutlet->location    = $_POST['location'];
    $objOutlet->manager_id  = $_POST['manager_id'] ?: null;
    $objOutlet->AddOutlet();
    echo "<script>alert('" . addslashes($objOutlet->message) . "');</script>";
    echo '<script>window.location="dashboardadmin.php?p=outletlist";</script>'; exit();
}

if (isset($_POST['btnUpdate'])) {
    $objOutlet->outlet_id   = $_POST['outlet_id'];
    $objOutlet->outlet_name = $_POST['outlet_name'];
    $objOutlet->location    = $_POST['location'];
    $objOutlet->manager_id  = $_POST['manager_id'] ?: null;
    $objOutlet->UpdateOutlet();
    echo "<script>alert('" . addslashes($objOutlet->message) . "');</script>";
    echo '<script>window.location="dashboardadmin.php?p=outletlist";</script>'; exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $objOutlet->outlet_id = (int)$_GET['id'];
    $objOutlet->DeleteOutlet();
    echo "<script>alert('" . addslashes($objOutlet->message) . "');</script>";
    echo '<script>window.location="dashboardadmin.php?p=outletlist";</script>'; exit();
}

$allOutlets = $objOutlet->SelectAllOutlet();
?>

<style>
.ol-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.ol-header h3{font-family:'Boldonse',sans-serif!important;font-size:20px!important;color:#fff;margin:0}
.btn-add-primary{background:#8bd100;color:#000;font-weight:700;font-size:12px;padding:10px 20px;border-radius:8px;border:none;cursor:pointer;transition:all 0.2s;display:inline-flex;align-items:center;gap:6px}
.btn-add-primary:hover{background:#fff}
.outlet-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}
.outlet-admin-card{background:#1f0410;border:1px solid #2f0618;border-radius:14px;overflow:hidden;transition:border-color 0.3s}
.outlet-admin-card:hover{border-color:#8bd100}
.ocard-img{height:160px;background:#111 url('assets/stocks/Afterhour (3).png') center/cover;position:relative}
.ocard-img::after{content:'';position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(0,0,0,0.8))}
.ocard-body{padding:18px}
.ocard-name{font-family:'DM Sans',sans-serif;font-weight:700;color:#fff;font-size:16px;margin:0 0 4px}
.ocard-loc{color:#8A7E6C;font-size:12px;margin:0 0 10px;display:flex;align-items:flex-start;gap:6px}
.ocard-manager{background:#0a1a0a;border:1px solid #1a3a1a;border-radius:6px;padding:6px 10px;font-size:11px;color:#5acc8a;margin-bottom:14px}
.ocard-actions{display:flex;gap:8px}
.btn-oact{font-size:11px;padding:6px 14px;border-radius:6px;cursor:pointer;text-decoration:none;transition:all 0.2s;display:inline-block;border:1px solid;font-weight:600}
.btn-oedit{background:#1a1a3a;border-color:#3a3a8a;color:#8a8aff}
.btn-oedit:hover{background:#8a8aff;color:#000;text-decoration:none}
.btn-odel{background:#3a0a0a;border-color:#8a2020;color:#ff8888}
.btn-odel:hover{background:#ff8888;color:#000;text-decoration:none}
/* Modal */
.modal-dark .modal-content{background:#1f0410;border:1px solid #2f0618;border-radius:16px;color:#fff}
.modal-dark .modal-header{background:#2f0618;border-radius:14px 14px 0 0;border-bottom:1px solid #3f0828;padding:18px 24px}
.modal-dark .modal-header h4{color:#fff;margin:0;font-family:'DM Sans',sans-serif;font-weight:700}
.modal-dark .modal-body{padding:24px}
.modal-dark .modal-footer{border-top:1px solid #2f0618;padding:16px 24px}
.mform-group{margin-bottom:16px}
.mform-group label{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase;margin-bottom:6px;display:block}
.mform-control{background:#0d0106;border:1px solid #2f0618;border-radius:8px;height:42px;color:#fff;font-size:14px;padding:0 14px;width:100%;transition:border-color 0.2s;box-sizing:border-box}
.mform-control:focus{border-color:#8bd100;outline:none}
.mform-control option{background:#0d0106}
.btn-save{background:#8bd100;color:#000;font-weight:700;font-size:13px;padding:10px 28px;border-radius:8px;border:none;cursor:pointer}
.btn-save:hover{background:#fff}
.btn-cancel-m{background:transparent;border:1px solid #2f0618;color:#8A7E6C;font-size:13px;padding:10px 20px;border-radius:8px;cursor:pointer}
</style>

<div class="ol-header">
    <h3>🏢 Kelola Outlet</h3>
    <button class="btn-add-primary" data-toggle="modal" data-target="#modalAdd">
        <span class="glyphicon glyphicon-plus"></span> Tambah Outlet
    </button>
</div>

<div class="outlet-grid">
    <?php foreach ($allOutlets as $i => $ol):
        $imgNum = ($i % 12) + 1;
    ?>
    <div class="outlet-admin-card">
        <div class="ocard-img" style="background-image:url('assets/stocks/Afterhour (<?= $imgNum ?>).png')"></div>
        <div class="ocard-body">
            <h4 class="ocard-name"><?= htmlspecialchars($ol->outlet_name) ?></h4>
            <p class="ocard-loc">
                <span class="glyphicon glyphicon-map-marker" style="color:#e81b7b;margin-top:1px"></span>
                <?= htmlspecialchars($ol->location) ?>
            </p>
            <?php if ($ol->manager_id): ?>
                <?php foreach ($allUsers as $u): if ($u->userid == $ol->manager_id): ?>
                <div class="ocard-manager">👤 Manager: <?= htmlspecialchars($u->name) ?></div>
                <?php break; endif; endforeach; ?>
            <?php else: ?>
                <div class="ocard-manager" style="color:#555;border-color:#1a1a1a">Belum ada manager</div>
            <?php endif; ?>
            <div class="ocard-actions">
                <a class="btn-oact btn-oedit" href="#"
                   onclick="openEdit(<?= $ol->outlet_id ?>, '<?= addslashes($ol->outlet_name) ?>', '<?= addslashes($ol->location) ?>', '<?= $ol->manager_id ?>'); return false;">
                   ✎ Edit
                </a>
                <a class="btn-oact btn-odel"
                   href="dashboardadmin.php?p=outletlist&action=delete&id=<?= $ol->outlet_id ?>"
                   onclick="return confirm('Hapus outlet ini? Semua meja di outlet ini akan ikut terhapus.')">
                   ✕ Hapus
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal Tambah -->
<div class="modal fade modal-dark" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4>➕ Tambah Outlet Baru</h4></div>
            <form method="post">
                <div class="modal-body">
                    <div class="mform-group">
                        <label>Nama Outlet</label>
                        <input type="text" name="outlet_name" class="mform-control" placeholder="cth: Afterhour Sudirman" required>
                    </div>
                    <div class="mform-group">
                        <label>Lokasi / Alamat</label>
                        <input type="text" name="location" class="mform-control" placeholder="Alamat lengkap outlet" required>
                    </div>
                    <div class="mform-group">
                        <label>Manager Outlet</label>
                        <select name="manager_id" class="mform-control">
                            <option value="">-- Belum ditentukan --</option>
                            <?php foreach ($managers as $m): ?>
                            <option value="<?= $m->userid ?>"><?= htmlspecialchars($m->name) ?> (<?= $m->role ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" class="btn-cancel-m" data-dismiss="modal">Batal</button>
                    <button type="submit" name="btnAdd" class="btn-save">💾 Simpan Outlet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade modal-dark" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4>✎ Edit Outlet</h4></div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="outlet_id" id="editId">
                    <div class="mform-group">
                        <label>Nama Outlet</label>
                        <input type="text" name="outlet_name" id="editName" class="mform-control" required>
                    </div>
                    <div class="mform-group">
                        <label>Lokasi / Alamat</label>
                        <input type="text" name="location" id="editLoc" class="mform-control" required>
                    </div>
                    <div class="mform-group">
                        <label>Manager Outlet</label>
                        <select name="manager_id" id="editManager" class="mform-control">
                            <option value="">-- Belum ditentukan --</option>
                            <?php foreach ($managers as $m): ?>
                            <option value="<?= $m->userid ?>"><?= htmlspecialchars($m->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" class="btn-cancel-m" data-dismiss="modal">Batal</button>
                    <button type="submit" name="btnUpdate" class="btn-save">💾 Update Outlet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEdit(id, name, loc, mgr) {
    document.getElementById('editId').value      = id;
    document.getElementById('editName').value    = name;
    document.getElementById('editLoc').value     = loc;
    document.getElementById('editManager').value = mgr;
    $('#modalEdit').modal('show');
}
</script>
