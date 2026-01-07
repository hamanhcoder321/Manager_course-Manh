<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

// hàm title các trang
function Layout($LayoutName, $data = [])
{
    if (file_exists(require_once _PATH_URL_TEMPLATES . '/layouts/' . $LayoutName . '.php')) {
        require_once _PATH_URL_TEMPLATES . '/layouts/' . $LayoutName . '.php';
    }
}

// hàm xử lý gửi email
use PHPMailer\PHPMailer\PHPMailer; // tạo đối tượng email, thiết lập người gửi, người nhận, tiêu đề, nội dung
use PHPMailer\PHPMailer\SMTP; // lớp này chịu trách nhiệm xử lý giao tiếp với máy chủ mail qua giao thức SMTP
use PHPMailer\PHPMailer\Exception; // bắt lỗi và hiển thị lỗi

function sendMail($emailTo, $subject, $content)
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'vanmanh8930@gmail.com';                     //SMTP username
        $mail->Password = 'dnntdbkvwnqjfuly';                              //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('vanmanh8930@gmail.com', 'Manh Course');
        $mail->addAddress(address: $emailTo);     //Add a recipient


        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $content;


        $mail->SMTPOptions = array(
            'ssl' => [
                'verify_peer' => true,
                'verify_depth' => 3,
                'allow_self_signed' => true
            ],
        );

        return $mail->send();
    } catch (Exception $e) {
        echo "gửi thất bại. Mailer Error: {$mail->ErrorInfo}";
    }
}

// ktra phương thức post nếu yêu cầu dlieu post
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // nếu là post thì xly
        return true; // nếu có thì true
    }
    return false;// ko phải post false
}

// ktra phương thức get nếu yêu cầu dlieu get
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}


// lọc dữ liệu đầu vào loại bỏ ký tự đặc biệt sạch dữ liệu
function filterData($method = '')
{ // tham số method này nếu ko truyền gì mặc định là rỗng 
    $filterArr = [];
    if (empty($method)) { // nếu tồn tại $method là post hoặc là get thì cho vào điều kiện else if khác nhau
        if (isGet()) { // nếu là get thì xly dlieu 
            if (!empty($_GET)) { // sau khi xác đinh get xly
                foreach ($_GET as $key => $value) { // lọc key và value
                    $key = strip_tags($key);
                    if (is_array($value)) { // nếu value là mảng
                        $filterArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                        // xóa bỏ ký tự đặc biệt, FILTER_REQUIRE_ARRAY và xly value dạng cho mảng

                    } else {// nếu ko pải mảng
                        // sau khi lọc dữ liệu xong thì truyền lại giá trị cho filterArr mảng và vào $key 
                        $filterArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // xóa bỏ ký tự đặc biệt
                    }
                }
            }
        }
        if (isPost()) { // nếu là post
            if (!empty($_POST)) { // sau khi xác đinh get xly
                foreach ($_POST as $key => $value) { // lọc key và value
                    $key = strip_tags($key);
                    if (is_array($value)) { // nếu value là mảng
                        $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                        // xóa bỏ ký tự đặc biệt, FILTER_REQUIRE_ARRAY và xly value dạng cho mảng, INPUTPOST lấy giá trị từ ô input ng dùng nhập 

                    } else {// nếu ko pải mảng
                        // sau khi lọc dữ liệu xong thì truyền lại giá trị cho filterArr mảng và vào $key 
                        $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS); // xóa bỏ ký tự đặc biệt

                    }
                }
            }
        }
    } else {
        if ($method == 'get') {
            if (!empty($_GET)) { // sau khi xác đinh get xly
                foreach ($_GET as $key => $value) { // lọc key và value
                    $key = strip_tags($key);
                    if (is_array($value)) { // nếu value là mảng
                        $filterArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                        // xóa bỏ ký tự đặc biệt, FILTER_REQUIRE_ARRAY và xly value dạng cho mảng

                    } else {// nếu ko pải mảng
                        // sau khi lọc dữ liệu xong thì truyền lại giá trị cho filterArr mảng và vào $key 
                        $filterArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // xóa bỏ ký tự đặc biệt
                    }
                }
            }
        } else if ($method == 'post') {
            if (!empty($_POST)) { // sau khi xác đinh get xly
                foreach ($_POST as $key => $value) { // lọc key và value
                    $key = strip_tags($key);
                    if (is_array($value)) { // nếu value là mảng
                        $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                        // xóa bỏ ký tự đặc biệt, FILTER_REQUIRE_ARRAY và xly value dạng cho mảng, INPUTPOST lấy giá trị từ ô input ng dùng nhập 

                    } else {// nếu ko pải mảng
                        // sau khi lọc dữ liệu xong thì truyền lại giá trị cho filterArr mảng và vào $key 
                        $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS); // xóa bỏ ký tự đặc biệt

                    }
                }
            }
        }
    }
    return $filterArr;
}

// hàm validate
function validateEmail($email)
{
    if (!empty($email)) {
        $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL); // filter_var ktra định dang email đúng hay ko
    }
    return $checkEmail;
}

// hàm validate int
function validateInt($number)
{
    if (!empty($number)) {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT); // ktra giá trị số nguyên đúng ko
    }
    return $checkNumber; // trả về giá trị
}

// hàm validte phone
function isPhone($phone)
{
    $phoneFirst = false; // nếu ký tự đầu tiên sdt này bằng false
    if ($phone[0] == '0') {
        $phoneFirst = true; // nếu số đầu tiên 0 thì true
        $phone = substr($phone, 1); // cắt từ vị trí 1 về sau bỏ số 0
    }

    $checkPhone = false;
    if (validateInt($phone)) {
        $checkPhone = true; // validate thành công thì true
    }

    if ($phoneFirst & $checkPhone) { // nếu 2 biến có giá trị số nguyên sdt hợp lệ thì trả về true
        return true;
    }
    return false;
}

// validate chung form
function getMsg($msg, $type = 'success')
{
    echo '<div class="annouce-message alert alert-' . $type . ' "> ';
    echo $msg;
    echo '</div>';
}

// hiện lỗi input form
function formErr($err, $fieldName)
{
    //reset lây ptu trong key
    return (!empty($err[$fieldName])) ? ' <div class="error">' . reset($err[$fieldName]) . ' </div>' : false;
}

// hàm lưu giá trị cũ
function oldData($oldData, $fieldName)
{
    return !empty($oldData[$fieldName]) ? $oldData[$fieldName] : null;
}



// hàm chuyển hướng: path đầy đủ và path ko đầy đủ
/*
- vd: redirect('http://localhost/manager_course/?module=auth&action=login', true)
- redirect('?module=auth&action=login)
*/
function redirect($path, $pathFull = false)
{ // 2 tham số path 
    if ($pathFull) {
        header("Location: $path"); // path ko nối domain, truy cập theo url 
        exit();
    } else {
        $url = _HOST_URL . $path; // path nối domain truy cập theo url thiết lập
        header("Location: $url");
        exit();
    }
}


// hàm ktra login
function isLogin()
{
    $checkToken = false; // ban đầu là false
    $tokenLogin = getSessionFlash('token_login');
    $checkToken = getOne("SELECT * FROM token_login WHERE token = '$tokenLogin' ");
    if (!empty($checkToken)) { // nếu khớp token thì true
        $checkToken = true;
    } else {
        removeSession('token_login'); // nếu ko khớp thì xóa token đi
    }
    return $checkToken;
}