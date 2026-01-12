<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}
$data = [
    'title' => 'Danh sách học viên'
];
Layout('header', $data);
// Layout('sidebar');

/*
-  trước tiên lấy dlieu users phải đổi json sang mảng 
*/

$filter = filterData();

$keyword = '';
if(isGet()){
    
}

$permissionArr = [];
$userDetail = getAll("SELECT fullname, email, permission FROM users");
if(!empty($userDetail)){
    foreach($userDetail as $key => $item){
        $permissionJson = json_decode($item['permission'], true);
        $permissionArr[$key] = $permissionJson;
    }
}
echo '<pre>';
print_r($permissionArr);
echo '</pre>';
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
                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
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

                                ?>
                                <tr>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                                <?php
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