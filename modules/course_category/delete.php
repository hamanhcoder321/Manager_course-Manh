<?php
if(!defined('_Manh')){
    echo 'truy cập ko hợp lệ'; 
}
/*
-lấy id url
- ktra trong bảng course_category có cái lĩnh vực id này ko
- có --> check trong bảng course xem có khóa học nào đang có category_id ko
- có khóa học --> danh mục này đang còn khóa học
- nếu ko có khóa học --> xóa danh mục đi
*/

$filter = filterData('get'); // lấy url id
if(!empty($filter)){
    $cateId = $filter['id'];

    $checkCate = getOne("SELECT * FROM course_category WHERE id = $cateId");

    if(!empty($checkCate)){ // có dlieu ko 
        $checkCourse = getRows("SELECT * FROM course WHERE category_id = $cateId");
        if($checkCourse > 0){
            // còn tồn tại khóa học
            setSessionFlash('msg', ' danh mục khóa học còn tồn tại khóa học.');
            setSessionFlash('msg_type', 'danger');
            redirect('?module=course_category&action=list');
        }else{
            $deleteStatus = delete('course_category', "id = $cateId");
            if($deleteStatus){
                setSessionFlash('msg', 'xóa danh mục khóa học thành công.');
                setSessionFlash('msg_type', 'success');
                redirect('?module=course_category&action=list');
            }
        }
    }else{
        setSessionFlash('msg', ' danh mục khóa học không tồn tại.');
        setSessionFlash('msg_type', 'danger');
        redirect('?module=course_category&action=list');
    }
}else{
    setSessionFlash('msg', 'đã có lỗi xảy ra, vui lòng thử lại sau.');
    setSessionFlash('msg_type', 'danger');
}
