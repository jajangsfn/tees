<?php

session_start();
include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);  

//cek status login
if (isset($_SESSION['is_login'])) {
    //GET   = get all data produk
    //POST  = insert new data produk
    //PUT   = update data produk
    //DELETE= delete data produk
    if ($method == 'GET') {
        //get id jika ada
        $where = isset($_GET['id']) ? "WHERE id=".$_GET['id'] : "";
        $order = "";
        
        if (isset($_GET['type'])) {
            if ($_GET['type'] == "promo") {
                $where .= ($where) ?  " AND promo is not null" : " WHERE promo is not null";
            }else if ($_GET['type'] == "most") {
                $order = " ORDER BY total_qty DESC";
            }else {
                $order = " ORDER BY total_qty ASC";
            }
        }
        
        $qry = $conn->query("SELECT t1.nama, t2.qty, t2.price,sum(ifnull(t2.qty,0)) total_qty FROM `produk` t1 
                            LEFT JOIN detail_transaksi t2 ON t2.id_produk=t1.id ".$where. " GROUP BY t1.id ".$order);

        //jika data ditemukan
        if ( $qry->num_rows > 0 ) {
            $_SESSION['result'] = TRUE;
            $_SESSION['message']= $qry->fetch_all(MYSQLI_ASSOC);
        }else {
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Data tidak ditemukan";
        }

    }else if ($method == 'POST') {
        
        //cek param
        if ( !isset($input['nama']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Nama Barang tidak boleh kosong";
        }else if ( !isset($input['id_kategori']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Kategori tidak boleh kosong";
        }else if ( !isset($input['qty']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Qty tidak boleh kosong";
        }else if ( !isset($input['price']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Harga tidak boleh kosong";
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

            $_SESSION['result'] = TRUE;
            $_SESSION['message']= "Data Berhasil disimpan";
        }

    }else if ($method == 'PUT') {

        //cek param
        if ( !isset($input['id']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID tidak boleh kosong";
        }else if ( !isset($input['nama']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Nama tidak boleh kosong";
        }else if ( !isset($input['id_kategori']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Kategori tidak boleh kosong";
        }else if ( !isset($input['qty']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Qty tidak boleh kosong";
        }else if ( !isset($input['price']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Harga tidak boleh kosong";
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

            $_SESSION['result'] = TRUE;
            $_SESSION['message']= "Data berhasil di update";
        }
    }else if ($method == "DELETE"){

        if (!isset($input['id'])) {
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID tidak boleh kosong";
        }else {
            $id  = $input['id'];
            $qry = $conn->query("DELETE FROM produk WHERE id = ".$id);

            $_SESSION['result'] = TRUE;
            $_SESSION['message']= "Data berhasil di delete";
        }
    }else {
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Method tidak didukung";
    }
}else {
    $_SESSION['result'] = FALSE;
    $_SESSION['message']= "not login";
}

echo json_encode($_SESSION);

?>