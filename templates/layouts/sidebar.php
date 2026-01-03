<?php
if (!defined('_Manh')) {
    echo 'truy cập ko hợp lệ';
}
?>
<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <!-- <img src="./assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" /> -->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Học cùng khóa học Manh</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                    
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Khóa học
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./widgets/small-box.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./widgets/info-box.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./widgets/cards.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Lĩnh vực khóa học</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Quản lý tài khoản
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./layout/unfixed-sidebar.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách tài khoản</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/fixed-sidebar.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Tạo mới tài khoản</p>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>
                            Học viên
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./forms/general.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->