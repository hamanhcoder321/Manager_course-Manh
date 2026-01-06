<?php
if(!defined('_Manh')){
    die('truy cập không hợp lệ');
}

// set session
function setSession($key, $value){ // key là tên session, value là giá trị của session  
    if(!empty(session_id())){ // ktra phiên session này id là gì và trả về id đó nó dựa vào session_start ở index.php
        $_SESSION[$key] = $value;
        return true; // sau khi có giá trị session set cho nó là true   
    }
    return false; // còn ko có set cho nó false.
}

// hàm lấy giá trị session
function getSession($key = ''){ // tham số key là tên của session là gì
    if(empty($key)){
        return $_SESSION; // trường hợp nó rỗng
    }else{
        if(isset($_SESSION[$key])){
            return $_SESSION[$key]; // nếu có giá trị trả về giá trị $key
        }
    }
    return false;

}

// xóa session
function removeSession($key = ''){
    if(empty($key)){ 
        session_destroy(); // nếu key rỗng có thì xóa hết session
        return true; // trả về true
    }else{
        if(isset($_SESSION[$key])){ // nếu có key thì thực hiện xóa từng session
            unset($_SESSION[$key]); // unset xóa từng session
        }
    }
    return false;

}


// tạo session flash dùng 1 lần xong roi xóa
function setSessionFlash($key, $value){
    $key = $key . 'Flash'; // nối flash phân biệt key thường và key flash phục vụ xóa rõ hơn
    $rel = setSession($key, $value); // tạo biến rel lưu kq
    return $rel; // return biến $rel 
}

// lấy session flash 
function getSessionFlash($key){
    $key = $key . 'Flash'; // nối flash phân biệt key thường và key flash phục vụ xóa rõ hơn
    $rel = getSession($key);
    removeSession($key);
    return $rel;
}