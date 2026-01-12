<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

// cú pháp search: ?module=users&action=list&group=1&keyword=manh
// phân trang: trước ... ,4,'5',6 ... sau
// '1', 2, 3, ... sau --> trước 1, '2', 3, 4 .. sau
// perPage, maxPage, offset

$data = [
    'title' => 'Danh sách khóa học'
];
Layout('header', $data);
Layout('sidebar');

$filter = filterData();
$chuoiWhere = ''; // nó tìm theo điều kiện theo group và keyword
$cate = '0';
$keyword = '';
$offset = 0;
$page = 1;

if (isGet()) { // xảy ra phương thức get
    if (isset($filter['keyword'])) { // nếu tồn tại keyword
        $keyword = $filter['keyword']; // gán lại giá trị keyword
    }
    if (isset($filter['cate'])) { // nếu tồn tại cate
        $cate = $filter['cate']; // gán lại giá trị cate
    }

    if (!empty($keyword)) { // nếu keyword có dlieu ko rỗng
        // ktra trong chuoi và chuoi dùng strpos, ktra có chuỗi where chưa
        if (strpos($chuoiWhere, 'WHERE') == false) {
            $chuoiWhere .= ' WHERE '; // nếu chưa có where nối where
        } else {
            $chuoiWhere .= ' AND '; // nếu có where thêm and
        }
        $chuoiWhere .= " a.name LIKE '%$keyword%' OR a.description LIKE '%$keyword%' "; // tìm kiếm theo tên và so sánh chuỗi dlieu
    }
    if (!empty($cate)) { // nếu group có dlieu ko rỗng
        // ktra trong chuoi và chuoi dùng strpos, ktra có chuỗi where chưa
        if (strpos($chuoiWhere, 'WHERE') == false) {
            $chuoiWhere .= ' WHERE '; // nếu chưa có where nối where
        } else {
            $chuoiWhere .= ' AND '; // nếu có where thêm and
        }
        $chuoiWhere .= " a.category_id = $cate ";
    }
}


// xử lý phân trang
$maxData = getRows("SELECT id FROM course"); // tổng dlieu bảng course
$perPage = 4; // số dòng dlieu 1 trang
$maxPage = ceil($maxData / $perPage); // hàm chia số trang


// get page
if (isset($filter['page'])) {
    $page = $filter['page']; // lấy page url
}

if ($page > $maxPage || $page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $perPage;


// biến lấy all dlieu users ra giao diện (a là bảng users,b là groups viết tắt các bảng) inner join
$getDetailUser = getAll("SELECT a.id, a.name, a.price, a.created_at, a.thumbnail, b.name as name_cate FROM 
course a INNER JOIN course_category b ON a.category_id = b.id $chuoiWhere
ORDER BY a.created_at DESC
LIMIT $offset, $perPage
");



// xly query
if (!empty($_SERVER['QUERY_STRING'])) { // được dùng để lấy toàn bộ chuỗi văn bản đứng sau dấu chấm hỏi (?) trong một URL [1, 2], Trả về một chuỗi (string) thô chưa qua xử lý
    $queryString = $_SERVER['QUERY_STRING'];

    //tạo lại link phân trang mới hoặc chuyển hướng mà không muốn các tham số trang cũ chồng chéo lên nhau
    // lưu ý xóa phải có page đứng đầu ko có page ko xóa đc
    $queryString = str_replace('&page=' . $page, '', $queryString);
}


$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');

?>



<?php
Layout('footer');
?>