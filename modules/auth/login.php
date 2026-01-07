<?php
if (!defined('_Manh')) {
    echo 'truy cập không hợp lệ';
}
$data = [
    'title' => 'Đăng nhập hệ thống'
];
Layout('header-auth', $data);

/*
- validate dlieu nhập vào
- check dlieu vs csdl (email, pass)
- dlieu khớp --> tokenlogin --> insert vào bảng token_login
- điều hướng dashboard
*/


if (isPost()) {
    // lọc dữ liệu gọi lại hàm filterData ở fuctions.php gán lại cho biến $filter
    $filter = filterData();
    $err = []; // mảng giá trị đầu là rỗng

    // validate email
    if (empty(trim($filter['email']))) {
        $err['email']['required'] = 'email không để trống';
    } else {
        if (!validateEmail($filter['email'])) { // gọi lại hàm validateEmail ở bên fuc.php 
            $err['email']['isEmail'] = 'email không đúng định dạng';
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

    if(empty($err)){
        // ktra dlieu
        $email = $filter['email'];
        $password = $filter['password'];

        //ktra email
        $checkEmail = getOne("SELECT * FROM users WHERE email = '$email' ");

        if(!empty($checkEmail)){
            if(!empty($password)) {
                $checkStatus = password_verify($password, $checkEmail['password']);
                
                if($checkStatus) {
                    // tạo token và insert và bảng token_login
                    $token = sha1(uniqid() . time());
                    $data  = [
                        'token' => $token,
                        'created_at' => date('Y:m:d H:i:s'),
                        'user_id' => $checkEmail['id'] // user_id theo biến checkEmail khớp vs thông tin id
                    ];
                    $insetToken = insert('token_login', $data);
                    // nếu insert thành công
                    if($insetToken){
                        setSessionFlash('msg', 'Đăng nhập thành công'); // gọi hàm setSession bên session, lưu giá trị cũ
                        setSessionFlash('msg_type', 'success'); // gọi hàm setSession bên session
                        redirect('/'); // hàm ở fuc.php
                    }else{
                        setSessionFlash('msg', 'Đăng nhập không thành công'); // gọi hàm setSession bên session, lưu giá trị cũ
                        setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session
                    }
                }else{
                    setSessionFlash('msg', 'vui lòng kiểm tra dữ liệu nhập vào.'); // gọi hàm setSession bên session, lưu giá trị cũ
                    setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session
                }
            }
        }

        
    }else{
        setSessionFlash('msg', 'vui lòng kiểm tra dữ liệu nhập vào.'); // gọi hàm setSession bên session, lưu giá trị cũ
        setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session

        setSessionFlash('oldData', $filter); // gọi hàm setSession bên session, lưu giá trị cũ
        setSessionFlash('error', $err); // gọi hàm setSession bên session
    }

}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData'); // lấy giá trị cũ ng dùng nhập
$errorArray = getSessionFlash('error'); 

?>

<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="<?php echo _HOST_URL_TEMPLATES; ?>/assets/image/draw2.webp"
                    class="img-fluid" alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <?php 
                if(!empty($msg) && !empty($msg_type)){
                    getMsg($msg, $msg_type);
                }
                ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-4 me-3">Đăng nhập hệ thống</h2>
                        
                    </div>


                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="email" id="form3Example3" value="<?php 
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

                    <!-- Password input -->
                    <div data-mdb-input-init class="form-outline mb-3">
                        <input type="password" name="password" id="form3Example4" class="form-control form-control-lg"
                            placeholder="Nhập mật khẩu của bạn" />
                        <?php 
                        if(!empty($errorArray)){
                            echo formErr($errorArray, 'password');
                        } 
                        ?> 
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        
                        <a href="<?php echo _HOST_URL; ?>?module=auth&action=forgot" class="text-body">Quên mật khẩu?</a>
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng nhập</button>
                        <p class="small fw-bold mt-2 pt-1 mb-0">Bạn chưa có tài khoản? <a href="<?php echo _HOST_URL; ?>?module=auth&action=register"
                                class="link-danger">Đăng ký ngay</p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<?php
Layout('footer');