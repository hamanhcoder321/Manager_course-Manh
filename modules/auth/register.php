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

    // validate confirm-pass
    if (empty(trim($filter['confirm-pass']))) {
        $err['confirm-pass']['required'] = 'Vui lòng nhập lại mật khẩu';
    } else {
        if (trim($filter['password']) !== trim($filter['confirm-pass'])) { // so sánh 2 pass dlieu và giá trị
            $err['confirm-pass']['like'] = 'Nhập lại mật khẩu không đúng';
        }
    }

    if (!empty($err)) {
        echo '<pre>';
        print_r($err);
        echo '</pre>';
    }
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
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-4 me-3">Đăng ký tài khoản</h2>

                    </div>


                    <!-- input tên, email, phone, mật khẩu, nhập lại mật khẩu-->

                    <!--họ tên-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="fullname" type="text" class="form-control form-control-lg"
                            placeholder="Họ tên của bạn" />
                    </div>

                    <!--email-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="email" type="text" class="form-control form-control-lg"
                            placeholder="Nhập địa chỉ email" />
                    </div>

                    <!--số điện thoại-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="phone" type="text" class="form-control form-control-lg"
                            placeholder="Nhập số điện thoại" />
                    </div>

                    <!--password mật khẩu-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="password" type="password" id="form3Example4" class="form-control form-control-lg"
                            placeholder="Nhập mật khẩu" />
                    </div>

                    <!--password mật khẩu-->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input name="confirm-pass" type="password" class="form-control form-control-lg"
                            placeholder="Nhập lại mật khẩu" />
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