<?php
require_once(__DIR__.'/../class/class.User.php');
$objUser  = new User();
$allUsers = $objUser->SelectAllUsers();

$count = ['admin'=>0,'manager'=>0,'customer'=>0];
foreach ($allUsers as $u) { if (isset($count[$u->role])) $count[$u->role]++; }
?>

<style>
.ul-header{margin-bottom:20px}
.ul-header h3{font-family:'Boldonse',sans-serif!important;font-size:20px!important;color:#fff;margin:0 0 4px}
.ul-header p{color:#8A7E6C;font-size:12px;margin:0}
.ul-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px}
.ul-stat{background:#1f0410;border:1px solid #2f0618;border-radius:10px;padding:16px;text-align:center}
.ul-stat .sv{font-family:'Boldonse',sans-serif;font-size:28px;color:#fff;line-height:1;margin-bottom:4px}
.ul-stat .sl{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase}
.table-wrap{background:#1f0410;border:1px solid #2f0618;border-radius:14px;overflow:hidden}
.table-wrap .table>thead>tr>th{background:#2f0618;color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:12px 14px;font-family:'DM Sans',sans-serif;font-weight:600}
.table-wrap .table>tbody>tr>td{border-color:#2f0618;color:#EDE8DC;padding:12px 14px;font-size:13px;vertical-align:middle}
.table-wrap .table-hover>tbody>tr:hover>td{background:rgba(255,255,255,0.02)}
.role-badge{display:inline-block;padding:4px 12px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase}
.role-admin{background:#3a0a3a;color:#ff88ff;border:1px solid #5a1a5a}
.role-manager{background:#3a2a00;color:#ffcc44;border:1px solid #5a4400}
.role-customer{background:#0a1a2a;color:#5aacff;border:1px solid #1a3a5a}
.avatar-circle{width:36px;height:36px;border-radius:50%;background:#2f0618;border:2px solid #4f1030;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0;overflow:hidden}
.avatar-circle img{width:100%;height:100%;object-fit:cover}
@media(max-width:768px){.ul-stats{grid-template-columns:1fr}}
</style>

<div class="ul-header">
    <h3>👥 Manajemen User</h3>
    <p>Daftar seluruh pengguna terdaftar di sistem Afterhour</p>
</div>

<div class="ul-stats">
    <div class="ul-stat">
        <div class="sv" style="color:#ff88ff"><?= $count['admin'] ?></div>
        <div class="sl">Admin</div>
    </div>
    <div class="ul-stat">
        <div class="sv" style="color:#ffcc44"><?= $count['manager'] ?></div>
        <div class="sl">Manager</div>
    </div>
    <div class="ul-stat">
        <div class="sv" style="color:#5aacff"><?= $count['customer'] ?></div>
        <div class="sl">Customer</div>
    </div>
</div>

<div class="table-wrap">
    <table class="table table-hover" style="margin:0">
        <thead>
            <tr>
                <th>No.</th>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($allUsers as $u): ?>
            <tr>
                <td style="color:#555"><?= $no++ ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:12px">
                        <div class="avatar-circle">
                            <?php if ($u->avatar): ?>
                            <img src="uploads/<?= htmlspecialchars($u->avatar) ?>" alt="">
                            <?php else: ?>
                            <?= strtoupper(substr($u->name,0,1)) ?>
                            <?php endif; ?>
                        </div>
                        <span style="font-weight:600;color:#fff"><?= htmlspecialchars($u->name) ?></span>
                    </div>
                </td>
                <td style="color:#8A7E6C"><?= htmlspecialchars($u->email) ?></td>
                <td>
                    <?php $rc = 'role-' . $u->role; ?>
                    <span class="role-badge <?= $rc ?>"><?= ucfirst($u->role) ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
