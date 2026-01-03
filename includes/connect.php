<?php
if(!defined('_Manh')){
    echo 'truy cập ko hợp lệ'; 
}

try {
    if (class_exists('PDO')) { // kiểm tra pdo tồn tại hay ko trc khi sử dụng
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", // hỗ trợ tiếng việt
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // đẩy lỗi vào ngoại lệ
        );
        // tạo biến $conn + $dsn tạo mới lớp PDO gọi lại hằng số kết nối db đã tạo ở file config để kết nối 
        $dsn = _DRIVER . ':host='._HOST."; dbname=". _DATABASE;
        
        $conn = new PDO($dsn, _USER, _PASSWORD, $options);
        // thêm biến $options để hỗ trợ thêm tiếng việt và xử lý lỗi

    }
} catch (Exception $ex) { // truyền lớp và tạo biến $ex 
// có lỗi hiển thị lỗi
    echo 'lỗi kết nối: ' . $ex->getMessage();
}