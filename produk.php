<?php

include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);  

//GET   = get all data produk
//POST  = insert new data produk
//PUT   = update data produk
//DELETE= delete data produk
if ($method == 'GET') {
    //get id jika ada
    $where = isset($input['id']) ? "WHERE id=".$input['id'] : "";
    
    $qry = $conn->query("SELECT * FROM produk ".$where);

    //jika data ditemukan
    if ( $qry->num_rows > 0 ) {
        echo json_encode($qry->fetch_all(MYSQLI_ASSOC));
    }else {
        echo "Data tidak ditemukan";
    }

}else if ($method == 'POST') {
    
    //cek param
    if ( !isset($input['nama']) ){
        echo "Nama tidak boleh kosong";
    }else if ( !isset($input['id_kategori']) ){
        echo "Kategori tidak boleh kosong";
    }else if ( !isset($input['qty']) ){
        echo "Qty tidak boleh kosong";
    }else if ( !isset($input['price']) ){
        echo "Harga tidak boleh kosong";
    }else {

        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO produk (nama, id_kategori, qty, price,percent_discount, created_date, updated_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt -> bind_param("sssssss", $nama, $id_kategori, $qty, $price, $percent, $created, $updated);

        // set parameters and execute
        $nama        = $input['nama'];
        $id_kategori = $input['id_kategori'];
        $qty         = $input['qty'];
        $price       = $input['price'];
        $percent     = isset($input['percent']) ? $input['percent'] : 0;
        $created     = date('Y-m-d H:i:s');
        $updated     = date('Y-m-d H:i:s');
        $stmt->execute();
        echo "Data berhasil disimpan";
    }

}else if ($method == 'PUT') {

    //cek param
    if ( !isset($input['id']) ){
        echo "id tidak boleh kosong";
    }else if ( !isset($input['nama']) ){
        echo "Nama tidak boleh kosong";
    }else if ( !isset($input['id_kategori']) ){
        echo "Kategori tidak boleh kosong";
    }else if ( !isset($input['qty']) ){
        echo "Qty tidak boleh kosong";
    }else if ( !isset($input['price']) ){
        echo "Harga tidak boleh kosong";
    }else {

        // prepare and bind
        $stmt = $conn->prepare("UPDATE produk SET nama = ?, id_kategori = ?, qty = ?, price = ?, percent_discount = ?, updated_date = ? WHERE id = ?");
        $stmt -> bind_param("sssssss", $nama, $id_kategori, $qty, $price, $percent, $updated, $id);

        // set parameters and execute
        $nama        = $input['nama'];
        $id_kategori = $input['id_kategori'];
        $qty         = $input['qty'];
        $price       = $input['price'];
        $percent     = isset($input['percent']) ? $input['percent'] : 0;
        $updated     = date('Y-m-d H:i:s');
        $id          = $input['id'];

        $stmt->execute();

        echo "Data berhasil diupdate";
    }
}else if ($method == "DELETE"){

    if (!isset($input['id'])) {
        echo "id tidak boleh kosong";
    }else {
        $id  = $input['id'];
        $qry = $conn->query("DELETE FROM produk WHERE id = ".$id);
        echo "Data berhasil di delete";
    }
}else {
    echo "Method tidak di dukung!";
}


?>