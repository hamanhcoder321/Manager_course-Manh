<?php
if (!defined('_Manh')) {
    echo 'truy cập không hợp lệ';
}
$data = [
    'title' => 'Quên mật khẩu'
];
Layout('header-auth', $data);

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

    if (empty($err)) {
        // xly gửi email
        if (!empty($filter['email'])) {
            $email = $filter['email'];
            $checkEmail = getOne("SELECT * FROM users WHERE email = '$email' ");

            if (!empty($checkEmail)) {
                // update forget_token vào bảng users
                $forgot_token = sha1(uniqid() . time());
                $data = [
                    'forget_token' => $forgot_token
                ];

                $condition = "id=" . $checkEmail['id'];
                $updateStatus = update('users', $data, $condition);
                
                if ($updateStatus) {
                    // bắn email cho users
                    $emailTo = $email;
                    $subject = 'Reset mật khẩu tài khoản hệ thống';
                    $content = 'bạn đang yêu cầu reset lại mật khẩu hệ thống <br>';
                    $content .= 'Để thay đổi mật khẩu tài khoản, bạn hãy click vào đường link bên dưới: <br>';
                    $content .= _HOST_URL . '/?module=auth&action=reset&token=' . $forgot_token . '<br>';
                    $content .= 'Thank you so match';

                    sendMail($emailTo, $subject, $content);

                    setSessionFlash('msg', 'gửi yêu cầu thành công , vui lòng kiểm tra email'); // gọi hàm setSession bên session, lưu giá trị cũ
                    setSessionFlash('msg_type', 'success'); // gọi hàm setSession bên session
                }else{
                    setSessionFlash('msg', 'đã có lỗi xảy ra,vui lòng thử lại sau.'); // gọi hàm setSession bên session, lưu giá trị cũ
                    setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session
                }
            }
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
$errorArray = getSessionFlash('error');

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
                if (!empty($msg) && !empty($msg_type)) {
                    getMsg($msg, $msg_type);
                }
                ?>

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-4 me-3">Quên mật khẩu</h2>

                    </div>

                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="email" name="email" id="form3Example3" value="<?php
                        if (!empty($errorArray)) {
                            echo oldData($oldData, 'email');
                        }
                        ?>" class="form-control form-control-lg" placeholder="Nhập địa chỉ email" />
                        <?php
                        if (!empty($errorArray)) {
                            echo formErr($errorArray, 'email');
                        }
                        ?>
                    </div>


                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" type="button" data-mdb-button-init data-mdb-ripple-init
                            class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<?php
Layout('footer');