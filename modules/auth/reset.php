<?php
if (!defined('_Manh')) {
    echo 'truy cập không hợp lệ';
}
$data = [
    'title' => 'Đặt lại mật khẩu'
];
Layout('header-auth', $data);

$filterGet = filterData('get'); // lấy token

if (!empty($filterGet['token'])) {
    $tokenReset = $filterGet['token']; // nếu token tồn tại 
}


if (!empty($tokenReset)) { //có token 
    $checkToken = getOne("SELECT * FROM users WHERE forget_token = '$tokenReset' ");
    if (!empty($checkToken)) {
        if (isPost()) {
            // lọc dữ liệu gọi lại hàm filterData ở fuctions.php gán lại cho biến $filter
            $filter = filterData();
            $err = []; // mảng giá trị đầu là rỗng

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
            if (empty($err)) {
                $password = password_hash($filter['password'], PASSWORD_DEFAULT);
                $data = [
                    'password' => $password,
                    'forget_token' => null,
                    'updated_at' => date('Y:m:d H:i:s')
                ];
                $condition = "id=" . $checkToken['id'];
                $updateStatus = update('users', $data, $condition);
                if ($updateStatus) {
                    // bắn email cho users
                    $emailTo = $checkToken['email'];
                    $subject = 'Đổi mật khẩu tài khoản hệ thống thành công';
                    $content = 'chúc mừng bạn đổi mật khẩu hệ thống thành công<br>';
                    $content .= 'Nếu không phải bạn thao tác đổi mật khẩu thì hãy liên hệ ngay với Quản trị viên!<br>';
                    $content .= 'Thank you so match';

                    sendMail($emailTo, $subject, $content);

                    setSessionFlash('msg', 'Đổi mật khẩu thành công'); // gọi hàm setSession bên session, lưu giá trị cũ
                    setSessionFlash('msg_type', 'success'); // gọi hàm setSession bên session
                } else {
                    setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau!.'); // gọi hàm setSession bên session, lưu giá trị cũ
                    setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session
                }
            } else {
                setSessionFlash('msg', 'vui lòng kiểm tra dữ liệu nhập vào.'); // gọi hàm setSession bên session, lưu giá trị cũ
                setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session

                setSessionFlash('oldData', $filter); // gọi hàm setSession bên session, lưu giá trị cũ
                setSessionFlash('error', $err); // gọi hàm setSession bên session
            }
        }
    } else {
        getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
    }

} else { // nếu ko tồn tại token
    getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
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
                        <h2 class="fw-normal mb-4 me-3">Đặt lại mật khẩu</h2>

                    </div>


                    <!-- password mới -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" name="password" class="form-control form-control-lg"
                            placeholder="Nhập mật khẩu mới" />
                        <?php if (!empty($errorArray)) {
                            echo formErr($errorArray, 'password');
                        } ?>
                    </div>

                    <!--nhập lại password mới -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" name="confirm_pass" class="form-control form-control-lg"
                            placeholder="Nhập lại mật khẩu mới" />
                        <?php if (!empty($errorArray)) {
                            echo formErr($errorArray, 'confirm_pass');
                        } ?>
                    </div>


                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" type="button" data-mdb-button-init data-mdb-ripple-init
                            class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi</button>

                        <p style="margin-top: 15px; "><a href="<?php echo _HOST_URL; ?>?module=auth&action=login" class="link-danger">Quay lại Đăng nhập</p>

                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<?php
Layout('footer');