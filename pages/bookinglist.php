<?php
// pages/bookinglist.php
require_once('./class/class.Booking.php');
$objBooking = new Booking();

// Logika Proses Aksi ketika Admin menekan tombol "Konfirmasi Lunas" (W12)
if (isset($_GET['action']) && $_GET['action'] == 'approve') {
    $id_to_approve = $_GET['id'];
    $objBooking->ApproveBooking($id_to_approve);

    echo "<script>alert('$objBooking->message');</script>";
    echo '<script>window.location = "dashboardadmin.php?p=bookinglist";</script>';
}

// Load seluruh transaksi booking masuk (W12)
$allBookings = $objBooking->SelectAllBookings();
?>

<h4>Daftar Manajemen Reservasi Meja Afterhour</h4>
<hr>

<table class="table table-bordered table-hover">
    <thead>
        <tr class="active">
            <th>No.</th>
            <th>Nama Pelanggan</th>
            <th>Cabang Outlet</th>
            <th>Nomor Meja</th>
            <th>Jadwal Bermain</th>
            <th>Durasi</th>
            <th>Total Tagihan</th>
            <th>Status</th>
            <th>Aksi Kasir</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($allBookings) == 0) {
            echo '<tr><td colspan="9" class="text-center">Belum ada transaksi reservasi yang masuk.</td></tr>';
        } else {
            $no = 1;
            foreach ($allBookings as $data) {
                echo '<tr>';
                echo '<td>' . $no . '</td>';
                echo '<td>' . $data->customer_name . '</td>';
                echo '<td>' . $data->outlet_name . ' (' . $data->class_type . ')</td>';
                echo '<td>Meja ' . $data->table_number . '</td>';
                echo '<td>' . date('d M Y', strtotime($data->booking_date)) . ' | ' . $data->start_time . '</td>';
                echo '<td>' . $data->duration_hours . ' Jam</td>';
                echo '<td>Rp ' . number_format($data->total_price, 0, ',', '.') . '</td>';

                // Variasi Badge Status (W13)
                if ($data->payment_status == 'Pending') {
                    echo '<td><span class="label label-warning">Pending</span></td>';
                    // Jika pending, munculkan tombol aksi approve konfirmasi lunas
                    echo '<td>
                            <a class="btn btn-success btn-xs" href="dashboardadmin.php?p=bookinglist&action=approve&id=' . $data->booking_id . '" onclick="return confirm(\'Konfirmasi pelunasan untuk transaksi ini?\')">
                                <span class="glyphicon glyphicon-ok"></span> Set Lunas
                            </a>
                          </td>';
                } else {
                    echo '<td><span class="label label-success">Paid</span></td>';
                    echo '<td><button class="btn btn-default btn-xs" disabled><span class="glyphicon glyphicon-lock"></span> Locked</button></td>';
                }
                echo '</tr>';
                $no++;
            }
        }
        ?>
    </tbody>
</table>