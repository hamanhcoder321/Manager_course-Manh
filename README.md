/*
hệ thống quản lý khóa học

thiết kế database

users:

- id int primary key
- fullname varchar 200
- email varchar 100
- phone varchar 50
- address varchar 500
- forget_token varchar 500
- active_token varchar 500
- status int (1: đã kích hoạt, 0: chưa kích hoạt)
- permission text --> quyền truy cập khóa hoc liên kết tới bảng id khóa học

- group_id int --> liên kết tới bảng group 
- created_at datetime
- updated_at datetime

Token_login:

- id int primary key
- user_id int --> liên kết bảng users tới cột forget_token

- token varchar 200
- created_at datetime
- updated_at datetime

Course:

- id int primary key
- name varchar 100
- slug varchar 100
- category_id int --> liên kết bảng Course_category
- description text
- price int
- thumbnail varchar 200
- created_at datetime
- updated_at datetime

Course_category:

- id int primary key
- name varchar 100
- slug varchar 100
- created_at datetime
- updated_at datetime

permission: //

groups:

- id int primary key
- name varchar 100
- created_at datetime
- updated_at datetime

*/

- code tính năng đăng ký và xly dlieu đầu vào
- thêm dlieu vào bảng users trong db
- gửi email kích hoạt tài khoản
- click vào link kích hoạt -> xly active tài khoản
