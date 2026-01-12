<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

$data = [
    'title' => 'Thêm danh mục khóa học'
];
Layout('header', $data);
Layout('sidebar');


if (isPost()) {
    // lọc dữ liệu gọi lại hàm filterData ở fuctions.php gán lại cho biến $filter
    $filter = filterData();
    $err = []; // mảng giá trị đầu là rỗng

    // validate tên
    if (empty(trim($filter['name']))) { // key name ở form, và khoản trắng
        $err['name']['required'] = 'Tên danh mục khóa học không được để trống';
    }

    // validate mô tả
    if (empty($filter['slug'])) {
        $err['slug']['required'] = 'mô tả không được để trống';
    }

    if (empty($err)) {
        $dataCate = [
            'name' => $filter['name'],
            'slug' => $filter['slug'],
            'created_at' => date('Y:m:d H:i:s')
        ];

        $insertStatus = insert('course_category', $dataCate);

        if ($insertStatus) { // nếu thêm thành công báo
            setSessionFlash('msg', 'Thêm khóa học thành công');
            setSessionFlash('msg_type', 'success');
            redirect('?module=course_category&action=list');
        } else {
            setSessionFlash('msg', 'Đã có lỗi xảy ra vui lòng thử lại sau.');
            setSessionFlash('msg_type', 'danger');
        }
    } else {
        setSessionFlash('msg', 'vui lòng kiểm tra dữ liệu nhập vào.'); // gọi hàm setSession bên session, lưu giá trị cũ
        setSessionFlash('msg_type', 'danger'); // gọi hàm setSession bên session

        setSessionFlash('oldData', $filter); // gọi hàm setSession bên session, lưu giá trị cũ
        setSessionFlash('error', $err); // gọi hàm setSession bên session
    }
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData'); // lấy giá trị cũ ng dùng nhập
$errorArray = getSessionFlash('error');

?>


<main class="app-main">
    <div class="app-content">
        <div class="container-fluid pb-3">
            <div class="container">
                <h2>Thêm danh mục học</h2>
                <?php
                if (!empty($msg) && !empty($msg_type)) {
                    getMsg($msg, $msg_type);
                }
                ?>
                <form action="" method="post" enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-6 pb-3">
                            <label for="name">Tên danh mục học</label>
                            <input id="name" name="name" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'name');
                            }
                            ?>" placeholder="Tên danh mục khóa học">
                            <?php
                            if (!empty($errorArray)) {
                                echo formErr($errorArray, 'name');
                            }
                            ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="slug">slug</label>
                            <input id="slug" name="slug" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'slug');
                            }
                            ?>" placeholder="Mô tả">
                            <?php
                            if (!empty($errorArray)) {
                                echo formErr($errorArray, 'slug');
                            }
                            ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</main>


<script>
    // Hàm giúp chuyển text thành slug
    function createSlug (strig) {
        return strig.toLowerCase()
            .normalize('NFD') // chuyển ký tự có dấu thành tổ hợp: é -> e + | lập trình -> 1 + a + + p
            .replace(/[\u0300-\u036f]/g,'') // xoá dấu
            .replace(/d/g, 'd') // thay d -> d
            .replace(/[^a-z0-9\s-]/g,'') // xoá ký tự đặc biệt
            .trim() // bỏ khoảng trắng đầu/cuối
            .replace(/\s+/g, '-') // thay khoảng trắng ->
            .replace(/-+/g, '-'); // bỏ trùng dầu 
    }
    
    document.getElementById('name').addEventListener('input', function(){
        const getValue = this.value; // lấy giá trị ô input

        // giá trị id slug nó phải bằng giá trị ô input sau khi hàm trên chuyển đổi
        document.getElementById('slug').value = createSlug(getValue);
    });

</script>

<?php
Layout('footer');
?>

