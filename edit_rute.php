<?php
// mulai ses
session_start();

// ambil data dari file json
$dataAsal = file_get_contents('data/data_bandara_asal.json');
$dataAsal = json_decode($dataAsal, true);
$dataTujuan = file_get_contents('data/data_bandara_tujuan.json');
$dataTujuan = json_decode($dataTujuan, true);
$dataRute = file_get_contents('data/data_rute.json');
$dataRute = json_decode($dataRute, true);


$index = $_GET['i'];


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="library/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="gambar/icon.jpg" type="image/x-icon">
    <title>Rute Penerbangan</title>
</head>

<body>
    <div class="pt-3 bg-darkGrey position-relative header">
        <div class="container  pt-2 pb-3 text-white d-flex">
            <a href="index.php" class="text-white"><i class="bi bi-arrow-left me-3"></i></a>
            <h1 class="fs-3 fw-bold">Edit Data Rute Penerbangan</h1> <i class="bi bi-airplane-fill ms-2" style="color: #FFD523;"></i>
        </div>
        <img src="gambar/pesawat.png" alt="" class="position-absolute">
    </div>

    <div class="container mt-4 form-add">
        <form class="col-md-8" method="post" action="">
            <!-- input nama maskapai -->
            <div class="mb-4">
                <input type="text" placeholder="Nama Maskapai" name="nama_maskapai" value="<?= $dataRute[$index][0] ?>" required>
            </div>


            <!-- bandara asal -->
            <div class="mb-4">
                <select class="form-select form-select" aria-label="Small select example" required name="bandara_asal">
                    <option value="" hidden>Bandara Asal</option>
                    <?php foreach ($dataAsal as $data) { ?>
                        <option value="<?= $data['id'] ?>" <?= $data['nama_bandara'] == $dataRute[$index][1] ? 'selected' : '' ?>><?= $data['nama_bandara'] ?></option>

                    <?php } ?>
                </select>
            </div>

            <!-- bandara tujuan -->
            <div class="mb-4">
                <select class="form-select form-select" aria-label="Small select example" required name="bandara_tujuan">
                    <option value="" hidden>Bandara Tujuan</option>
                    <?php foreach ($dataTujuan as $data) { ?>
                        <option value="<?= $data['id'] ?>" <?= $data['nama_bandara'] == $dataRute[$index][2] ? 'selected' : '' ?>><?= $data['nama_bandara'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-4">
                <input type="number" name="harga_tiket" placeholder="Harga Tiket" required value="<?= $dataRute[$index][3] ?>">
            </div>

            <button type="submit" class="btn bg-darkGreytext-white btn-primary" name="btn_submit">Submit</button>
        </form>
    </div>



    <script src="library/bootstrap/js/bootstrap.js"></script>
</body>

</html>
<?php


// mengambil index data bandara sesuai id
function getBandara($id, $data)
{
    $data_bandara = null;
    foreach ($data as $bandara) {
        if ($bandara['id'] == $id) {
            $data_bandara = $bandara;
            break;
        }
    }
    return $data_bandara;
}

// hitung pajak
function hitungPajak($pajakAsal, $pajakTujuan)
{
    $jumlah = $pajakAsal + $pajakTujuan;
    return $jumlah;
}

// Fungsi pembanding untuk mengurutkan berdasarkan maskapai (index 0)
function compare($a, $b)
{
    return strcmp(
        $a[0],
        $b[0]
    );
}


function urutkanData(&$data)
{
    usort($data, 'compare');
}



if (isset($_POST['btn_submit'])) {
    // nama maskapai
    $nama_maskapai = ucwords($_POST['nama_maskapai']);
    // harga tiket
    $harga_tiket = $_POST['harga_tiket'];

    $id_bandara_asal = $_POST['bandara_asal'];
    $id_bandara_tujuan = $_POST['bandara_tujuan'];

    $data_bandara_asal = getBandara($id_bandara_asal, $dataAsal);
    $data_bandara_tujuan = getBandara($id_bandara_tujuan, $dataTujuan);

    $pajak_asal = $data_bandara_asal['pajak'];
    $pajak_tujuan = $data_bandara_tujuan['pajak'];

    // nama bandara asal
    $nama_bandara_asal = $data_bandara_asal['nama_bandara'];

    // nama bandara tjuan
    $nama_bandara_tujuan = $data_bandara_tujuan['nama_bandara'];

    // total pajak
    $total_pajak = hitungPajak($pajak_asal, $pajak_tujuan);

    //total harga tiket
    $total_harga_tiket = $harga_tiket + $total_pajak;

    $data_baru = [
        $nama_maskapai,
        $nama_bandara_asal,
        $nama_bandara_tujuan,
        $harga_tiket,
        $total_pajak,
        $total_harga_tiket

    ];

    // edit data 
    $dataRute[$index] = $data_baru;




    // sorting data
    urutkanData($dataRute);

    // ubah data ke json
    $data_rute_updated = json_encode($dataRute, JSON_PRETTY_PRINT);

    // // Simpan kembali JSON ke dalam file
    file_put_contents('data/data_rute.json', $data_rute_updated);




    // Set pesan dalam session
    $_SESSION['pesan'] = 'Edit Data Berhasil!';

    // Redirect ke halaman awal
    header('Location: index.php');
    exit;
}

?>