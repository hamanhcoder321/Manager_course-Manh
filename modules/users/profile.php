<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

$data = [
    'title' => 'Thông tin tài khoản'
];
Layout('header', $data);
Layout('sidebar');


// lấy thông tin user dựa vào session token_login
$token = getSession('token_login'); // lấy token
if (!empty($token)) {
    $checkTokenLogin = getOne("SELECT * FROM token_login WHERE token='$token'");
    if (!empty($checkTokenLogin)) {
        $user_id = $checkTokenLogin['user_id']; // lấy id user
        $detailUser = getOne("SELECT * FROM users WHERE id=$user_id"); // lấy tất cả dlieu users
    }
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
            'address'  => (!empty($filter['address']) ? $filter['address'] : null),
            'updated_at' =>  date('Y:m:d H:i:s')
        ];

        // người dùng có nhập vào file thumb thì mới cập nhật lại
        if (!empty($_FILES['avartar']['name'])) {
            // xử lý avartar upload lên
            $uploadDir = './templates/uploads/';

            if (!file_exists($uploadDir)) { // tạo file lưu trữ nếu chưa có
                mkdir($uploadDir, 0777, true); // tạo file
            }

            $fileName = basename($_FILES['avartar']['name']);

            // đích đến file
            $targetFile = $uploadDir . time() . '-' . $fileName;

            $thumb = '';

            // xử lý lấy file đã tạo vào uploads
            $checkMove = move_uploaded_file($_FILES['avartar']['tmp_name'], $targetFile);
            $targetFile = ltrim($targetFile, '.' );
            if ($checkMove) {
                $thumb = $targetFile;
            }

            $dataUpdate['avartar'] = $thumb;

        }


        if(!empty($filter['password']) ){ // nếu người dùng thay đổi mk thì mã hóa nó
            $dataUpdate['password'] = password_hash($filter['password'], PASSWORD_DEFAULT); // mã hóa mk
        }

        $condition = "id=". $user_id; // upadete the id
        $updateStatus = update('users', $dataUpdate, $condition);


        if($updateStatus) { // nếu sửa thành công báo
            setSessionFlash('msg', 'Sửa thông tin tài khoản thành công');
            setSessionFlash('msg_type', 'success');
            redirect('?module=users&action=profile');
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
                <h2>Thông tin người dùng</h2>
                <?php
                if (!empty($msg) && !empty($msg_type)) {
                    getMsg($msg, $msg_type);
                }
                ?>
                <form action="" method="post" enctype='multipart/form-data'>
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

                        <div class="col-6 pb-3">
                            <label for="avartar">Ảnh đại diện</label>
                            <input id="avartar" name="avartar" type="file" class="form-control"
                                placeholder="nhập địa chỉ">
                            <img width="200px" id="previewImage" class="preview-image p-3"
                                src="<?php echo _HOST_URL; ?><?php echo !empty($oldData['avartar']) ? $oldData['avartar'] : false; ?>"
                                alt="">
                        </div>
                        
                    </div>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
Layout('footer');
?>

<script>
    // xử lý slug đường dẫn tự động tạo đường dẫn
    const thumbInput = document.getElementById('avartar');
    const previewImg = document.getElementById('previewImage');

    thumbInput.addEventListener('change', function () { // có sự thay đỗi input img
        const file = this.files[0] // file đầu tiên
        if (file) {
            const reader = new FileReader(); // đọc dlieu sẵn
            reader.onload = function (e) {
                previewImg.setAttribute('src', e.target.result);
                previewImg.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
        else {
            previewImg.style.display = 'none';
        }
    });
</script>