<?php

include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);  

//GET   = get all data member
//POST  = insert new data member
//PUT   = update data member
//DELETE= delete data member
if ($method == 'GET') {
    //get id jika ada
    $where = isset($input['id']) ? "WHERE id=".$input['id'] : "";
    
    $qry = $conn->query("SELECT * FROM member ".$where);

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
    }else if ( !isset($input['jenkel']) ){
        echo "Jenis Kelamin tidak boleh kosong";
    }else if ( !isset($input['ttl']) ){
        echo "Tanggal Lahir tidak boleh kosong";
    }else if ( !isset($input['alamat']) ){
        echo "Alamat tidak boleh kosong";
    }else if ( !isset($input['telp']) ){
        echo "Nomor Handphone tidak boleh kosong";
    }else if ( !isset($input['username']) ){
        echo "Username tidak boleh kosong";
    }else if ( !isset($input['password']) ){
        echo "Password tidak boleh kosong";
    }else if ( !isset($input['email']) ){
        echo "Email tidak boleh kosong";
    }else {
        //check email
        $stmt = $conn->query('SELECT * FROM user  WHERE email = "'.trim($input['email']).'"');
        if ($stmt->num_rows > 0 ) {
            echo "Maaf email telah terdaftar";
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

            echo "Data berhasil disimpan";
        }
    }

}else if ($method == 'PUT') {

    //cek param
    if ( !isset($input['id']) ){
        echo "id tidak boleh kosong";
    //cek param
    }else if ( !isset($input['nama']) ){
        echo "Nama tidak boleh kosong";
    }else if ( !isset($input['jenkel']) ){
        echo "Jenis Kelamin tidak boleh kosong";
    }else if ( !isset($input['ttl']) ){
        echo "Tanggal Lahir tidak boleh kosong";
    }else if ( !isset($input['alamat']) ){
        echo "Alamat tidak boleh kosong";
    }else if ( !isset($input['telp']) ){
        echo "Nomor Handphone tidak boleh kosong";
    }else{

        //check email
        $stmt = $conn->query('SELECT * FROM user  WHERE email = "'.trim($input['email']).'"');
        if ($stmt->num_rows > 0 ) {
            echo "Maaf email telah terdaftar";
        }else {

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

            echo "Data berhasil diupdate";
        }
    }
}else if ($method == "DELETE"){

    if (!isset($input['id'])) {
        echo "id tidak boleh kosong";
    }else {
        $id  = $input['id'];
        $qry = $conn->query("DELETE FROM member WHERE id = ".$id);
        echo "Data berhasil di delete";
    }
}else {
    echo "Method tidak di dukung!";
}


?>