<?php
if (!isset($_SESSION["role"]) || $_SESSION["role"] != 'customer') {
    echo "<script>alert('Akses ilegal!');</script>";
    echo "<script>window.location='index.php?p=login';</script>"; exit();
}

require_once(__DIR__.'/../class/class.Outlet.php');
require_once(__DIR__.'/../class/class.BilliardTable.php');
require_once(__DIR__.'/../class/class.Booking.php');
require_once(__DIR__.'/../class/class.Mail.php');
require_once(__DIR__.'/../class/class.User.php');

$objOutlet  = new Outlet();
$objTable   = new BilliardTable();
$objBooking = new Booking();
$arrOutlet  = $objOutlet->SelectAllOutlet();
$arrTable   = [];
$selected_outlet = '';

if (isset($_POST['outlet_id']) && $_POST['outlet_id'] != '') {
    $selected_outlet = $_POST['outlet_id'];
    $arrTable = $objTable->SelectTablesByOutlet($selected_outlet);
}

if (isset($_POST['btnSubmit'])) {
    $objBooking->userid        = $_SESSION['userid'];
    $objBooking->table_id      = $_POST['table_id'];
    $objBooking->booking_date  = $_POST['booking_date'];
    $objBooking->start_time    = $_POST['start_time'];
    $objBooking->duration_hours= $_POST['duration_hours'];
    $objBooking->AddBooking();

    echo "<script>alert('" . addslashes($objBooking->message) . "');</script>";
    if ($objBooking->hasil) {
        
        $outletObj = new Outlet();
        $arrAllOutlets = $outletObj->SelectAllOutlet();
        $outletName = '';
        foreach ($arrAllOutlets as $ol) {
            if ($ol->outlet_id == $selected_outlet) { $outletName = $ol->outlet_name; break; }
        }
        
        $tableInfo = $arrTable;
        $tableNumber = ''; $classType = '';
        foreach ($tableInfo as $t) {
            if ($t->table_id == $_POST['table_id']) { $tableNumber = $t->table_number; $classType = $t->class_type; break; }
        }
        $bookingData = [
            'outlet_name'   => $outletName,
            'table_number'  => $tableNumber,
            'class_type'    => $classType,
            'booking_date'  => $_POST['booking_date'],
            'start_time'    => $_POST['start_time'],
            'duration_hours'=> $_POST['duration_hours'],
            'total_price'   => $objBooking->total_price,
        ];
        @Mail::SendBookingConfirmation($_SESSION['email'], $_SESSION['name'], $bookingData);
        echo '<script>window.location="dashboardcustomer.php?p=mybookings";</script>';
    }
}
?>

<style>
.booking-wrapper{padding:30px 0;min-height:80vh}
.booking-header{margin-bottom:28px}
.booking-header h3{font-family:'Boldonse',sans-serif!important;font-size:22px!important;color:#fff;margin:0 0 6px}
.booking-header p{color:#8A7E6C;font-size:13px;margin:0}
.form-card{background:#1f0410;border:1px solid #2f0618;border-radius:16px;padding:32px;margin-bottom:20px}
.form-card-title{font-family:'DM Sans',sans-serif;font-weight:700;color:#8bd100;font-size:12px;letter-spacing:2px;text-transform:uppercase;margin:0 0 20px;padding-bottom:12px;border-bottom:1px solid #2f0618}
.fgroup{margin-bottom:20px}
.fgroup label{font-family:'DM Sans',sans-serif;font-weight:500;color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;display:block}
.fcontrol{background:#0d0106;border:1px solid #2f0618;border-radius:10px;height:46px;color:#fff;font-size:14px;padding:0 16px;width:100%;transition:all 0.3s;-webkit-appearance:none;appearance:none;display:block;box-sizing:border-box}
.fcontrol:focus{border-color:#8bd100;outline:none;box-shadow:0 0 8px rgba(139,209,0,0.15)}
.fcontrol option{background:#0d0106;color:#fff}
.input-with-icon{position:relative}
.input-with-icon .fcontrol{padding-left:42px}
.input-with-icon .icon-prefix{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#e81b7b;font-size:14px}
.price-preview{background:#0d0106;border:1px solid #2f0618;border-radius:10px;padding:16px;margin-bottom:20px;display:none}
.price-preview.show{display:block}
.price-preview .label-p{color:#8A7E6C;font-size:12px;margin-bottom:4px}
.price-preview .amount{color:#8bd100;font-size:28px;font-weight:900;font-family:'Boldonse',sans-serif}
.btn-book{background:#8bd100;color:#000;font-family:'DM Sans',sans-serif;font-weight:700;font-size:13px;letter-spacing:0.05em;text-transform:uppercase;height:50px;padding:0 40px;border-radius:30px;border:none;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:8px}
.btn-book:hover{background:#fff;transform:translateY(-2px)}
.btn-back{background:transparent;color:#8A7E6C;font-family:'DM Sans',sans-serif;font-size:13px;height:50px;padding:0 24px;border-radius:30px;border:1px solid #2f0618;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:all 0.3s;margin-left:12px}
.btn-back:hover{color:#fff;border-color:#555;text-decoration:none}
.info-box{background:#0a1a0a;border:1px solid #1a3a1a;border-radius:10px;padding:14px 18px;margin-bottom:20px;font-size:13px;color:#7acc7a}
.table-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;margin-top:16px}
.table-option{background:#0d0106;border:2px solid #2f0618;border-radius:10px;padding:14px;cursor:pointer;transition:all 0.2s;position:relative}
.table-option:hover{border-color:#8bd100}
.table-option.selected{border-color:#8bd100;background:#0d1a00}
.table-option input[type=radio]{position:absolute;opacity:0;width:0;height:0}
.table-option .t-num{font-weight:700;color:#fff;font-size:16px;margin-bottom:4px}
.table-option .t-class{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px}
.table-option .t-price{color:#8bd100;font-size:14px;font-weight:700}
.class-badge{display:inline-block;padding:2px 8px;border-radius:4px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:6px}
.badge-regular{background:#1a3a2a;color:#5acc8a}
.badge-vip{background:#1a1a3a;color:#8a8aff}
.badge-vvip{background:#3a1a00;color:#ffaa00}
</style>

<div class="booking-wrapper">
    <div class="booking-header">
        <h3>🎱 Reservasi Meja</h3>
        <p>Pilih outlet, meja, dan jadwal bermain Anda</p>
    </div>

    <form action="" method="post" id="bookingForm">

        <div class="form-card">
            <div class="form-card-title">01 &mdash; Pilih Lokasi Outlet</div>
            <div class="fgroup">
                <label>Cabang Afterhour</label>
                <div class="input-with-icon">
                    <span class="icon-prefix glyphicon glyphicon-map-marker"></span>
                    <select class="fcontrol" name="outlet_id" id="outletSelect" onchange="this.form.submit()">
                        <option value="">-- Pilih Lokasi Cabang --</option>
                        <?php foreach ($arrOutlet as $outlet): ?>
                            <option value="<?= $outlet->outlet_id ?>"
                                <?= ($selected_outlet == $outlet->outlet_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($outlet->outlet_name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <?php if (!empty($arrTable)): ?>
        <div class="form-card">
            <div class="form-card-title">02 &mdash; Pilih Meja</div>
            <div class="table-grid" id="tableGrid">
                <?php foreach ($arrTable as $table):
                    $badgeClass = $table->class_type == 'Regular Floor' ? 'badge-regular' :
                                 ($table->class_type == 'VIP Smoking'   ? 'badge-vip' : 'badge-vvip');
                ?>
                <label class="table-option" id="opt_<?= $table->table_id ?>" onclick="selectTable(<?= $table->table_id ?>, <?= $table->price_per_hour ?>)">
                    <input type="radio" name="table_id" value="<?= $table->table_id ?>" required>
                    <div class="t-num">Meja <?= htmlspecialchars($table->table_number) ?></div>
                    <span class="class-badge <?= $badgeClass ?>"><?= htmlspecialchars($table->class_type) ?></span>
                    <div class="t-price">Rp <?= number_format($table->price_per_hour,0,',','.') ?>/jam</div>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php elseif ($selected_outlet): ?>
        <div class="info-box">
            <span class="glyphicon glyphicon-info-sign"></span> Tidak ada meja tersedia di outlet ini saat ini.
        </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-title">03 &mdash; Tentukan Jadwal & Durasi</div>
            <div class="row">
                <div class="col-md-4">
                    <div class="fgroup">
                        <label>Tanggal Bermain</label>
                        <div class="input-with-icon">
                            <span class="icon-prefix glyphicon glyphicon-calendar"></span>
                            <input type="date" name="booking_date" class="fcontrol"
                                   style="padding-left:42px" required
                                   min="<?= date('Y-m-d') ?>"
                                   value="<?= isset($_POST['booking_date']) ? htmlspecialchars($_POST['booking_date']) : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fgroup">
                        <label>Jam Mulai</label>
                        <div class="input-with-icon">
                            <span class="icon-prefix glyphicon glyphicon-time"></span>
                            <input type="time" name="start_time" class="fcontrol"
                                   style="padding-left:42px" required
                                   value="<?= isset($_POST['start_time']) ? htmlspecialchars($_POST['start_time']) : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fgroup">
                        <label>Durasi Pemakaian</label>
                        <div class="input-with-icon">
                            <span class="icon-prefix glyphicon glyphicon-hourglass"></span>
                            <select name="duration_hours" class="fcontrol" style="padding-left:42px" onchange="updatePrice()" id="durSelect">
                                <option value="1">1 Jam</option>
                                <option value="2">2 Jam</option>
                                <option value="3">3 Jam</option>
                                <option value="4">4 Jam</option>
                                <option value="5">5 Jam</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="price-preview" id="pricePreview">
                <div class="label-p">Estimasi Total Tagihan</div>
                <div class="amount" id="priceAmount">Rp 0</div>
                <div style="color:#555;font-size:11px;margin-top:4px">*Harga final dihitung server saat konfirmasi</div>
            </div>

            <div style="margin-top:10px">
                <button type="submit" name="btnSubmit" class="btn-book" <?= empty($arrTable) ? 'disabled' : '' ?>>
                    <span class="glyphicon glyphicon-ok-sign"></span> Konfirmasi Booking
                </button>
                <a href="dashboardcustomer.php" class="btn-back">
                    <span class="glyphicon glyphicon-arrow-left"></span> Kembali
                </a>
            </div>
        </div>

    </form>
</div>

<script>
var selectedPrice = 0;

function selectTable(tableId, pricePerHour) {
    selectedPrice = pricePerHour;
    document.querySelectorAll('.table-option').forEach(function(el){ el.classList.remove('selected'); });
    document.getElementById('opt_' + tableId).classList.add('selected');
    document.querySelector('input[value="' + tableId + '"]').checked = true;
    updatePrice();
}

function updatePrice() {
    if (selectedPrice <= 0) return;
    var dur = parseInt(document.getElementById('durSelect').value) || 1;
    var total = selectedPrice * dur;
    var formatted = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('priceAmount').textContent = formatted;
    document.getElementById('pricePreview').classList.add('show');
}
</script>
