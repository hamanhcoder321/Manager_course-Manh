<?php
const _Manh = true; // nghĩa là hằng số _Manh này ktra truy cập hợp lệ ko?

// khai báo truy cập folder vs file
const _MODULES = 'dashboard'; 
const _ACTION = 'index'; 


// khai báo hằng số kết nối db
const _HOST = 'localhost';
const _DATABASE = 'manager_manh';
const _USER = 'root';
const _PASSWORD = '';
const _DRIVER = 'mysql'; 

// debug server
const _DEBUG = true;

// thiết lập đường dẫn host url nhập đường dẫn giống search
define('_HOST_URL', 'http://'. $_SERVER['HTTP_HOST'] . '/Manager_course');
define('_HOST_URL_TEMPLATES', _HOST_URL . '/templates');

// thiết lập path (đường dẫn vật lý ổ cứng) nghĩa là folder htdocs trong máy tính 
define('_PATH_URL', __DIR__);
define('_PATH_URL_TEMPLATES', _PATH_URL . '/templates');

