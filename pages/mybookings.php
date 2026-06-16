<?php
// pages/mybookings.php
require_once('./class/class.Booking.php');
$objBooking = new Booking();

// Ambil semua riwayat booking khusus user yang login
$arrayResult = $objBooking->SelectCustomerBookings($_SESSION['userid']);
?>

<h4>Riwayat & Konfirmasi Pemesanan Meja</h4>
<hr>

<table class="table table-bordered table-striped">
    <thead>
        <tr class="active">
            <th>No.</th>
            <th>Cabang Outlet</th>
            <th>Nomor Meja</th>
            <th>Jadwal Main</th>
            <th>Durasi</th>
            <th>Total Bayar</th>
            <th>Status Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($arrayResult) == 0) {
            echo '<tr><td colspan="7" class="text-center">Belum ada riwayat pemesanan.</td></tr>';
        } else {
            $no = 1;
            foreach ($arrayResult as $data) {
                echo '<tr>';
                echo '<td>' . $no . '</td>';
                echo '<td>' . $data->outlet_name . ' (' . $data->class_type . ')</td>';
                echo '<td>Meja ' . $data->table_number . '</td>';
                echo '<td>' . date('d M Y', strtotime($data->booking_date)) . ' | ' . $data->start_time . '</td>';
                echo '<td>' . $data->duration_hours . ' Jam</td>';
                echo '<td>Rp ' . number_format($data->total_price, 0, ',', '.') . '</td>';

                // Variasi Badge Status Pembayaran (Pola W13)
                if ($data->payment_status == 'Pending') {
                    echo '<td><span class="label label-warning">Pending</span></td>';
                } else if ($data->payment_status == 'Paid') {
                    echo '<td><span class="label label-success">Paid (Lunas)</span></td>';
                } else {
                    echo '<td><span class="label label-danger">Cancelled</span></td>';
                }
                echo '</tr>';
                $no++;
            }
        }
        ?>
    </tbody>
</table>

<div class="alert alert-info" style="margin-top: 30px;">
    <h5><span class="glyphicon glyphicon-info-sign"></span> <strong>Instruksi Pembayaran Afterhour:</strong></h5>
    <p>Jika status pemesanan Anda masih <strong>Pending</strong>, silakan lakukan penyelesaian pembayaran dengan salah
        satu metode berikut:</p>
    <ul>
        <li><strong>Metode 1 (Bayar di Tempat):</strong> Tunjukkan halaman riwayat ini ke kasir outlet Afterhour pilihan
            Anda saat kedatangan untuk divalidasi.</li>
        <li><strong>Metode 2 (Transfer Manual):</strong> Transfer sesuai nominal di atas ke rekening <strong>BCA
                123-456-7890 a.n Afterhour Group</strong>, lalu konfirmasikan ke WhatsApp Admin Outlet.</li>
    </ul>
    <p class="text-muted"><small>*Status akan berubah menjadi <strong>Paid</strong> setelah diverifikasi oleh
            Admin/Kasir di dashboard manajemen.</small></p>
</div>