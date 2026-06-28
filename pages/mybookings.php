<?php
require_once(__DIR__.'/../class/class.Booking.php');
require_once(__DIR__.'/../class/class.Discount.php');

$objBooking = new Booking();
$arrayResult = $objBooking->SelectCustomerBookings($_SESSION['userid']);

$objDiscount = new Discount();
$activeDiscounts = $objDiscount->SelectActiveDiscounts();
?>

<style>
.mybooking-header{margin-bottom:24px}
.mybooking-header h3{font-family:'Boldonse',sans-serif!important;font-size:20px!important;color:#fff;margin:0 0 6px}
.mybooking-header p{color:#8A7E6C;font-size:13px;margin:0}
.booking-table-wrap{background:#1f0410;border:1px solid #2f0618;border-radius:14px;overflow:hidden;margin-bottom:20px}
.booking-table-wrap table{margin:0}
.booking-table-wrap .table>thead>tr>th{background:#2f0618;color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:14px 16px;font-family:'DM Sans',sans-serif;font-weight:600}
.booking-table-wrap .table>tbody>tr>td{border-color:#2f0618;color:#EDE8DC;padding:14px 16px;font-size:13px;vertical-align:middle}
.booking-table-wrap .table-hover>tbody>tr:hover>td{background:rgba(139,209,0,0.04)}
.status-badge{display:inline-block;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase}
.badge-pending{background:#3a2a00;color:#ffaa00;border:1px solid #5a4000}
.badge-paid{background:#0a2a0a;color:#5acc8a;border:1px solid #1a5a1a}
.badge-cancelled{background:#3a0a0a;color:#ff6666;border:1px solid #5a1a1a}
.promo-section{margin-top:24px}
.promo-card{background:#1f0410;border:1px solid #2f0618;border-radius:14px;padding:20px;margin-bottom:14px;display:flex;align-items:center;gap:20px;transition:border-color 0.3s}
.promo-card:hover{border-color:#8bd100}
.promo-pct{background:#0d1a00;border:2px solid #8bd100;border-radius:12px;min-width:80px;height:80px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0}
.promo-pct .pct-num{font-family:'Boldonse',sans-serif;font-size:26px;color:#8bd100;line-height:1}
.promo-pct .pct-label{font-size:9px;color:#8bd100;letter-spacing:1px;text-transform:uppercase}
.promo-body h5{font-family:'DM Sans',sans-serif;font-weight:700;color:#fff;font-size:16px;margin:0 0 6px}
.promo-body p{color:#8A7E6C;font-size:13px;margin:0 0 6px;line-height:1.5}
.promo-valid{font-size:11px;color:#555;display:flex;align-items:center;gap:6px}
.promo-valid span{color:#e81b7b}
.empty-state{text-align:center;padding:60px 20px;color:#555}
.empty-state .icon{font-size:48px;margin-bottom:16px;color:#2f0618}
.payment-info{background:#0a1a0a;border:1px solid #1a3a1a;border-radius:12px;padding:20px;margin-top:20px}
.payment-info h5{color:#5acc8a;font-family:'DM Sans',sans-serif;font-weight:700;font-size:14px;margin:0 0 12px}
.payment-info p{color:#aaa;font-size:13px;margin:0 0 8px}
.payment-info strong{color:#fff}
</style>

<div class="mybooking-header">
    <h3>📋 Riwayat Booking Saya</h3>
    <p>Kelola dan pantau status reservasi meja billiard Anda</p>
</div>

<?php if (count($arrayResult) == 0): ?>
<div class="empty-state">
    <div class="icon"><span class="glyphicon glyphicon-calendar"></span></div>
    <h4 style="color:#fff;margin-bottom:8px">Belum Ada Booking</h4>
    <p style="color:#555;margin-bottom:20px">Yuk, reservasi meja billiard premium Anda sekarang!</p>
    <a href="dashboardcustomer.php?p=booking" class="btn-book" style="background:#8bd100;color:#000;padding:12px 32px;border-radius:30px;text-decoration:none;font-weight:700;font-size:13px">BOOKING SEKARANG</a>
</div>
<?php else: ?>
<div class="booking-table-wrap">
    <table class="table table-hover" style="margin:0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Outlet / Meja</th>
                <th>Jadwal</th>
                <th>Durasi</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($arrayResult as $data): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <div style="font-weight:700;color:#fff"><?= htmlspecialchars($data->outlet_name) ?></div>
                    <div style="font-size:12px;color:#8A7E6C">Meja <?= htmlspecialchars($data->table_number) ?> &bull; <?= htmlspecialchars($data->class_type) ?></div>
                </td>
                <td>
                    <div style="font-weight:600"><?= date('d M Y', strtotime($data->booking_date)) ?></div>
                    <div style="font-size:12px;color:#8A7E6C"><?= htmlspecialchars($data->start_time) ?> WIB</div>
                </td>
                <td><?= $data->duration_hours ?> Jam</td>
                <td style="color:#8bd100;font-weight:700">Rp <?= number_format($data->total_price,0,',','.') ?></td>
                <td>
                    <?php if ($data->payment_status == 'Pending'): ?>
                        <span class="status-badge badge-pending">⏳ Pending</span>
                    <?php elseif ($data->payment_status == 'Paid'): ?>
                        <span class="status-badge badge-paid">✅ Lunas</span>
                    <?php else: ?>
                        <span class="status-badge badge-cancelled">❌ Dibatalkan</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="payment-info">
    <h5><span class="glyphicon glyphicon-info-sign"></span> Instruksi Pembayaran</h5>
    <p><strong>Metode 1 (Bayar di Tempat):</strong> Tunjukkan halaman ini ke kasir saat kedatangan.</p>
    <p><strong>Metode 2 (Transfer):</strong> Transfer ke <strong>BCA 123-456-7890 a.n Afterhour Group</strong>, lalu konfirmasi ke WhatsApp Admin.</p>
    <p style="color:#555;font-size:12px;margin:0">*Status berubah menjadi <strong style="color:#5acc8a">Lunas</strong> setelah diverifikasi kasir.</p>
</div>
<?php endif; ?>

<?php if (!empty($activeDiscounts)): ?>
<div class="promo-section">
    <h4 style="color:#fff;font-family:'DM Sans',sans-serif;font-weight:700;font-size:16px;margin:0 0 16px;padding-top:10px;border-top:1px solid #2f0618">
        🎁 Promo Aktif Untuk Anda
    </h4>
    <?php foreach ($activeDiscounts as $disc): ?>
    <div class="promo-card">
        <div class="promo-pct">
            <div class="pct-num"><?= (int)$disc->discount_pct ?>%</div>
            <div class="pct-label">DISKON</div>
        </div>
        <div class="promo-body">
            <h5><?= htmlspecialchars($disc->title) ?></h5>
            <p><?= htmlspecialchars($disc->description) ?></p>
            <div class="promo-valid">
                <span class="glyphicon glyphicon-calendar"></span>
                Berlaku: <span><?= date('d M Y',strtotime($disc->valid_from)) ?></span> –
                <span><?= date('d M Y',strtotime($disc->valid_until)) ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
