<?php
if (!defined('_Manh')) {
    echo 'truy cập không hợp lệ';
}
$data = [
    'title' => 'Đăng ký tài khoản'
];
Layout('header-auth', $data);


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

    // validate sdt
    if (empty($filter['phone'])) {
        $err['phone']['required'] = 'số điện thoại không được để trống';
    } else {
        if (!isPhone($filter['phone'])) { // gọi lại hàm isPhone ktra dlieu sdt
            $err ['phone']['isPhone'] = 'số điện thoại không đúng định dạng';
        }
    }

    // validate pass
    if (empty(trim($filter['password']))) {
        $err['password']['required'] = 'mật khẩu không bỏ trống';
    } else {
        if (strlen(trim($filter['password'])) < 6) {
            $err['password']['isPass'] = 'mật khẩu phải lớn 6';
        }
    }

    // validate confirm_pass
    if (empty(trim($filter['confirm_pass']))) {
        $err['confirm_pass']['required'] = 'Vui lòng nhập lại mật khẩu';
    } else {
        if (trim($filter['password']) !== trim($filter['confirm_pass'])) { // so sánh 2 pass dlieu và giá trị
            $err['confirm_pass']['like'] = 'Nhập lại mật khẩu không đúng';
        }
    }

    if (empty($err)){
        // insert table: user
        $activeToken = sha1(uniqid().time()); // lấy mã hóa thông tin gửi email kích hoạt
        $data = [
            'fullname' => $filter['fullname'],
            // 'address'  => $filter['address'],
            'phone'    => $filter['phone'],
            'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
            'email'    => $filter['email'],
            'active_token' => $activeToken,
            'group_id'     => 1,
            'created_at'   => date('Y:m:d H:i:s')
        ];

        $checkInsert = insert('users', $data);

        if($checkInsert){
            // bắn email cho users
            $emailTo = $filter['email'];
            $subject = 'Kích hoạt tài khoản hệ thống';
            $content = 'Chúc mừng bạn đã đăng ký tài khoản thành công <br>';
            $content .= 'Để kích hoạt tài khoản, bạn hãy click vào đường link bên dưới: <br>';
            $content .= _HOST_URL . '/?module=auth&action=active&token=' .$activeToken. '<br>';
            $content .= 'Thank you so match';

            sendMail($emailTo, $subject, $content);

            setSessionFlash('msg', 'Đăng ký thành công , vui lòng kích hoạt tài khoản'); // gọi hàm setSession bên session, lưu giá trị cũ
            setSessionFlash('msg_type', 'success'); // gọi hàm setSession bên session
        }else{
            setSessionFlash('msg', 'Đăng ký không thành công , vui lòng thử lại sau.'); // gọi hàm setSession bên session, lưu giá trị cũ
            setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session
        }
    }else{
        setSessionFlash('msg', 'vui lòng kiểm tra dữ liệu nhập vào.'); // gọi hàm setSession bên session, lưu giá trị cũ
        setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session

        setSessionFlash('oldData', $filter); // gọi hàm setSession bên session, lưu giá trị cũ
        setSessionFlash('error', $err); // gọi hàm setSession bên session

    }

    $msg = getSessionFlash('msg');
    $msg_type = getSessionFlash('msg_type');
    $oldData = getSessionFlash('oldData'); // lấy giá trị cũ ng dùng nhập
    $errorArray = getSessionFlash('error'); 
}
?>

<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="<?php echo _HOST_URL_TEMPLATES; ?>/assets/image/draw2.webp" class="img-fluid"
                alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                <?php 
                if(!empty($msg) && !empty($msg_type)){
                    getMsg($msg, $msg_type);
                }
                ?>

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-4 me-3">Đăng ký tài khoản</h2>
                    </div>
                    
                    
                    <!-- input tên, email, phone, mật khẩu, nhập lại mật khẩu-->
                    
                    <!--họ tên-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="fullname" type="text" value="<?php  
                        if(!empty($oldData)){
                            echo oldData($oldData, 'fullname');
                        }
                        ?>" class="form-control form-control-lg"
                        placeholder="Họ tên của bạn" />
                        <?php 
                            if(!empty($errorArray)){
                                echo formErr($errorArray, 'fullname');
                            }
                        ?>
                    </div>

                    <!--email-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="email" type="text" value="<?php 
                            if(!empty($errorArray)){
                                echo oldData($oldData, 'email'); 
                            }
                        ?>" class="form-control form-control-lg"
                            placeholder="Nhập địa chỉ email" />
                        <?php 
                            if(!empty($errorArray)){
                                echo formErr($errorArray, 'email');
                            }
                        ?>
                    </div>

                    <!--số điện thoại-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="phone" type="text" value="<?php 
                        if(!empty($errorArray)){
                            echo oldData($oldData, 'phone'); 
                        }
                        ?>" class="form-control form-control-lg"
                            placeholder="Nhập số điện thoại" />
                        <?php 
                            if(!empty($errorArray)){
                                echo formErr($errorArray, 'phone');
                            }
                        ?>
                    </div>

                    <!--password mật khẩu-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="password" type="password" id="form3Example4" class="form-control form-control-lg"
                            placeholder="Nhập mật khẩu" />
                        <?php if(!empty($errorArray)){
                            echo formErr($errorArray, 'password');
                        } ?> 
                    </div>

                    <!-- confirm password mật khẩu-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="confirm_pass" type="password" class="form-control form-control-lg"
                            placeholder="Nhập lại mật khẩu" />
                        <?php if(!empty($errorArray)){
                            echo formErr($errorArray, 'confirm_pass');
                        } ?> 
                    </div>


                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng ký</button>
                        <p class="small fw-bold mt-2 pt-1 mb-0">Bạn đã có tài khoản? <a
                                href="<?php echo _HOST_URL; ?>?module=auth&action=login" class="link-danger">Đăng nhập
                                ngay</p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<?php
Layout('footer');