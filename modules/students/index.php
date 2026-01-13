<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}
$data = [
    'title' => 'Danh sách học viên'
];
Layout('header', $data);
Layout('sidebar');

/*
-  trước tiên lấy dlieu users phải đổi json sang mảng 
*/

$filter = filterData();

$keyword = '';
if (isGet()) { // pthuc get xảy ra
    if (isset($filter['keyword'])) { // nếu keyword tồn tại
        $keyword = $filter['keyword']; // gán filter[keyword] vào $keyword
    }
}

$permissionArr = []; // tổng hợp mảng pquyen
$userDetail = getAll("SELECT fullname, email, permission FROM users"); // lấy thông tin tk user
if (!empty($userDetail)) {
    foreach ($userDetail as $key => $item) {
        $permissionJson = json_decode($item['permission'], true);
        $permissionArr[$key] = $permissionJson; // sau khi lấy giá trị cho lưu vào $permissionArr
    }
}

?>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid p-3">
            <div class="container">
                <form action="" method="get">
                    <div class="row">
                        <input type="hidden" name="module" value="students">
                        <div class="col-9">
                            <select name="keyword" id="" class="form-select form-control">
                                <option value="0">Chọn khóa học</option>
                                <?php
                                $getCourseDetail = getAll("SELECT id, name FROM course");
                                foreach ($getCourseDetail as $item):
                                    ?>
                                    <option value="<?php echo $item['id']; ?>" <?php if($keyword == $item['id']) {echo 'selected';} ?>><?php echo $item['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary">Duyệt</button>
                        </div>
                    </div>
                </form>
                <div class="row p-3">
                    <div class="col-9">
                        <table class="table table-borderd">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên học viên</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $dem = 0;
                                // ktra keyword id nằm trong persion ko từng tk nếu có permission thì ktra lấy id đó in ra thông tin tk đó
                                foreach ($permissionArr as $key => $item):
                                    if (!empty($item)): // ktra item có rỗng ko
                                        if (in_array($keyword, $item)):
                                            ?>
                                            <tr>
                                                <td><?php echo $dem+1;
                                                $dem++; ?></td> <!--số thứ tự-->
                                                <td><?php echo $userDetail[$key]['fullname'] ?></td> <!--tên học viên-->
                                                <td><?php echo $userDetail[$key]['email'] ?></td>
                                            </tr>
                                            <?php
                                        endif;
                                    endif;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
Layout('footer');
?>