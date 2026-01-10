<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

$data = [
    'title' => 'chỉnh sửa tài khoản'
];
Layout('header', $data);
Layout('sidebar');


$getData = filterData('get'); //lấy dữ liệu tù thanh địa chỉ url xuống
if(!empty($getData['id'])){ // nếu có dữ liệu từ id
    $user_id = $getData['id'];
    $detailUser = getOne("SELECT * FROM users WHERE id = $user_id ");
    if(empty($detailUser)){
        setSessionFlash('msg', 'người dùng không tồn tại.');
        setSessionFlash('msg_type', 'danger');
        redirect('?module=users&action=list');
    }
} else{
    setSessionFlash('msg', 'có lỗi xảy ra, vui lòng thử lại sau.');
    setSessionFlash('msg_type', 'danger');
    redirect('?module=users&action=list');
}



if (isPost()) {
    // lọc dữ liệu gọi lại hàm filterData ở fuctions.php gán lại cho biến $filter
    $filter = filterData();
    $err = []; // mảng giá trị đầu là rỗng


    // validate tên
    if (empty(trim($filter['fullname']))) { // key fullname ở form, và khoản trắng
        $err['fullname']['required'] = 'Họ tên không được để trống';
    } else {
        // đếm độ dài ký tự
        if (strlen(trim($filter['fullname'])) < 5) { //ktra gtri tên có lớn hơn 5 ko, bỏ khoảng trắng
            $err['fullname']['length'] = 'Họ tên phải lớn hơn 5 ký tự';
        }
    }


    // email thu đc ở filter nó mà khác vs cái email db $deltailUser thì validate nó
    if($filter['email'] != $detailUser['email']){
        // validate email
        if (empty(trim($filter['email']))) {
            $err['email']['required'] = 'email không để trống';
        } else {
            if (!validateEmail($filter['email'])) { // gọi lại hàm validateEmail ở bên fuc.php 
                $err['email']['isEmail'] = 'email không đúng định dạng';
            } else {
                $email = $filter['email']; // gán lai cho biến $email
    
                // ktra email có tồn tại trong users ko, dùng hàm getRows đếm ktra dòng email trong db 
                $checkEmail = getRows("SELECT * FROM users WHERE email = '$email' ");
                if ($checkEmail > 0) {
                    $err['email']['check'] = 'email đã tồn tại';
                }
            }
        }
    }

    // validate sdt
    if (empty($filter['phone'])) {
        $err['phone']['required'] = 'số điện thoại không được để trống';
    } else {
        if (!isPhone($filter['phone'])) { // gọi lại hàm isPhone ktra dlieu sdt
            $err['phone']['isPhone'] = 'số điện thoại không đúng định dạng';
        }
    }


    // validate pass
    if (!empty(trim($filter['password']))) { // nghĩa là password có dlieu sẽ validate cho ô pass
        if (strlen(trim($filter['password'])) < 6) {
            $err['password']['isPass'] = 'mật khẩu phải lớn 6';
        }
    } 

    if (empty($err)) {
        $dataUpdate = [ // ở đây lưu ý data này ko cập nhật pass nếu người dùng ko sửa pass
            'fullname' => $filter['fullname'],
            'email'    => $filter['email'],
            'phone'    => $filter['phone'],
            'group_id'    => $filter['group_id'],
            'status'   => $filter['status'],
            'address'  => (!empty($filter['address']) ? $filter['address'] : null),
            'updated_at' =>  date('Y:m:d H:i:s')
        ];


        if(!empty($filter['password']) ){ // nếu người dùng thay đổi mk thì mã hóa nó
            $dataUpdate['password'] = password_hash($filter['password'], PASSWORD_DEFAULT); // mã hóa mk
        }

        $condition = "id=". $user_id; // upadete the id
        $updateStatus = update('users', $dataUpdate, $condition);


        if($updateStatus) { // nếu sửa thành công báo
            setSessionFlash('msg', 'Sửa tài khoản thành công');
            setSessionFlash('msg_type', 'success');
            redirect('?module=users&action=list');
        }else{
            setSessionFlash('msg', 'Đã có lỗi xảy ra vui lòng thử lại sau.');
            setSessionFlash('msg_type', 'danger');
        }

    } else {
        setSessionFlash('msg', 'vui lòng kiểm tra dữ liệu nhập vào.'); // gọi hàm setSession bên session, lưu giá trị cũ
        setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session

        setSessionFlash('oldData', $filter); // gọi hàm setSession bên session, lưu giá trị cũ
        setSessionFlash('error', $err); // gọi hàm setSession bên session

    }

}
    $msg = getSessionFlash('msg');
    $msg_type = getSessionFlash('msg_type');
    $oldData = getSessionFlash('oldData'); // lấy giá trị cũ ng dùng nhập
    if(!empty($detailUser)){
        $oldData = $detailUser; // lấy dữ liệu cũ từ detailUsers gán lại oldata
    }
    $errorArray = getSessionFlash('error');

?>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid pb-3">
            <div class="container">
                <h2>chỉnh sửa người dùng</h2>
                <?php
                if (!empty($msg) && !empty($msg_type)) {
                    getMsg($msg, $msg_type);
                }
                ?>
                <form action="" method="post">
                    <div class="row">
                        <div class="col-6 pb-3">
                            <label for="fullname">Họ và Tên</label>
                            <input id="fullname" name="fullname" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'fullname');
                            }
                            ?>" placeholder="họ tên">
                            <?php
                            if (!empty($errorArray)) {
                                echo formErr($errorArray, 'fullname');
                            }
                            ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'email');
                            }
                            ?>" placeholder="Email">
                            <?php 
                            if(!empty($errorArray)){
                                echo formErr($errorArray, 'email');
                            }
                        ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'phone');
                            }
                            ?>" placeholder="số điện thoại">
                            <?php 
                            if(!empty($errorArray)){
                                echo formErr($errorArray, 'phone');
                            }
                        ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="password">Mật khẩu</label>
                            <input id="password" name="password" type="password" class="form-control"  placeholder="nhập mật khẩu">
                            <?php 
                            if(!empty($errorArray)){
                                echo formErr($errorArray, 'password');
                            }
                        ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="address">Địa chỉ</label>
                            <input id="address" name="address" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'address');
                            }
                            ?>" placeholder="nhập địa chỉ">
                            </label>
                        </div>
                        <div class="col-3 pb-3">
                            <label for="group">phân cấp người dùng</label>
                            <select name="group_id" id="group" class="form-select form-control">
                                <?php
                                $getGroup = getAll("SELECT * FROM `groups` ");
                                foreach ($getGroup as $item):
                                    ?>
                                    <option value="<?php echo $item['id']; ?>"
                                    <?php echo ($oldData['group_id'] == $item['id']) ? 'selected' : false; ?>>
                                    <?php echo $item['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-3 pb-3">
                            <label for="status">Trạng thái tài khoản</label>
                            <select name="status" id="status" class="form-select form-control">
                                <option value="0" <?php echo ($oldData['status'] == 0) ? 'selected' : false; ?>>chưa kích hoạt</option>
                                <option value="1" <?php echo ($oldData['status'] == 1) ? 'selected' : false; ?>>đã kích hoạt</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Lưu</button>
                    <a href="?module=users&action=list" class="btn btn-primary">Quay lại danh sách</a>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
Layout('footer');
?>