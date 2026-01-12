<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}

$data = [
    'title' => 'Phân quyền'
];
Layout('header', $data);
Layout('sidebar');


$filterGet = filterData('get'); // lấy id từ url
if(!empty($filterGet['id'])){
    $idUser = $filterGet['id']; // có id ko
    
    $checkId = getOne("SELECT * FROM users WHERE id = $idUser");

    if(empty($checkId)){ // ko có id tài khoản đó quay lại danh sách
        redirect('?module=users&action=list');
    }
}else{
    setSessionFlash('msg', 'tài khoản không tồn tại');
    setSessionFlash('msg_type', 'danger');
}

// lấy dạng mảng cho value checkbox 
if(isPost()){
    $filter = filterData();
    

    if(!empty($filter['permission'])){
        $permission = json_encode($filter['permission']); // chuyển mảng về json
        
    }else{
        $permission = ''; // ko có dlieu = rỗng
    }


    // update bảng users permission
    $dataUpdate = [
        'permission' => $permission,
        'updated_at' => date('Y:m:d H:i:s')
    ];

    $condition = "id=". $idUser;
    $checkUpdate = update('users', $dataUpdate, $condition);

    if($checkUpdate){
        setSessionFlash('msg', 'phân quyền tài khoản thành công');
        setSessionFlash('msg_type', 'success');
        redirect("?module=users&action=permission&id=$idUser");
    }else{
        setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau');
        setSessionFlash('msg_type', 'danger');
    }
}
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');

if(!empty($checkId['permission'])){
    $permissionOld = json_decode($checkId['permission'], true); // chuyển chuỗi thành mảng
}

?>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid p-3">
            <div class="table-responsive">
                <div class="container">
                    <form action="" method="post">
                        <?php 
                            if(!empty($msg) && !empty($msg_type)){
                                getMsg($msg, $msg_type);
                            }
                        ?>
                        <table class="table table-borderd">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>khóa học</th>
                                    <th>Phân quyền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $getDetailCourse = getAll("SELECT id, name FROM course");
                                $dem = 1;
                                foreach ($getDetailCourse as $item):
                                    ?>
                                    <tr>
                                        <td><?php echo $dem;
                                        $dem++; ?></td>
                                        <td><?php echo $item['name']; ?></td>
                                        <td>
                                            <input type="checkbox" name="permission[]" <?php echo (!empty($permissionOld)) && in_array($item['id'], $permissionOld) ?  'checked' : false; ?> value="<?php echo $item['id']; ?>">
                                        </td> <!--check xanh id nó mà nằm trong danh sách lần lặp $permissionOld nếu trong 2 xảy ra hiện check xanh -->                                             
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a class="btn btn-success" href="?module=users&action=list">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
Layout('footer');
?>