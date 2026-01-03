<?php
if (!defined('_Manh')) {
    echo 'truy cập không hợp lệ';
}
$data = [
    'title' => 'Đặt lại mật khẩu'
];
Layout('header-auth', $data)
?>

<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="<?php echo _HOST_URL_TEMPLATES; ?>/assets/image/draw2.webp"
                    class="img-fluid" alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <form>
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-4 me-3">Đặt lại mật khẩu</h2>
                        
                    </div>


                    <!-- password mới -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" class="form-control form-control-lg"
                            placeholder="Nhập mật khẩu mới" />
                    </div>

                    <!--nhập lại password mới -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" class="form-control form-control-lg"
                            placeholder="Nhập lại mật khẩu mới" />
                    </div>


                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi</button>
                        
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<?php
Layout('footer');