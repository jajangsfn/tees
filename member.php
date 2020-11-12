<?php
session_start();
include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);  

//GET   = get all data member
//POST  = insert new data member
//PUT   = update data member
//DELETE= delete data member
if ($method == 'GET') {
    //cek login untuk admin
    if (isset($_SESSION['is_login'])) {
        //get id jika ada
        $where  = isset($_GET['id']) ? "WHERE id=".$_GET['id'] : "";
        $order  = "";

        if (isset($_GET['type'])) {
            if ($_GET['type'] == "new") {
                $order = " ORDER BY t1.created_date DESC";
            }else if ($_GET['type'] == "most") {
                $order = " ORDER BY total_belanja DESC";
            }else {
                $order = " ORDER BY total_belanja ASC";
            }
        }

        $qry    = $conn->query("SELECT t1.*,ifnull(t2.total_belanja,0) total_belanja FROM `member` t1
                                LEFT JOIN (SELECT id_member,count(*) total_belanja FROM transaksi ".$where."
                                GROUP BY id_member) t2 ON t2.id_member = t1.id " . $order);

        //jika data ditemukan
        if ( $qry->num_rows > 0 ) {
            $_SESSION['result'] = TRUE;
            $_SESSION['message']   = $qry->fetch_all(MYSQLI_ASSOC);
        }else {
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Data tidak ditemukan";
        }
        
    }else {
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Anda Belum login";
    }

}else if ($method == 'POST') {
    
    //cek param
    if ( !isset($input['nama']) ){
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Nama tidak boleh kosong";
    }else if ( !isset($input['jenkel']) ){
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Jenis Kelamin tidak boleh kosong";
    }else if ( !isset($input['ttl']) ){
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Tanggal Lahir tidak boleh kosong";
    }else if ( !isset($input['alamat']) ){
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Alamat tidak boleh kosong";
    }else if ( !isset($input['telp']) ){
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Nomor Handphone tidak boleh kosong";
    }else if ( !isset($input['username']) ){
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Username tidak boleh kosong";
    }else if ( !isset($input['password']) ){
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Password tidak boleh kosong";
    }else if ( !isset($input['email']) ){
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Email tidak boleh kosong";
    }else {
        //check email
        $stmt = $conn->query('SELECT * FROM user  WHERE email = "'.trim($input['email']).'"');
        if ($stmt->num_rows > 0 ) {
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Maaf email telah terdaftar";
        }else {

            // prepare and bind
            $stmt = $conn->prepare("INSERT INTO member (nama, jenkel, ttl, alamat, telp, created_date, updated_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt -> bind_param("sssssss", $nama, $jenkel, $ttl, $alamat, $telp, $created, $updated);

            // set parameters and execute
            $nama        = $input['nama'];
            $jenkel      = $input['jenkel'];
            $ttl         = $input['ttl'];
            $alamat      = $input['alamat'];
            $telp        = $input['telp'];
            $created     = date('Y-m-d H:i:s');
            $updated     = date('Y-m-d H:i:s');
            $stmt->execute();

            //get last id member
            $id_member_insert = $stmt->insert_id;

            //insert into user
            // prepare and bind
            $stmt2 = $conn->prepare("INSERT INTO user (username, password, email, id_member, created_date, updated_date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2-> bind_param("ssssss", $username, $password, $email, $id_member, $created, $updated);

            // set parameters and execute
            $username    = trim($input['username']);
            $password    = password_hash( trim($input['password']), PASSWORD_DEFAULT);
            $email       = trim($input['email']);
            $id_member   = $id_member_insert;
            $created     = date('Y-m-d H:i:s');
            $updated     = date('Y-m-d H:i:s');
            $stmt2->execute();

            $_SESSION['result'] = TRUE;
            $_SESSION['message']= "Data berhasil disimpan";
        }
    }

}else if ($method == 'PUT') {
    //cek status login
    if (isset($_SESSION['is_login'])) {
        //cek param
        if ( !isset($input['id']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "id tidak boleh kosong";
        //cek param
        }else if ( !isset($input['nama']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Nama tidak boleh kosong";
        }else if ( !isset($input['jenkel']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Jenis Kelamin tidak boleh kosong";
        }else if ( !isset($input['ttl']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Tanggal Lahir tidak boleh kosong";
        }else if ( !isset($input['alamat']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Alamat tidak boleh kosong";
        }else if ( !isset($input['telp']) ){
            $_SESSION['result'] = FALSE;
            $_SESSION['message']= "Nomor Handphone tidak boleh kosong";
        }else{

            // prepare and bind
            $stmt = $conn->prepare("UPDATE member SET nama = ? , jenkel = ?, ttl = ?, alamat = ? , telp = ? , updated_date = ? WHERE id = ?");
            $stmt -> bind_param("sssssss", $nama, $jenkel, $ttl, $alamat, $telp, $updated, $id);

            // set parameters and execute
            $nama        = $input['nama'];
            $jenkel      = $input['jenkel'];
            $ttl         = $input['ttl'];
            $alamat      = $input['alamat'];
            $telp        = $input['telp'];
            $updated     = date('Y-m-d H:i:s');
            $id          = $input['id'];

            $stmt->execute();

            $_SESSION['result'] = TRUE;
            $_SESSION['message']= "Data berhasil diupdate";
        }
    }else {
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "Anda belum login";
    }
    
}else if ($method == "DELETE"){

    if (!isset($input['id'])) {
        $_SESSION['result'] = FALSE;
        $_SESSION['message']= "id tidak boleh kosong";
    }else {
        $id  = $input['id'];
        $qry = $conn->query("DELETE FROM member WHERE id = ".$id);

        $_SESSION['result'] = TRUE;
        $_SESSION['message']= "Data berhasil di delete";
    }
    
}else {
    $_SESSION['result'] = FALSE;
    $_SESSION['message']= "Method tidak di dukung!";    
}

echo json_encode($_SESSION);

?>