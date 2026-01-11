<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

// cú pháp search: ?module=users&action=list&group=1&keyword=manh
// phân trang: trước ... ,4,'5',6 ... sau
// '1', 2, 3, ... sau --> trước 1, '2', 3, 4 .. sau
// perPage, maxPage, offset

$data = [
    'title' => 'Danh sách tài khoản'
];
Layout('header', $data);
Layout('sidebar');

$filter = filterData();
$chuoiWhere = ''; // nó tìm theo điều kiện theo group và keyword
$group = '0';
$keyword = '';
$offset = 0;
$page = 1;

if (isGet()) { // xảy ra phương thức get
    if (isset($filter['keyword'])) { // nếu tồn tại keyword
        $keyword = $filter['keyword']; // gán lại giá trị keyword
    }
    if (isset($filter['group'])) { // nếu tồn tại group
        $group = $filter['group']; // gán lại giá trị group
    }

    if (!empty($keyword)) { // nếu keyword có dlieu ko rỗng
        // ktra trong chuoi và chuoi dùng strpos, ktra có chuỗi where chưa
        if (strpos($chuoiWhere, 'WHERE') == false) {
            $chuoiWhere .= ' WHERE '; // nếu chưa có where nối where
        } else {
            $chuoiWhere .= ' AND '; // nếu có where thêm and
        }
        $chuoiWhere .= " a.fullname LIKE '%$keyword%' OR a.email LIKE '%$keyword%' "; // tìm kiếm theo tên và so sánh chuỗi dlieu
    }
    if (!empty($group)) { // nếu group có dlieu ko rỗng
        // ktra trong chuoi và chuoi dùng strpos, ktra có chuỗi where chưa
        if (strpos($chuoiWhere, 'WHERE') == false) {
            $chuoiWhere .= ' WHERE '; // nếu chưa có where nối where
        } else {
            $chuoiWhere .= ' AND '; // nếu có where thêm and
        }
        $chuoiWhere .= " a.group_id = $group ";
    }
}


// xử lý phân trang
$maxData = getRows("SELECT id FROM users "); // tổng dlieu bảng users
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
$getDetailUser = getAll("SELECT a.id, a.fullname, a.email, a.created_at, b.name FROM 
users a INNER JOIN `groups` b ON a.group_id = b.id $chuoiWhere
ORDER BY a.created_at DESC
LIMIT $offset, $perPage
");

// lấy group nhóm người dùng, tất cả nhóm
$getGroup = getAll("SELECT * FROM `groups`");

// xly query
if (!empty($_SERVER['QUERY_STRING'])) { // được dùng để lấy toàn bộ chuỗi văn bản đứng sau dấu chấm hỏi (?) trong một URL [1, 2], Trả về một chuỗi (string) thô chưa qua xử lý
    $queryString = $_SERVER['QUERY_STRING'];

    //tạo lại link phân trang mới hoặc chuyển hướng mà không muốn các tham số trang cũ chồng chéo lên nhau
    // lưu ý xóa phải có page đứng đầu ko có page ko xóa đc
    $queryString = str_replace('&page=' . $page, '', $queryString);
}


// đặt lại maxpage tránh hiển thị lỗi phân trang thừa
if ($group > 0 || !empty($keyword)) { // keyword khác rỗng
    $maxData2 = getRows("SELECT id FROM 
    users a $chuoiWhere");
    $maxPage = ceil($maxData2 / $perPage); // hàm chia số trang
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');

?>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="container grid-user">
                <div class="container-fuild">
                    <div class="plus-search">
                        <?php
                        if (!empty($msg) && !empty($msg_type)) {
                            getMsg($msg, $msg_type);
                        }
                        ?>
                        <form action="" method="get">
                            <input type="hidden" name="module" value="users">
                            <input type="hidden" name="action" value="list">
                            <div class="row mb-3">
                                <div class="col-3 form-group">
                                    <select class="form-select form-control" name="group" id="">
                                        <option value="">Nhóm người dùng</option>
                                        <?php
                                        foreach ($getGroup as $item):
                                            ?>
                                            <!--lấy id và name groups-->
                                            <option value="<?php echo $item['id']; ?>" <?php echo ($group == $item['id']) ? 'selected' : false; ?>><?php echo $item['name'] ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                                <div class="col-7">
                                    <input type="text" class="form-control"
                                        value="<?php echo (!empty($keyword)) ? $keyword : false; ?>" name="keyword"
                                        placeholder="Nhập thông tin tìm kiếm...">
                                </div>
                                <div class="col-2"><button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                        </form>
                        <a href="?module=users&action=add" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i>
                            Thêm mới
                            tài khoản</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Họ Tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Ngày đăng ký</th>
                                <th scope="col">Nhóm</th>
                                <th scope="col">Phân quyền</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--duyệt mảng-->
                            <?php
                            // duyệt và lấy dlieu mảng con item
                            foreach ($getDetailUser as $key => $item): // key là id, item dlieu
                                ?>
                                <tr>
                                    <!--key+1 vì key bắt đầu số 0 nên + 1 đơn vị lấy 1-->
                                    <th scope="row"><?php echo $key + 1; ?></th>
                                    <td><?php echo $item['fullname']; ?></td>
                                    <td><?php echo $item['email']; ?></td>
                                    <td><?php echo $item['created_at']; ?></td>
                                    <td><?php echo $item['name']; ?></td>
                                    <td><a href="?module=users&action=permission&id=<?php echo $item['id']; ?>"
                                            class="btn btn-primary">Phân quyền</a></td>
                                    <td><a href="?module=users&action=edit&id=<?php echo $item['id']; ?>"
                                            class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a>
                                        <a href="?module=users&action=delete&id=<?php echo $item['id']; ?>"
                                            onclick="return confirm('bạn chắc chắn muốn xóa không?')"
                                            class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                    </div>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <!--xử lý phân trang trước nếu 1 ẩn nút còn ở 2 trở đi hiện-->
                            <?php
                            if ($page > 1):
                                ?>
                                <li class="page-item"><a class="page-link"
                                        href="?<?php echo $queryString ?>&page=<?php echo $page - 1; ?>"><i
                                            class="fa-solid fa-backward"></i></a></li>
                            <?php endif; ?>

                            <!--vị trí bđầu nếu 3-2 = 1 thì lớn hơn hoặc bằng 1 hiện ba chấm-->
                            <?php
                            $start = $page - 1;
                            if ($start < 1) {
                                $start = 1; // ko cho số âm nữa mà 1 luôn, ví dụ nó 1 trừ 2 số âm 
                            }
                            ?>
                            <?php
                            if ($start > 1):
                                ?>
                                <li class="page-item"><a class="page-link"
                                        href="?<?php echo $queryString ?>&page=<?php echo $page - 1; ?>">...</a></li>

                            <?php endif;
                            $end = $page + 1;
                            if ($end > $maxPage) { // nếu end này lớn hơn page
                                $end = $maxPage; // gán end bằng maxpage luôn
                            }
                            ?>

                            <!--lặp vtri bắt đầu vì trước có chấm lặp lấy hiển thị page ở vtri bắt đầu và kthuc ở end-->
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <!--biến i = start và nhỏ hơn hoặc = end i++ -->
                                <li class="page-item <?php echo ($page == $i) ? 'active' : false; ?>"><a class="page-link"
                                        href="?<?php echo $queryString ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>

                                <?php
                            endfor;
                            if ($end < $maxPage):
                                ?>
                                <li class="page-item"><a class="page-link"
                                        href="?<?php echo $queryString ?>&page=<?php echo $page + 1; ?>">...</a></li>

                            <?php endif; ?>
                            <!--xử lý phân trang sau nếu lớn hơn maxpage ẩn nút còn nhỏ hơn maxPage hiện-->
                            <?php
                            if ($page < $maxPage):
                                ?>
                                <li class="page-item"><a class="page-link"
                                        href="?<?php echo $queryString ?>&page=<?php echo $page + 1; ?>"><i
                                            class="fa-solid fa-forward"></i></a></li>
                            <?php endif;
                            ?>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>
</main>

<?php
Layout('footer');
?>