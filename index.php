<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uasmobile";
$conn = new mysqli($servername, $username, $password,$dbname);
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
// Mengambil data barang
    $sql = "SELECT * FROM barang";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $barang = array();
        while ($row = $result->fetch_assoc()) {
            $barang[] = $row;
        }
        echo json_encode($barang);
    } else {
        echo "Data barang kosong.";
    }
}

if ($method === "POST") {
    // Menambahkan data barang
   $data = json_decode(file_get_contents("php://input"), true);
   $nama = $data["nama"];
   $jumlah = $data["jumlah"];
   $harga = $data["harga"];
   $sql = "INSERT INTO barang (nama, jumlah, harga) VALUES ('$nama', '$jumlah', '$harga')";
   if ($conn->query($sql) === TRUE) {
    $data['pesan'] = 'berhasil';
   //echo "Berhasil tambah data";
   } else {
    $data['pesan'] = "Error: " . $sql . "<br>" . $conn->error;
   }
   echo json_encode($data);
} 

if ($method === "PUT") {
    // Memperbarui data barang
   $data = json_decode(file_get_contents("php://input"), true);
   $id = $data["id"];
   $nama = $data["nama"];
   $jumlah = $data["jumlah"];
   $harga = $data["harga"];
   $sql = "UPDATE barang SET nama='$nama', jumlah='$jumlah', harga='$harga' WHERE id=$id";
   if ($conn->query($sql) === TRUE) {
    $data['pesan'] = 'data berhasil diubah';
   } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
   }
}

if ($method === "DELETE") {
    // Menghapus data barang
   $id = $_GET["id"];
   $sql = "DELETE FROM barang WHERE id=$id";
   if ($conn->query($sql) === TRUE) {
    $data['pesan'] = 'berhasil';
   } else {
    $data['pesan'] = "Error: " . $sql . "<br>" . $conn->error;
   }
    echo json_encode($data);
}

$conn->close();

?>