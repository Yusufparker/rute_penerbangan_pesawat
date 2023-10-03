<?php
session_start();
$dataRute = file_get_contents('data/data_rute.json');
$dataArray = json_decode($dataRute, true);



// jika tombol hapus
if (isset($_POST['submit_hapus'])) {
    $index_hapus = $_POST['hapus'];

    // menghapus array sesuai index
    unset($dataArray[$index_hapus]);

    // reindex array
    $dataArray = array_values(($dataArray));

    // ubah array yang sudah dihapus menjadi JSON
    $dataUpdated = json_encode($dataArray, JSON_PRETTY_PRINT);
    file_put_contents('data/data_rute.json', $dataUpdated); //menyimpan data je file data_rute.json


}


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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="gambar/icon.jpg" type="image/x-icon">
    <title>Rute Penerbangan</title>
</head>

<body>
    <div class="pt-3 bg-darkGrey  position-relative header">
        <div class="container  pt-2 pb-3 text-white d-flex">
            <h1 class="fs-3 fw-bold">Daftar Rute Penerbangan</h1><i class="bi bi-airplane-fill ms-2" style="color: #FFD523;"></i>
        </div>
        <img src="gambar/pesawat.png" alt="" class="position-absolute">
    </div>
    <a href="add_rute.php" class="text-decoration-none bg-darkGrey  d-block btn-add text-white rounded-circle mb-3 mt-3 ms-auto btn-add">+</a>

    <div class="container mt-4 table-maskapai overflow-x-scroll pb-3">

        <!-- mengambil pesan yang dikirim ke session saat menambah data (jika ada) -->
        <?php
        if (isset($_SESSION['pesan'])) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['pesan'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
            // hapus sesi pesan
            unset($_SESSION['pesan']);
        }
        ?>

        <table class="table  table-striped display" id="myTable">
            <thead>
                <tr class="table-warning">
                    <th scope="col">Maskapai</th>
                    <th scope="col">Asal Penerbangan</th>
                    <th scope="col">Tujuan Penerbangan</th>
                    <th scope="col">Harga Tiket</th>
                    <th scope="col">Pajak</th>
                    <th scope="col">Total Harga Tiket</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- melooping data pada dataArray yang diambil dari file json yaitu data_rute.json -->
                <?php foreach ($dataArray as $index => $data) { ?>
                    <tr>
                        <!-- maskapai -->
                        <td><?= $data[0] ?></td>
                        <!-- bandara asal -->
                        <td><?= $data[1] ?></td>
                        <!-- bandatra tujuan -->
                        <td><?= $data[2] ?></td>
                        <!-- harga tiket -->
                        <td>Rp. <?= $data[3] ?></td>
                        <!-- pajak -->
                        <td>Rp. <?= $data[4] ?></td>
                        <!-- total tiket -->
                        <td>Rp. <?= $data[5] ?></td>
                        <td>
                            <!-- form untuk hapus data rute -->
                            <form action="" method="post" class="d-inline">
                                <input type="hidden" name="hapus" value="<?= $index ?>">
                                <button type="submit" name="submit_hapus" class="btn btn-primary text-white  p-1 bg-danger me-2 border-0 rounded-0" style="font-size: 12px;"><i class="bi bi-trash3"></i></button>
                            </form>
                            <a href="edit_rute.php?i=<?= $index ?>" class="text-white  p-1 bg-warning"><i class="bi bi-pencil-square"></i></a>
                        </td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>
    </div>



    <script src="library/bootstrap/js/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "lengthMenu": [5, 10, 25, 50],
                "pageLength": 10,
            });

        });
    </script>

</body>

</html>