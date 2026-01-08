<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

$data = [
    'title' => 'Danh sách tài khoản'
];
Layout('header', $data);
Layout('sidebar');


// biến lấy all dlieu users ra giao diện (a là bảng users,b là groups viết tắt các bảng) inner join
$getDetailUser = getAll("SELECT a.id, a.fullname, a.email, a.created_at, b.name FROM 
users a INNER JOIN `groups` b ON a.group_id = b.id
ORDER BY a.created_at DESC
");

?>

<div class="container grid-user">
    <div class="container-fuild">
        <div class="plus-search">
            <form action="" method="get">
                <div class="row mb-3">
                    <div class="col-3 form-group">
                        <select class="form-select form-control" name="" id="">
                            <option value="">Nhóm người dùng</option>
                            <option value="">1</option>
                        </select>
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control" placeholder="Nhập thông tin tìm kiếm...">
                    </div>
                    <div class="col-2"><button class="btn btn-primary" type="submit">Tìm kiếm</button></div>
                </div>
            </form>
            <a href="?module=users&action=add" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i> Thêm mới
                tài khoản</a>
        </div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Họ Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ngày đăng ký</th>
                    <th scope="col">Nhóm</th>
                    <th scope="col">Phân quyền</th>
                    <th scope="col">Sửa</th>
                    <th scope="col">xóa</th>
                </tr>
            </thead>
            <tbody>
                <!--duyệt mảng-->
                <?php
                // duyệt và lấy dlieu mảng con item
                foreach ($getDetailUser as $key => $item): // key là id, item dlieu
                    ?>
                    <tr>
                    <!--key+1 vì id bắt đầu số 0 nên + 1 đơn vị lấy 1-->
                        <th scope="row"><?php echo $key+1; ?></th>
                        <td><?php echo $item['fullname']; ?></td>
                        <td><?php echo $item['email']; ?></td>
                        <td><?php echo $item['created_at']; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><a href="?module=users&action=permission&id=<?php echo $item['id']; ?>" class="btn btn-primary">Phân quyền</a></td>
                        <td><a href="?module=users&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>
                        <td><a href="?module=users&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('bạn chắc chắn muốn xóa không?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>
                    <?php
                endforeach;
                ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" style="justify-content: center;">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>

</div>

<?php
Layout('footer');
?>