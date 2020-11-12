<?php
session_start();
include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);  

if (isset($_SESSION['is_login'])) {
    if ($method == "GET") {
        //get id jika ada
        $where  = " WHERE flag is null AND id_member = ".$_SESSION['id_member']; 
        $where .= isset($input['id']) ? " amd id=".$input['id'] : "";
        $qry    = $conn->query("SELECT * FROM keranjang ".$where);

        //jika data ditemukan
        if ( $qry->num_rows > 0 ) {
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= $qry->fetch_all(MYSQLI_ASSOC);
        }else {
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Data tidak ditemukan";
        }

    }else if ($method == 'POST') {

        //cek param
        if ( !isset($input['id_produk']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Produk tidak boleh kosong";
        }else if ( !isset($input['id_member']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID Member tidak boleh kosong";
        }else if ( !isset($input['qty']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Qty tidak boleh kosong";
        }else if ( !isset($input['price']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Harga tidak boleh kosong";
        }else if ( !isset($input['total']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Total tidak boleh kosong";
        }else{
            //cek jika data yg dimasukkan banyak
            $count = is_array($input['id_produk']) ? count($input['id_produk']) : 1;
            
            if ( $count > 1) {
                $stmt = $conn->stmt_init();

                if ( $stmt->prepare("INSERT INTO keranjang (id_produk, id_member, qty, price, percent_discount, total, created_date, updated_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)") ){
                    
                    // prepare and bind
                    $stmt -> bind_param("ssssssss", $id_produk, $id_member, $qty, $price, $percent, $total, $created, $updated);

                    for ($i=0; $i < $count ; $i++) {     
                        // set parameters and execute
                        $id_produk   = $input['id_produk'][$i];
                        $id_member   = $input['id_member'];
                        $qty         = $input['qty'][$i];
                        $price       = $input['price'][$i];
                        $percent     = isset($input['percent'][$i]) ? $input['percent'][$i]: 0;
                        $total       = $input['total'][$i];
                        $created     = date('Y-m-d H:i:s');
                        $updated     = date('Y-m-d H:i:s');
                        $stmt->execute();
        
                    }
                }
                
                $_SESSION['result'] = FALSE;
                $_SESSION['message']= "Data berhasil disimpan";

            }else {
                
                // prepare and bind
                $stmt = $conn->prepare("INSERT INTO keranjang (id_produk, id_member, qty, price, percent_discount, total, created_date, updated_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt -> bind_param("ssssssss", $id_produk, $id_member, $qty, $price, $percent, $total, $created, $updated);

                // set parameters and execute
                $id_produk   = $input['id_produk'];
                $id_member   = $input['id_member'];
                $qty         = $input['qty'];
                $price       = $input['price'];
                $percent     = isset($input['percent']) ? $input['percent'] : 0;
                $total       = $input['total'];
                $created     = date('Y-m-d H:i:s');
                $updated     = date('Y-m-d H:i:s');
                $stmt->execute();

                $_SESSION['result'] = FALSE;
                $_SESSION['message']= "Data berhasil disimpan";
            }
        }
    }else if ($method == 'PUT') {
        //cek param
        if ( !isset($input['id']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID tidak boleh kosong";
        }else if ( !isset($input['id_produk']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Produk tidak boleh kosong";
        }else if ( !isset($input['id_member']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "ID Member tidak boleh kosong";
        }else if ( !isset($input['qty']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Qty tidak boleh kosong";
        }else if ( !isset($input['price']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Harga tidak boleh kosong";
        }else{
            // prepare and bind
            $stmt = $conn->prepare("UPDATE keranjang SET id_produk = ?, id_member = ?, qty = ?, price = ?, percent_discount = ?, total = ?, updated_date = ?  WHERE id = ?");
            $stmt -> bind_param("ssssssss", $id_produk, $id_member, $qty, $price, $percent, $total, $updated, $id);

            // set parameters and execute
            $id_produk   = $input['id_produk'];
            $id_member   = $input['id_member'];
            $qty         = $input['qty'];
            $price       = $input['price'];
            $percent     = isset($input['percent']) ? $input['percent'] : 0;
            $total       = $input['total'];
            $updated     = date('Y-m-d H:i:s');
            $id          = $input['id'];
            $stmt->execute();

            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Data berhasil diupdate";

        }

    }else if ($method == "DELETE"){

        if (!isset($input['id'])) {
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "id tidak boleh kosong";
        }else {
            $id  = $input['id'];
            $qry = $conn->query("DELETE FROM keranjang WHERE id = ".$id);

            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Data berhasil di delete";
        }
    }else {
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Method tidak di dukung!";
    }
}else {
    $_SESSION['result'] = FALSE;
    $_SESSION['message']= "Anda belum login";    
}

echo json_encode($_SESSION);
?>