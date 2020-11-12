<?php
include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);  

if ($method == "POST") {

    //cek param
    if ( !isset($input['username']) ){
        echo "Username tidak boleh kosong";
    }else if ( !isset($input['password']) ){
        echo "Password tidak boleh kosong";
    }else {

        $stmt = $conn->query("SELECT * FROM user WHERE username = '".trim($input['username'])."' OR email = '".trim($input['username']) ."'" );
        
        if ($stmt->num_rows > 0) {
            //password from db
            $password_from_db = $stmt->fetch_array(MYSQLI_ASSOC)['password'];
            //password from param
            $password   = trim($input['password']);

            //verifikasi password
            if (password_verify($password, $password_from_db))
            {
                echo "Berhasil Login";
            }else {
                echo "Password Salah";
            }
            
        }else {
            echo "Data tidak ditemukan";
        }
        
    }
}else {
    echo "Method tidak di dukung";
}
?>