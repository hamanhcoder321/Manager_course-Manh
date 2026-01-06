<?php

// ktra xem active_token ở url có giống active_token trong bảng users ko
// Update status trong bảng users -> 1 đã kích hoạt -> và xóa active_token đi
if (!defined('_Manh')) {
    echo 'truy cập không hợp lệ';
}
$data = [
    'title' => 'kích hoạt tài khoản'
];
Layout('header-auth', $data);

$filter = filterData('get');


if (!empty($filter['token'])): // nếu có token
    $token = $filter['token']; // gán token 
    $checkToken = getOne("SELECT * FROM users WHERE active_token = '$token' "); // ktra token csdl
    ?>

    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="<?php echo _HOST_URL_TEMPLATES; ?>/assets/image/draw2.webp" class="img-fluid"
                        alt="Sample image">
                </div>

                <?php
                if (!empty($checkToken)):
                    // cập nhật lại csdl
                    $data = [
                      'status' => 1,
                      'active_token' => null,
                      'updated_at' => date('Y:m:d H:i:s') 
                    ];

                    $condition = "id = ". $checkToken['id']; // theo id token users
                    update('users', $data, $condition);
                    ?>
                    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                            <h2 class="fw-normal mb-4 me-3">Kích hoạt tài khoản thành công</h2>
                        </div>
                        <a href="<?php echo _HOST_URL; ?>?module=auth&action=login" class="link-danger"
                            style="font-size: 20px; color: blue !important;">Đăng nhập ngay</a>
                    </div>
                    <?php
                else:
                    ?>
                    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                            <h2 class="fw-normal mb-4 me-3">Kích hoạt tài khoản không thành công</h2>
                        </div>
                    </div>
                    <?php
                endif;
                ?>

            </div>
        </div>
    </section>

    <?php
else: // đường link sai hoặc ko hợp lệ
    ?>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="<?php echo _HOST_URL_TEMPLATES; ?>/assets/image/draw2.webp" class="img-fluid"
                        alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-4 me-3">Đường link kích hoạt đã hết hạn hoặc không tồn tại!</h2>
                    </div>
                    <a href="<?php echo _HOST_URL; ?>?module=auth&action=login" class="link-danger"
                        style="font-size: 20px; color: blue !important;">Quay trở lại</a>
                </div>
            </div>
        </div>
    </section>
    <?php
endif;

?>


<?php
Layout('footer');