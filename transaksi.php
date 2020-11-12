<?php
session_start();
include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);  

if (isset($_SESSION['is_login'])) {

    if ($method == "GET") {
        
        if (!isset($_SESSION['id_member'])) {
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID Member tidak boleh kosong";
        }else {

            $where = "WHERE t1.id_member = ".$_SESSION['id_member'];
            // type data
            // all tampilkan semua data
            // tampilkan data sesuai id member
            if (isset($_GET['type'])) {
                if ($_GET['type'] == "all") {
                    $where = "";
                }
            }
            
            $qry = $conn->query("SELECT t1.total,t1.id_member,t3.nama nama_produk,
                                     t2.qty,t2.price,t2.percent_discount,t2.total total_per_produk 
                                    FROM transaksi t1 
                                    JOIN detail_transaksi t2 ON t2.id_transaksi=t1.id
                                    JOIN produk t3 ON t3.id=t2.id_produk
                                    ".$where);

            $_SESSION['result'] = TRUE;
            $_SESSION['message']= $qry->fetch_all(MYSQLI_ASSOC);
        }
    }else if ($method == "POST") {
        
        //cek param
        if ( !isset($input['id_keranjang']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID Keranjang tidak boleh kosong";
        }else if ( !isset($input['id_member']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID Member tidak boleh kosong";
        }else if ( !isset($input['id_produk']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID Produk tidak boleh kosong";
        }else if ( !isset($input['qty']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Qty tidak boleh kosong";
        }else if ( !isset($input['price']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Harga tidak boleh kosong";
        }else if ( !isset($input['total']) ){
            
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Total tidak boleh kosong";
        }else {
            
            $count = is_array($input['id_keranjang']) ? count($input['id_keranjang']) : 1;

            if ($count > 1) {
                $total = 0;

                for($i = 0; $i < $count; $i++) {
                    $total+= intval($input['total'][$i]);
                }
                //insert transaksi
                $stmt = $conn->prepare("INSERT INTO transaksi (id_member,total, created_date, updated_date) VALUES (?, ?, ?, ?)");

                $stmt->bind_param("ssss", $id_member, $total, $created, $updated);
                //param
                $id_member    = $input['id_member'];
                $total        = $total;
                $created      = date('Y-m-d H:i:s');
                $updated      = date('Y-m-d H:i:s');
                $stmt->execute();

                //id transaksi
                $id_transaksi_from_db = $stmt->insert_id;

                $stmt2 = $conn->stmt_init();

                if ( $stmt2->prepare("INSERT INTO detail_transaksi (id_transaksi,id_produk, qty, price, percent_discount, total, created_date, updated_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)") ){
                    
                    // prepare and bind
                    $stmt2->bind_param("ssssssss", $id_transaksi, $id_produk, $qty, $price, $percent, $total, $created, $updated);

                    for ($i=0; $i < $count ; $i++) { 

                        // set parameters and execute
                        $id_transaksi = $id_transaksi_from_db;
                        $id_produk    = $input['id_produk'][$i];
                        $qty          = $input['qty'][$i];
                        $price        = $input['price'][$i];
                        $percent      = isset($input['percent'][$i]) ? $input['percent'][$i] : 0;
                        $total        = $input['total'][$i];
                        $created      = date('Y-m-d H:i:s');
                        $updated      = date('Y-m-d H:i:s');
                        $stmt2->execute();

                        //update status keranjang
                        $stmt3 = $conn->prepare("UPDATE keranjang SET flag = 1 WHERE id = ?");
                        $stmt3->bind_param("s", $id_keranjang);
                        //param
                        $id_keranjang = $input['id_keranjang'][$i];
                        $stmt3->execute();

                        //update stok barang
                        $stmt4 = $conn->prepare("UPDATE produk SET qty = qty - ? WHERE id = ?");
                        $stmt4->bind_param("is", $qty, $id_produk);

                        $qty          = $input['qty'][$i];
                        $id_produk    = $input['id_produk'][$i];

                        $stmt4->execute();
        
                    }
                }
                
                $_SESSION['result'] = FALSE;
                $_SESSION['message']= "Data berhasil disimpan";
            }else {

                //update status keranjang
                $stmt = $conn->prepare("UPDATE keranjang SET flag = 1 WHERE id = ?");
                $stmt->bind_param("s", $id_keranjang);
                //param
                $id_keranjang = $input['id_keranjang'];
                $stmt->execute();

                //insert ke transaksi
                $stmt2 = $conn->prepare("INSERT INTO transaksi (id_member,total, created_date, updated_date) VALUES (?, ?, ?, ?)");
                $stmt2->bind_param("ssss", $id_member, $total, $created, $updated);
                //param
                $id_member    = $input['id_member'];
                $total        = $input['total'];
                $created      = date('Y-m-d H:i:s');
                $updated      = date('Y-m-d H:i:s');
                $stmt2->execute();

                $id_transaksi_from_db = $stmt2->insert_id;
                
                //insert detail transaksi
                $stmt3 = $conn->prepare("INSERT INTO detail_transaksi (id_transaksi,id_produk, qty, price, percent_discount, total, created_date, updated_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt3->bind_param("ssssssss", $id_transaksi, $id_produk, $qty, $price, $percent, $total, $created, $updated);
                //param
                $id_transaksi = $id_transaksi_from_db;
                $id_produk    = $input['id_produk'];
                $qty          = $input['qty'];
                $price        = $input['price'];
                $percent      = isset($input['percent']) ? $input['percent'] : 0;
                $total        = $input['total'];
                $created      = date('Y-m-d H:i:s');
                $updated      = date('Y-m-d H:i:s');
                $stmt3->execute();

                // update stok produk
                $stmt4 = $conn->prepare("UPDATE produk SET qty = qty - ? WHERE id = ?");
                $stmt4->bind_param("is", $qty, $id_produk);

                $qty          = $input['qty'];
                $id_produk    = $input['id_produk'];

                $stmt4->execute();

                $_SESSION['result'] = TRUE;
                $_SESSION['message']= "Data transaksi berhasil disimpan";
            }
        }
    }else {
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Method tidak didukung";
    }


} else {
    $_SESSION['result'] = FALSE;
    $_SESSION['message']= "Anda belum login";
}
echo json_encode($_SESSION);