<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); // ngày giờ việt nam, Asia quốc tế
session_start(); //bắt đầu chạy 1 phiên, hoặc tiếp tục phiên làm việc

ob_start(); // tránh lỗi header, cookie

require_once 'config.php';


require_once './includes/connect.php';
require_once './includes/database.php';
require_once './includes/session.php';

// file xử lý email
require_once './includes/mailer/Exception.php';
require_once './includes/mailer/PHPMailer.php';
require_once './includes/mailer/SMTP.php';

require_once './includes/functions.php';


require_once './templates/layouts/index.php';



$module = _MODULES; // tạo biến cập nhật mặc định 
$action = _ACTION; // tạo biến action mặc định là index

if (!empty($_GET['module'])) { // ktra biến get tồn tại dlieu ko 
    $module = $_GET['module'];
    // nếu $_GET có dữ liệu cập nhật lại cho biến $module, nếu ko có dlieu lấy mặc định _modules đã tạo ở file config 
}

if (!empty($_GET['action'])) {
    $action = $_GET['action']; // tương tự như module
}

// sau khi có dữ liệu thì có path(đường dẫn) trỏ tới file actions .php
$path = 'modules/' . $module . '/' . $action . '.php';

// nếu path mà có dlieu thì 
if (!empty($path)) {
    if (file_exists($path)) {
        require_once $path; // lây dữ liệu modules path và action file .php
    } else {
        require_once './modules/errors/404.php'; // nếu page lỗi cho về 404
    }
} else {
    require_once './modules/errors/500.php'; // nếu http lỗi cho về 500
}
