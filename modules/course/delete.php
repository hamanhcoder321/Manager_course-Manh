<?php
if(!defined('_Manh')){
    echo 'truy cập ko hợp lệ'; 
}

$filter = filterData('get');
if(!empty($filter)){ // filter này có giá trị ko rỗng
    $course_id = $filter['id'];
    $checkCourse = getOne("SELECT * FROM course WHERE id = $course_id");
    if(!empty($checkCourse)){ // nếu có course thực hiện xóa
        $deleteStatus = delete('course', "id = $course_id");
        if($deleteStatus){
            setSessionFlash('msg', 'xóa khóa học thành công.');
            setSessionFlash('msg_type', 'success');
            redirect('?module=course&action=list');
        }
    }else{
        setSessionFlash('msg', 'khóa học không tồn tại.');
        setSessionFlash('msg_type', 'danger');
        redirect('?module=course&action=list');
    }
}else{
    setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
    setSessionFlash('msg_type', 'danger');
}