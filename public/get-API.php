<?PHP
header("Content-Type: application/json");

// Load konfigurasi database
$config = include __DIR__ . '/../config/api.php';

// Buat koneksi database
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$varjson = array();
$row_array = (object)array();

// Konfigurasi response JSON
$sql = "SELECT * FROM dokumen_hukums"; //query sql yang disesuaikan
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $slug = $row["slug"]; // slug diambil dari kolom slug di database
        $baseUrl = "http://jdih.bnpb.go.id"; // Ganti dengan domain aplikasi Anda

        $row_array->idData = $row["id"]; //berisi id dokumen
        $row_array->tahun_pengundangan = $row["tahun"]; //berisi tahun penetapan atau tahun terbit ex. 2019
        $row_array->tanggal_pengundangan = $row["tglPengundangan"]; //berisi tahun bulan tanggal (YYYY-MM-DD) ex. 2019-04-22
        $row_array->jenis = $row["jenisDok"]; //berisi jenis peraturan ex. Peraturan Daerah
        $row_array->noPeraturan = $row["nomor"]; //berisi no peraturan ex. 24
        $row_array->judul = $row["judul"]; //berisi judul ex. Peraturan Pemerintah No 1 Tahun 2019 Tentang Peraturan
        $row_array->noPanggil = $row["noPanggil"]; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
        $row_array->singkatanJenis = $row["singkatan"]; //berisi singkatan dari jenis ex. PERMEN/KEPMEN
        $row_array->tempatTerbit = $row["tempatPenetapan"]; //berisi tempat terbit
        $row_array->penerbit = $row["penerbit"]; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
        $row_array->deskripsiFisik = $row["deskripsiFisik"]; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
        $row_array->sumber = $row["sumber"]; //berisi sumber dokumen hukum, contoh PUU bersumber dari Berita Negara Tahun .... Nomor .....
        $row_array->subjek = $row["subjek"]; //berisi kata kunci dari dokumen hukum
        $row_array->isbn = $row["isbn"]; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
        $row_array->status = $row["status"]; //berisi status PUU ex.berlaku/tidak berlaku
        $row_array->bahasa = $row["bahasa"]; //berisi bahasa dari dokumen hukum tersebut
        $row_array->bidangHukum = $row["bidangHukum"]; //berisi pembidangan/pengkategorian isi dokumen hukum(opsional menyesuaikan kebutuhan instansi)
        $row_array->teuBadan = $row["teuBadan"]; //nama instansi terkait
        $row_array->nomorIndukBuku = $row["nomorIndukBuku"]; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
        $row_array->fileDownload = basename($row["lampiran"]); // Menghilangkan prefix file/ dan hanya mengambil nama file
        $row_array->urlDownload = $baseUrl . "/download-lampiran/" . $slug; // URL download lampiran
        $row_array->abstrak = $row["abstrak"];
        // Jika abstrak null, maka urlabstrak juga null
        if (is_null($row["abstrak"])) {
            $row_array->urlabstrak = null;
        } else {
            $row_array->urlabstrak = $baseUrl . "/download-abstrak/" . $slug; // URL download abstrak
        }
        $row_array->urlDetailPeraturan = $baseUrl . "/dokumen/" . $row["tipeDok"] . "/" . $row["slug"]; //berisi url halaman detail peraturan
        $row_array->operasi = "4"; //wajib ada
        $row_array->display = "1"; //wajib ada
        array_push($varjson, json_decode(json_encode($row_array)));
    }
    echo json_encode($varjson);
} else {
    echo "0 results";
}
$conn->close();
