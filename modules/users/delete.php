<?php
if(!defined('_Manh')){
    echo 'truy cập ko hợp lệ'; 
}

$getData = filterData('get'); // lấy dlieu từ filterData

if(!empty($getData['id'])){ // nếu có id
    $user_id = $getData['id'];
    $checkUser = getRows("SELECT * FROM users WHERE id = $user_id"); // có dlieu từ id ko

    if($checkUser > 0) { // nếu checkusers lớn hơn 0 thì xóa
        // delete tk
        $checkToken = getRows("SELECT * FROM token_login WHERE user_id = $user_id");
        if($checkToken > 0){ // nếu checktoken lớn 0 thì có token 
            delete('token_login', "user_id = $user_id ");
        }
        // else{
        //     setSessionFlash('msg', 'người dùng không tồn tại.');
        //     setSessionFlash('msg_type', 'danger');
        //     redirect('?module=users&action=list');
        // }

        $checkDelete = delete('users', "id = $user_id"); // xóa trong bảng users
        if($checkDelete) { // nếu sửa thành công báo
            setSessionFlash('msg', 'xóa tài khoản thành công');
            setSessionFlash('msg_type', 'success');
            redirect('?module=users&action=list');
        }else{
            setSessionFlash('msg', 'Đã có lỗi xảy ra vui lòng thử lại sau.');
            setSessionFlash('msg_type', 'danger');
        }
    }else{ // ko có thì messages
        setSessionFlash('msg', 'người dùng không tồn tại.');
        setSessionFlash('msg_type', 'danger');
        redirect('?module=users&action=list');
    }
}

