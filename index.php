 <!DOCTYPE html>
 <html lang="id">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <title>Import Excel To Mysqli</title>
     <link rel="stylesheet" href="css/bootstrap.min.css">
 </head>

 <body>
     <?php
        if (isset($_GET['berhasil'])) {
            echo "<p>" . $_GET['berhasil'] . " Data berhasil di import.</p>";
        }
        $koneksi = mysqli_connect('localhost', 'root', '', 'iexcel');
        if (mysqli_connect_errno()) {
            echo "Koneksi database gagal : " . mysqli_connect_error();
        }   ?>
     <?php
        require 'vendor/autoload.php';

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Reader\Csv;
        use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

        $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if (isset($_FILES['fileimport']['name']) && in_array($_FILES['fileimport']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['fileimport']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['fileimport']['tmp_name']);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            for ($i = 1; $i < count($sheetData); $i++) {
                $nama = $sheetData[$i]['1'];
                $kelas = $sheetData[$i]['2'];
                $sekolah = $sheetData[$i]['3'];
                mysqli_query($koneksi, "insert into datasiswa (id,nama,kelas,sekolah) values ('','$nama','$kelas','$sekolah')");
            }
            header("Location: index.php");
        }
        ?>
     <div class="container-md p-2">
         <div class="card mb-3">
             <h1><a href="https://www.panduancode.com"><img src="img/iexcel.jpg" class="card-img-top" alt="Cara Import Excel ke Mysqli" title="Cara Import Excel ke Mysqli"></a></h1>
             <div class="card-body">
                 <p class="card-title"><b>Import Excel To Mysqli</b></p>
                 <fieldset>
                     <form method="post" enctype="multipart/form-data">
                         <div class="input-group mb-3">
                             <input type="file" name="fileimport" class="form-control" id="exampleInputFile">
                             <button type="submit" class="btn btn-primary ml-3">Import</button>
                         </div>
                     </form>
                 </fieldset>
                 <table class="table table-striped">
                     <thead>
                         <tr>
                             <th scope="col">#</th>
                             <th scope="col">Nama</th>
                             <th scope="col">Kelas</th>
                             <th scope="col">Sekolah</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                            $no = 1;
                            $data = mysqli_query($koneksi, "select * from datasiswa");
                            while ($d = mysqli_fetch_array($data)) {
                            ?>
                             <tr>
                                 <th><?php echo $no++; ?></th>
                                 <th><?php echo $d['nama']; ?></th>
                                 <th><?php echo $d['kelas']; ?></th>
                                 <th><?php echo $d['sekolah']; ?></th>
                             </tr>
                         <?php
                            }
                            ?>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 </body>

 </html>