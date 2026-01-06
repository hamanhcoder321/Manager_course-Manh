<?php
if(!defined('_Manh')){
    echo 'truy cập ko hợp lệ'; 
}


// hàm truy vấn dữ liệu nhiều dòng
function getAll($sql) { // truyền tham số $sql để truy vấn dữ liệu 

    // khai báo global toàn cục cho $connect vì biến connect ở config
    global $conn;
    
    // sau khi nhận được câu lệnh sql cho vào prepare cb sql 
    $stm = $conn -> prepare($sql);

    $stm -> execute(); // sau khi cb sql xong nhận data cho vào excute thực thi

    $result = $stm -> fetchAll(PDO::FETCH_ASSOC); // fetch truy vấn tất cả dlieu

    // sau khi lọc dữ liệu xong return biến result
    return $result;
}

function getRows($sql){ // hàm getRows nó trả về số lượng dòng dlieu
    global $conn;
    // sau khi nhận được câu lệnh sql cho vào prepare cb sql 
    $stm = $conn -> prepare($sql);

    $stm -> execute(); // sau khi cb sql xong nhận data cho vào excute thực thi

    $rel = $stm -> rowCount(); // hàm rowCount đếm số dòng dlieu

    // sau khi lọc dữ liệu xong return biến result
    return $rel;
}

// hàm truy vấn 1 dòng
function getOne($sql){
    // khai báo global toàn cục cho $connect vì biến connect ở config
    global $conn;
    
    // sau khi nhận được câu lệnh sql cho vào prepare cb sql 
    $stm = $conn -> prepare($sql);

    $stm -> execute(); // sau khi cb sql xong nhận data cho vào excute thực thi

    $result = $stm -> fetch(PDO::FETCH_ASSOC); // truy vấn fecth lấy từng dữ liệu array kết hợp key

    // sau khi lọc dữ liệu xong return biến result
    return $result;

}

// hàm thêm dlieu vào
function insert($table, $data) {
    // khai báo global toàn cục cho $connect vì biến connect ở config
    global $conn;

    $keys = array_keys($data);  // in ra các keys dlieu

    // name,slug
    $cot = implode(',', $keys); // implode mảng thành chuỗi

    // :name,:slug
    $place = ':' . implode(',:', $keys);
    
    $sql = "INSERT INTO $table ($cot) VALUES ($place)"; //:$cot --> 2 chấm gọi là placehoder các trường thêm
    

    $stm = $conn->prepare($sql); // sql injection 
    // thực thi câu lệnh 
    $rel = $stm->execute($data);

    return $rel;
}

// update dlieu
function update($table, $data, $condition = ''){ // condition điều kiện câp nhật theo bảng nào
    global $conn;
    // tạo biến giá trị key
    $update = '';

    foreach ($data as $key => $item){
        $update .= $key . '=:' .$key . ',';// dấu bằng ở $key '=:' nghĩa là nối giá trị ở bên dưới $sql
    }

    $update = trim($update, ',');

    // ktra theo điều kiện update tất cả hoặc theo id
    if(!empty($condition)){
        $sql = "UPDATE $table SET $update where $condition";

    }else{
        $sql = "UPDATE $table SET $update ";
    }

    // cb sql
    $tmp = $conn -> prepare($sql);

    // thực thi sql
    $tmp -> execute($data);

} 

// hàm delete dlieu
function delete($table, $condition = '') {
    global $conn;
    if(!empty($condition)){
        $sql = "DELETE FROM $table where $condition ";
    }else{
        $sql = "DELETE FROM $table";
    }
    $stm = $conn -> prepare($sql);
    $stm -> execute();
}

// hàm getlastid lấy id dlieu mới id insert
function lastId () {
    global $conn;
    return $conn -> lastInsertId();
}