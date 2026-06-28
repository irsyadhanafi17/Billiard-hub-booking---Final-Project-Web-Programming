<?php
require_once(__DIR__.'/../class/class.Booking.php');
require_once(__DIR__.'/../class/class.Outlet.php');

$objOutlet = new Outlet();
$objOutlet->SelectOutletByManager($_SESSION['userid']);

if (!$objOutlet->hasil) {
    echo '<div style="color:#ff6666;padding:20px">Anda belum memiliki outlet yang ditetapkan. Hubungi Admin.</div>';
    return;
}

$outletId   = $objOutlet->outlet_id;
$outletName = $objOutlet->outlet_name;

$objBooking = new Booking();

if (isset($_GET['action']) && $_GET['action'] == 'approve') {
    $objBooking->ApproveBooking((int)$_GET['id']);
    echo "<script>alert('" . addslashes($objBooking->message) . "');</script>";
    echo '<script>window.location="dashboardmanager.php?p=bookinglist";</script>'; exit();
}
if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
    $objBooking->CancelBooking((int)$_GET['id']);
    echo "<script>alert('" . addslashes($objBooking->message) . "');</script>";
    echo '<script>window.location="dashboardmanager.php?p=bookinglist";</script>'; exit();
}

$bookings = $objBooking->SelectBookingsByOutlet($outletId);
$pending  = $objBooking->CountByStatus('Pending',  $outletId);
$paid     = $objBooking->CountByStatus('Paid',     $outletId);
$revenue  = $objBooking->SumRevenue($outletId);
?>

<style>
.bl-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px}
.bl-stat-card{background:#1a0a12;border:1px solid #2f0618;border-radius:12px;padding:18px}
.bl-stat-card .s-label{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase;margin-bottom:6px}
.bl-stat-card .s-val{font-family:'Boldonse',sans-serif;font-size:26px;color:#fff;line-height:1}
.bl-stat-card .s-val.green{color:#8bd100}
.bl-stat-card .s-val.yellow{color:#ffaa00}
.table-wrap{background:#1f0410;border:1px solid #2f0618;border-radius:14px;overflow:hidden}
.table-wrap .table>thead>tr>th{background:#2f0618;color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:12px 14px}
.table-wrap .table>tbody>tr>td{border-color:#2f0618;color:#EDE8DC;padding:11px 14px;font-size:13px;vertical-align:middle}
.table-wrap .table-hover>tbody>tr:hover>td{background:rgba(255,255,255,0.02)}
.status-badge{display:inline-block;padding:4px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase}
.badge-pending{background:#3a2a00;color:#ffaa00;border:1px solid #5a4000}
.badge-paid{background:#0a2a0a;color:#5acc8a;border:1px solid #1a5a1a}
.badge-cancelled{background:#3a0a0a;color:#ff6666;border:1px solid #5a1a1a}
.btn-xs-approve{background:#0a2a0a;border:1px solid #1a5a1a;color:#5acc8a;font-size:11px;padding:4px 10px;border-radius:6px;cursor:pointer;text-decoration:none;transition:all 0.2s;display:inline-block}
.btn-xs-approve:hover{background:#5acc8a;color:#000;text-decoration:none}
.btn-xs-cancel{background:#3a0a0a;border:1px solid #5a1a1a;color:#ff6666;font-size:11px;padding:4px 10px;border-radius:6px;cursor:pointer;text-decoration:none;transition:all 0.2s;display:inline-block;margin-left:4px}
.btn-xs-cancel:hover{background:#ff6666;color:#000;text-decoration:none}
@media(max-width:768px){.bl-stats{grid-template-columns:1fr}}
</style>

<div style="margin-bottom:20px">
    <h3 style="font-family:'Boldonse',sans-serif;font-size:20px;color:#fff;margin:0 0 4px">Transaksi Outlet</h3>
    <p style="color:#8bd100;font-size:13px;margin:0">📍 <?= htmlspecialchars($outletName) ?></p>
</div>

<div class="bl-stats">
    <div class="bl-stat-card">
        <div class="s-label">Menunggu</div>
        <div class="s-val yellow"><?= $pending ?></div>
    </div>
    <div class="bl-stat-card">
        <div class="s-label">Lunas</div>
        <div class="s-val green"><?= $paid ?></div>
    </div>
    <div class="bl-stat-card">
        <div class="s-label">Pendapatan Outlet</div>
        <div class="s-val green" style="font-size:16px">Rp <?= number_format($revenue,0,',','.') ?></div>
    </div>
</div>

<div class="table-wrap">
    <table class="table table-hover" style="margin:0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Pelanggan</th>
                <th>Meja</th>
                <th>Jadwal</th>
                <th>Durasi</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bookings)): ?>
            <tr><td colspan="8" style="text-align:center;color:#555;padding:40px">Belum ada booking di outlet ini.</td></tr>
            <?php else: $no=1; foreach ($bookings as $data): ?>
            <tr>
                <td style="color:#555"><?= $no++ ?></td>
                <td style="font-weight:600;color:#fff"><?= htmlspecialchars($data->customer_name) ?></td>
                <td>
                    <div style="color:#fff">Meja <?= htmlspecialchars($data->table_number) ?></div>
                    <div style="font-size:11px;color:#8A7E6C"><?= htmlspecialchars($data->class_type) ?></div>
                </td>
                <td>
                    <div><?= date('d M Y', strtotime($data->booking_date)) ?></div>
                    <div style="font-size:11px;color:#8A7E6C"><?= htmlspecialchars($data->start_time) ?></div>
                </td>
                <td><?= $data->duration_hours ?>j</td>
                <td style="color:#8bd100;font-weight:700">Rp <?= number_format($data->total_price,0,',','.') ?></td>
                <td>
                    <?php if ($data->payment_status=='Pending'): ?>
                        <span class="status-badge badge-pending">Pending</span>
                    <?php elseif ($data->payment_status=='Paid'): ?>
                        <span class="status-badge badge-paid">Lunas</span>
                    <?php else: ?>
                        <span class="status-badge badge-cancelled">Batal</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($data->payment_status=='Pending'): ?>
                        <a class="btn-xs-approve" href="dashboardmanager.php?p=bookinglist&action=approve&id=<?= $data->booking_id ?>"
                           onclick="return confirm('Konfirmasi pelunasan?')">✓ Lunas</a>
                        <a class="btn-xs-cancel" href="dashboardmanager.php?p=bookinglist&action=cancel&id=<?= $data->booking_id ?>"
                           onclick="return confirm('Batalkan booking ini?')">✕</a>
                    <?php else: ?>
                        <span style="color:#333;font-size:11px">locked</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
