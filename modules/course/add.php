<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

$data = [
    'title' => 'Thêm khóa học'
];
Layout('header', $data);
Layout('sidebar');


if (isPost()) {
    // lọc dữ liệu gọi lại hàm filterData ở fuctions.php gán lại cho biến $filter
    $filter = filterData();
    $err = []; // mảng giá trị đầu là rỗng


    // validate tên
    if (empty(trim($filter['name']))) { // key name ở form, và khoản trắng
        $err['name']['required'] = 'Tên khóa học không được để trống';
    } else {
        // đếm độ dài ký tự
        if (strlen(trim($filter['name'])) < 5) { //ktra gtri tên có lớn hơn 5 ko, bỏ khoảng trắng
            $err['name']['length'] = 'Tên khóa học phải lớn hơn 5 ký tự';
        }
    }


    // validate slug
    if (empty(trim($filter['slug']))) { // key slug ở form, và khoản trắng
        $err['slug']['required'] = 'slug tên không được để trống';
    }


    // validate giá
    if (empty($filter['price'])) {
        $err['price']['required'] = 'giá không được để trống';
    }

    // validate mô tả
    if (empty($filter['description'])) {
        $err['description']['required'] = 'mô tả không được để trống';
    }


    if (empty($err)) {

        // xử lý thumbnail upload lên
        $uploadDir = './templates/uploads/';

        if (!file_exists($uploadDir)) { // tạo file lưu trữ nếu chưa có
            mkdir($uploadDir, 0777, true); // tạo file
        }

        $fileName = basename($_FILES['thumbnail']['name']);

        // đích đến file
        $targetFile = $uploadDir . time() . '-' . $fileName;

        $thumb = '';
        // xử lý lấy file đã tạo vào uploads
        $checkMove = move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile);
        $targetFile = ltrim($targetFile, '.' );
        if ($checkMove) {
            $thumb = $targetFile;
        }

        $dataInsert = [
            'name' => $filter['name'],
            'slug' => $filter['slug'],
            'price' => $filter['price'],
            'description' => $filter['description'],
            'thumbnail' => $thumb,
            'category_id' => $filter['category_id'],
            'created_at' => date('Y:m:d H:i:s')
        ];

        $insertStatus = insert('course', $dataInsert);

        if ($insertStatus) { // nếu thêm thành công báo
            setSessionFlash('msg', 'Thêm khóa học thành công');
            setSessionFlash('msg_type', 'success');
            redirect('?module=course&action=list');
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
                <h2>Thêm khóa học</h2>
                <?php
                if (!empty($msg) && !empty($msg_type)) {
                    getMsg($msg, $msg_type);
                }
                ?>
                <form action="" method="post" enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-6 pb-3">
                            <label for="name">Tên khóa học</label>
                            <input id="name" name="name" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'name');
                            }
                            ?>" placeholder="Tên khóa học">
                            <?php
                            if (!empty($errorArray)) {
                                echo formErr($errorArray, 'name');
                            }
                            ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="slug">Đường dẫn</label>
                            <input id="slug" name="slug" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'slug');
                            }
                            ?>" placeholder="Slug">
                            <?php
                            if (!empty($errorArray)) {
                                echo formErr($errorArray, 'slug');
                            }
                            ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="description">Mô tả khóa học</label>
                            <input id="description" name="description" type="text" class="form-control" value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'description');
                            }
                            ?>" placeholder="Mô tả">
                            <?php
                            if (!empty($errorArray)) {
                                echo formErr($errorArray, 'description');
                            }
                            ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="price">Giá</label>
                            <input id="price" name="price" type="price" class="form-control"
                                placeholder="giá khóa học">
                            <?php
                            if (!empty($errorArray)) {
                                echo formErr($errorArray, 'price');
                            }
                            ?>
                        </div>
                        <div class="col-6 pb-3">
                            <label for="thumbnail">Thumbnail</label>
                            <input id="thumbnail" name="thumbnail" type="file" class="form-control"
                                placeholder="nhập địa chỉ">
                            <img width="200px" id="previewImage" class="preview-image p-3" src="" style="display: none;"
                                alt="">
                        </div>
                        <div class="col-3 pb-3">
                            <label for="group">Lĩnh vực</label>
                            <select name="category_id" id="group" class="form-select form-control">
                                <?php
                                $getGroup = getAll("SELECT * FROM course_category ");
                                foreach ($getGroup as $item):
                                    ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // xử lý slug đường dẫn tự động tạo đường dẫn
    const thumbInput = document.getElementById('thumbnail');
    const previewImg = document.getElementById('previewImage');

    thumbInput.addEventListener('change', function () { // có sự thay đỗi input img
        const file = this.files[0] // file đầu tiên
        if (file) {
            const reader = new FileReader(); // đọc dlieu sẵn
            reader.onload = function (e) {
                previewImg.setAttribute('src', e.target.result);
                previewImg.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
        else {
            previewImg.style.display = 'none';
        }
    });
</script>

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