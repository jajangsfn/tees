<?php

include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);  

//GET   = get all data user
//POST  = insert new data user
//PUT   = update data user
//DELETE= delete data user
if ($method == 'GET') {
    //get id jika ada
    $where = isset($input['id']) ? "WHERE id=".$input['id'] : "";
    
    $qry = $conn->query("SELECT * FROM user ".$where);

    //jika data ditemukan
    if ( $qry->num_rows > 0 ) {
        echo json_encode($qry->fetch_all(MYSQLI_ASSOC));
    }else {
        echo "Data tidak ditemukan";
    }

}else if ($method == 'POST') {
    
    //cek param
    if ( !isset($input['username']) ){
        echo "Username tidak boleh kosong";
    }else if ( !isset($input['password']) ){
        echo "Password tidak boleh kosong";
    }else if ( !isset($input['email']) ){
        echo "Email tidak boleh kosong";
    }else if ( !isset($input['id_member']) ){
        echo "ID Member tidak boleh kosong";
    }else {

        //check email
        $stmt = $conn->query('SELECT * FROM user  WHERE email = "'.trim($input['email']).'"');
        if ($stmt->num_rows > 0 ) {
            echo "Maaf email telah terdaftar";
        }else {

        
            // prepare and bind
            $stmt = $conn->prepare("INSERT INTO user (username, password, email, id_member, created_date, updated_date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt -> bind_param("ssssss", $username, $password, $email, $id_member, $created, $updated);

            // set parameters and execute
            $username    = trim($input['username']);
            $password    = password_hash( trim($input['password']), PASSWORD_DEFAULT);
            $email       = trim($input['email']);
            $id_member   = $input['id_member'];
            $created     = date('Y-m-d H:i:s');
            $updated     = date('Y-m-d H:i:s');
            $stmt->execute();
            echo "Data user berhasil disimpan";
        }
    }

}else if ($method == 'PUT') {

    //cek param
    if ( !isset($input['id']) ){
        echo "id tidak boleh kosong";
    //cek param
    }else if ( !isset($input['username']) ){
        echo "Username tidak boleh kosong";
    }else if ( !isset($input['password']) ){
        echo "Password tidak boleh kosong";
    }else if ( !isset($input['email']) ){
        echo "Email tidak boleh kosong";
    }else if ( !isset($input['id_member']) ){
        echo "ID Member tidak boleh kosong";
    }else {

        // prepare and bind
        $stmt = $conn->prepare("UPDATE user SET username = ?, password = ?, email = ?, id_member = ?, updated_date = ? WHERE id = ?");
        $stmt -> bind_param("ssssss", $username, $password, $email, $id_member, $updated, $id);

        // set parameters and execute
        $username    = $input['username'];
        $password    = password_hash( trim($input['password']), PASSWORD_DEFAULT);
        $email       = $input['email'];
        $id_member   = $input['id_member'];
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
        $qry = $conn->query("DELETE FROM user WHERE id = ".$id);
        echo "Data berhasil di delete";
    }
}else {
    echo "Method tidak di dukung!";
}


?>