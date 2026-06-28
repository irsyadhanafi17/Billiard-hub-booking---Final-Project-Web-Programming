<?php
require_once(__DIR__.'/../class/class.Discount.php');
require_once(__DIR__.'/../class/class.User.php');
require_once(__DIR__.'/../class/class.Mail.php');

$objDiscount = new Discount();
$objUser     = new User();

// Tambah diskon
if (isset($_POST['btnAdd'])) {
    $objDiscount->title        = $_POST['title'];
    $objDiscount->description  = $_POST['description'];
    $objDiscount->discount_pct = $_POST['discount_pct'];
    $objDiscount->valid_from   = $_POST['valid_from'];
    $objDiscount->valid_until  = $_POST['valid_until'];
    $objDiscount->is_active    = isset($_POST['is_active']) ? 1 : 0;
    $objDiscount->AddDiscount();

    // Broadcast email ke semua customer jika aktif
    if ($objDiscount->hasil && $objDiscount->is_active) {
        $customers = $objUser->SelectAllCustomers();
        $sent = @Mail::BroadcastDiscount($customers, $objDiscount);
        echo "<script>alert('" . addslashes($objDiscount->message) . " Email promo dikirim ke {$sent} member.');</script>";
    } else {
        echo "<script>alert('" . addslashes($objDiscount->message) . "');</script>";
    }
    echo '<script>window.location="dashboardadmin.php?p=discountlist";</script>'; exit();
}

// Update diskon
if (isset($_POST['btnUpdate'])) {
    $objDiscount->discount_id  = $_POST['discount_id'];
    $objDiscount->title        = $_POST['title'];
    $objDiscount->description  = $_POST['description'];
    $objDiscount->discount_pct = $_POST['discount_pct'];
    $objDiscount->valid_from   = $_POST['valid_from'];
    $objDiscount->valid_until  = $_POST['valid_until'];
    $objDiscount->is_active    = isset($_POST['is_active']) ? 1 : 0;
    $objDiscount->UpdateDiscount();
    echo "<script>alert('" . addslashes($objDiscount->message) . "');</script>";
    echo '<script>window.location="dashboardadmin.php?p=discountlist";</script>'; exit();
}

// Hapus diskon
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $objDiscount->discount_id = (int)$_GET['id'];
    $objDiscount->DeleteDiscount();
    echo "<script>alert('" . addslashes($objDiscount->message) . "');</script>";
    echo '<script>window.location="dashboardadmin.php?p=discountlist";</script>'; exit();
}

// Broadcast ulang diskon yang sudah ada
if (isset($_GET['action']) && $_GET['action'] == 'broadcast') {
    $objDiscount->SelectOneDiscount((int)$_GET['id']);
    if ($objDiscount->hasil) {
        $customers = $objUser->SelectAllCustomers();
        $sent = @Mail::BroadcastDiscount($customers, $objDiscount);
        echo "<script>alert('Email promo berhasil dikirim ke {$sent} member!');</script>";
    }
    echo '<script>window.location="dashboardadmin.php?p=discountlist";</script>'; exit();
}

$allDiscounts = $objDiscount->SelectAllDiscounts();
$today = date('Y-m-d');
?>

<style>
.dl-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.dl-header h3{font-family:'Boldonse',sans-serif!important;font-size:20px!important;color:#fff;margin:0}
.btn-add-primary{background:#8bd100;color:#000;font-weight:700;font-size:12px;padding:10px 20px;border-radius:8px;border:none;cursor:pointer;letter-spacing:0.5px;transition:all 0.2s;display:inline-flex;align-items:center;gap:6px}
.btn-add-primary:hover{background:#fff}
.promo-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;margin-bottom:20px}
.promo-card-admin{background:#1f0410;border:1px solid #2f0618;border-radius:14px;padding:20px;position:relative;overflow:hidden;transition:border-color 0.3s}
.promo-card-admin:hover{border-color:#8bd100}
.promo-card-admin.inactive{opacity:0.5}
.promo-card-admin .active-tag{position:absolute;top:14px;right:14px;background:#0a2a0a;border:1px solid #1a5a1a;color:#5acc8a;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:1px;text-transform:uppercase}
.promo-card-admin .inactive-tag{position:absolute;top:14px;right:14px;background:#3a0a0a;border:1px solid #5a1a1a;color:#ff6666;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:1px;text-transform:uppercase}
.promo-card-admin .pct-big{font-family:'Boldonse',sans-serif;font-size:48px;color:#8bd100;line-height:1;margin-bottom:8px}
.promo-card-admin h4{color:#fff;font-family:'DM Sans',sans-serif;font-weight:700;font-size:16px;margin:0 0 8px}
.promo-card-admin p{color:#8A7E6C;font-size:13px;margin:0 0 12px;line-height:1.5}
.promo-card-admin .valid-info{color:#555;font-size:11px;margin-bottom:16px}
.promo-card-admin .valid-info span{color:#e81b7b}
.promo-actions{display:flex;gap:8px;flex-wrap:wrap}
.btn-pact{font-size:11px;padding:5px 12px;border-radius:6px;cursor:pointer;text-decoration:none;transition:all 0.2s;display:inline-block;border:1px solid;font-weight:600}
.btn-pedit{background:#1a1a3a;border-color:#3a3a8a;color:#8a8aff}
.btn-pedit:hover{background:#8a8aff;color:#000;text-decoration:none}
.btn-pbcast{background:#1a2a3a;border-color:#3a5a8a;color:#5aacff}
.btn-pbcast:hover{background:#5aacff;color:#000;text-decoration:none}
.btn-pdel{background:#3a0a0a;border-color:#8a2020;color:#ff8888}
.btn-pdel:hover{background:#ff8888;color:#000;text-decoration:none}
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
.mform-textarea{height:80px!important;padding:12px 14px!important;resize:vertical}
.mform-check{display:flex;align-items:center;gap:10px;cursor:pointer;color:#EDE8DC;font-size:14px}
.mform-check input{width:18px;height:18px;accent-color:#8bd100}
.btn-save{background:#8bd100;color:#000;font-weight:700;font-size:13px;padding:10px 28px;border-radius:8px;border:none;cursor:pointer;transition:all 0.2s}
.btn-save:hover{background:#fff}
.btn-cancel-m{background:transparent;border:1px solid #2f0618;color:#8A7E6C;font-size:13px;padding:10px 20px;border-radius:8px;cursor:pointer}
.btn-cancel-m:hover{color:#fff;border-color:#555}
.email-hint{background:#0a1a2a;border:1px solid #1a3a5a;border-radius:8px;padding:12px 16px;margin-top:12px;font-size:12px;color:#5aacff}
</style>

<div class="dl-header">
    <div>
        <h3>🎁 Kelola Promo & Diskon</h3>
        <p style="color:#8A7E6C;font-size:12px;margin:4px 0 0">Buat & kirim promo eksklusif ke semua member via email</p>
    </div>
    <button class="btn-add-primary" data-toggle="modal" data-target="#modalAdd">
        <span class="glyphicon glyphicon-plus"></span> Buat Promo Baru
    </button>
</div>

<?php if (empty($allDiscounts)): ?>
<div style="text-align:center;padding:60px;color:#555">
    <div style="font-size:48px;margin-bottom:16px">🎁</div>
    <h4 style="color:#fff">Belum Ada Promo</h4>
    <p>Buat promo pertama dan broadcast ke semua member!</p>
</div>
<?php else: ?>
<div class="promo-grid">
    <?php foreach ($allDiscounts as $d):
        $isExpired = $d->valid_until < $today;
        $isActive  = $d->is_active && !$isExpired;
    ?>
    <div class="promo-card-admin <?= (!$isActive) ? 'inactive' : '' ?>">
        <?php if ($isActive): ?>
            <div class="active-tag">✓ Aktif</div>
        <?php else: ?>
            <div class="inactive-tag"><?= $isExpired ? 'Kadaluarsa' : 'Nonaktif' ?></div>
        <?php endif; ?>

        <div class="pct-big"><?= (int)$d->discount_pct ?>%</div>
        <h4><?= htmlspecialchars($d->title) ?></h4>
        <p><?= htmlspecialchars($d->description) ?></p>
        <div class="valid-info">
            <span class="glyphicon glyphicon-calendar"></span>
            Berlaku: <span><?= date('d M Y',strtotime($d->valid_from)) ?></span> –
            <span><?= date('d M Y',strtotime($d->valid_until)) ?></span>
        </div>
        <div class="promo-actions">
            <a class="btn-pact btn-pedit" href="#"
               onclick="openEdit(<?= $d->discount_id ?>,'<?= addslashes($d->title) ?>','<?= addslashes($d->description) ?>',<?= $d->discount_pct ?>,'<?= $d->valid_from ?>','<?= $d->valid_until ?>',<?= $d->is_active ?>); return false;">
               ✎ Edit
            </a>
            <a class="btn-pact btn-pbcast"
               href="dashboardadmin.php?p=discountlist&action=broadcast&id=<?= $d->discount_id ?>"
               onclick="return confirm('Broadcast email promo ini ke semua member sekarang?')">
               📧 Broadcast Email
            </a>
            <a class="btn-pact btn-pdel"
               href="dashboardadmin.php?p=discountlist&action=delete&id=<?= $d->discount_id ?>"
               onclick="return confirm('Hapus promo ini?')">
               ✕ Hapus
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Modal Tambah -->
<div class="modal fade modal-dark" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4>➕ Buat Promo Baru</h4></div>
            <form method="post">
                <div class="modal-body">
                    <div class="mform-group">
                        <label>Judul Promo</label>
                        <input type="text" name="title" class="mform-control" placeholder="cth: Happy Hour Spesial" required>
                    </div>
                    <div class="mform-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="mform-control mform-textarea" placeholder="Jelaskan detail promo ini..." required></textarea>
                    </div>
                    <div class="mform-group">
                        <label>Persentase Diskon (%)</label>
                        <input type="number" name="discount_pct" class="mform-control" placeholder="20" min="1" max="100" step="0.5" required>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="mform-group">
                            <label>Berlaku Dari</label>
                            <input type="date" name="valid_from" class="mform-control" value="<?= $today ?>" required>
                        </div>
                        <div class="mform-group">
                            <label>Berlaku Sampai</label>
                            <input type="date" name="valid_until" class="mform-control" required>
                        </div>
                    </div>
                    <div class="mform-group">
                        <label class="mform-check">
                            <input type="checkbox" name="is_active" value="1" checked>
                            Langsung aktif &amp; broadcast email ke semua member
                        </label>
                    </div>
                    <div class="email-hint">
                        📧 Jika dicentang, email promo akan otomatis dikirim ke semua member saat promo disimpan.
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" class="btn-cancel-m" data-dismiss="modal">Batal</button>
                    <button type="submit" name="btnAdd" class="btn-save">🎁 Simpan & Broadcast</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade modal-dark" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4>✎ Edit Promo</h4></div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="discount_id" id="editId">
                    <div class="mform-group">
                        <label>Judul Promo</label>
                        <input type="text" name="title" id="editTitle" class="mform-control" required>
                    </div>
                    <div class="mform-group">
                        <label>Deskripsi</label>
                        <textarea name="description" id="editDesc" class="mform-control mform-textarea" required></textarea>
                    </div>
                    <div class="mform-group">
                        <label>Persentase Diskon (%)</label>
                        <input type="number" name="discount_pct" id="editPct" class="mform-control" min="1" max="100" step="0.5" required>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="mform-group">
                            <label>Berlaku Dari</label>
                            <input type="date" name="valid_from" id="editFrom" class="mform-control" required>
                        </div>
                        <div class="mform-group">
                            <label>Berlaku Sampai</label>
                            <input type="date" name="valid_until" id="editUntil" class="mform-control" required>
                        </div>
                    </div>
                    <div class="mform-group">
                        <label class="mform-check">
                            <input type="checkbox" name="is_active" id="editActive" value="1">
                            Aktifkan promo ini
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" class="btn-cancel-m" data-dismiss="modal">Batal</button>
                    <button type="submit" name="btnUpdate" class="btn-save">💾 Update Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEdit(id, title, desc, pct, from, until, active) {
    document.getElementById('editId').value    = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDesc').value  = desc;
    document.getElementById('editPct').value   = pct;
    document.getElementById('editFrom').value  = from;
    document.getElementById('editUntil').value = until;
    document.getElementById('editActive').checked = (active == 1);
    $('#modalEdit').modal('show');
}
</script>
