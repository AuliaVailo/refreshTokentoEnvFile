<?php
$tokens = '';

readToken();

runProcess();

function runProcess(){
    global $tokens;
    echo "Token Ditemukan\n";
    echo "Token : $tokens\n";

    echo "Sedang Proses\n";
    sleep(5);

    $tokenCheck = 'Misal Isinya Tidak Cocok'; // isi bagian ini dengan tulisan "Error grabbing Token" untuk simulasi token cocok

    echo "Mengecek Token\n";
    if($tokens != $tokenCheck){
        echo "Token Tidak Seusai, grab ulang TOken\n";
        grabToken();
    }else{
        echo "Token Sesuai\n";
    }
}

function readToken(){
    global $tokens;

    echo "Mengecek apakah file ada?\n";
    $log_filename = "./env/.env";
    if (!file_exists($log_filename)) 
    {
        echo "File tidak ada, laukan proses grab token\n";
        grabToken();
    }

    echo "Membaca file .env?\n";
    $env = file_get_contents("./env/.env");

    $tokens = $env;

    echo "Token berhasil dibaca, Token : $tokens\n";

    if($tokens === '0' || $tokens === ''){
        echo "Token Kosong, akan mengambil token, jalankan fungsi grabToken?\n";
        grabToken();
    }else{
        echo "Ok, Token sesuai..\n";
    }
}

function grabToken(){
    global $tokens;
    //ambil token dari endpoint
    echo "Membaca Endpoint getToken\n";
    $dataToken = getToken();

    if($dataToken != ''){
        echo "Memproses Endpoint getToken\n";
        $dataToken = json_decode($dataToken);
        echo "Token Ditemukan\n";
        $tokens = $dataToken->token_type.' '.$dataToken->access_token;
    }else{
        echo "Akses ke Endpoint Error\n";
        $tokens = 'Error grabbing Token';
    }

    echo "Status Token : $tokens\n";

    echo "Menyimpan Token di lokal\n";
    $log_filename = "env";
    if (!file_exists($log_filename)) 
    {
        echo "File tidak ditemukan, file akan dibuat\n";
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
        echo "Berhasil membuat folder\n";
    }

    $log_file_data = $log_filename.'/.env';
    file_put_contents($log_file_data, $tokens);
    echo "Token Berhasil disimpan di file lokal\n";
}

function getToken(){
    $ch     = curl_init();
    $url    = 'url api end point here';
    $data_string = 'grant_type=client_credentials';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/x-www-form-urlencoded',                                                                                
        'Authorization: fill your bassic auth')                                                                       
    );  

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    return $result;
}