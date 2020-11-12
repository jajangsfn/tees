<?php
session_start();

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
            $data_from_db     = $stmt->fetch_array(MYSQLI_ASSOC);
            $password_from_db = $data_from_db['password'];
            //password from param
            $password   = trim($input['password']);

            //verifikasi password
            if (password_verify($password, $password_from_db))
            {
                $_SESSION['username'] = trim($input['username']);
                $_SESSION['is_login'] = TRUE;
                $_SESSION['id_member']= $data_from_db['id_member'];
                $_SESSION['result']   = TRUE;
                $_SESSION['message']  = "Berhasil Login";

                echo json_encode($_SESSION);

            }else {
                $_SESSION['is_login'] = FALSE;
                $_SESSION['result']   = FALSE;
                $_SESSION['message']  = "Password Salah";

                echo json_encode($_SESSION);
            }
            
        }else {
            $_SESSION['is_login'] = FALSE;
            $_SESSION['result']   = FALSE;
            $_SESSION['message']  = "Data tidak ditemukan";

            echo json_encode($_SESSION);
        }
        
    }
}else {
    $_SESSION['is_login'] = FALSE;
    $_SESSION['result']   = FALSE;
    $_SESSION['message']  = "Method tidak didukung";

    echo json_encode($_SESSION);
}


?>