<?php
if (!defined('_Manh')) {
    echo 'truy cập không hợp lệ';
}
$data = [
    'title' => 'Đăng xuất tài khoản'
];


if(isLogin()){ // ktra đăng nhập nào ko
    $token = getSession('token_login'); // có token
    $removeToken = delete('token_login', "token = '$token' ");

    if($removeToken){ // nếu xóa thành success
        removeSession('token_login'); // sau khi xóa token roi thì xóa session để tạo session mới khi login
        redirect('?module=auth&action=login');
    }else{ // nếu xóa err thì báo bug
        setSessionFlash('msg', 'Lỗi hệ thống, xin vui lòng thử lại sau.');
        setSessionFlash('msg_type', 'danger');
    }

}else{
    setSessionFlash('msg', 'Lỗi hệ thống, xin vui lòng thử lại sau.');
    setSessionFlash('msg_type', 'danger');
}