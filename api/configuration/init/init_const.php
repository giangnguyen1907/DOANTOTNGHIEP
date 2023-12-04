<?php
setlocale(LC_MONETARY, 'vi_VN');

define('FIREBASE_ACCESS_KEY', 'YOUR-SERVER-API-ACCESS-KEY-GOES-HERE');

// Dinh nghia trang thai hoc sinh. Khi lay du lieu xu ly chi so sanh status IN('HT','CT')
define('STUDENT_HT', 'HT'); // Học thử
define('STUDENT_CT', 'CT'); // Học chính thức - đang học
define('STUDENT_TD', 'TD'); // Tam dung
define('STUDENT_TN', 'TN'); // Tốt nghiệp(đã ra trường)
define('STUDENT_TH', 'TH'); // Thôi học
define('STUDENT_GC', 'GC'); // Giu cho

// Trang thai trong lop hoc
define('SC_STATUS_STUDYING', 'DH'); // Dang trong lop hoc

// Hang so
define('CONSTANT_OPTION_DEFAULT_LOGIN', 'DEFAULT_LOGIN');
define('CONSTANT_OPTION_DEFAULT_LOGOUT', 'DEFAULT_LOGOUT');
define('CONSTANT_OPTION_LATE_MONEY', 'LATE_MONEY');
define('CONSTANT_OPTION_FULL_DAY', 'FULL_DAY');
define('CONSTANT_OPTION_NORMAL_DAY', 'NORMAL_DAY');

define('CONSTANT_LOGVALUE_1', '1'); // Đi học
define('CONSTANT_LOGVALUE_0', '0'); // Nghỉ có phép
define('CONSTANT_LOGVALUE_2', '2'); // Nghỉ ko phép

/** Khong chon nguoi than*/
define('LOGIN_RELATIVE_ID_NO', '0');
/** Đón hộ - Không có tên trong danh sách */
define('LOGIN_RELATIVE_ID_INSTEAD', '-1');


// Dinh nghia cho loai dich vu
define('ENABLE_ROLL_SCHEDULE', 1); // Dich vu co thoi khoa bieu - Thay thế bởi 

define('FEATURE_TYPE_ACTIVITI', 0); // Hoat dong
define('FEATURE_TYPE_SUBJECT', 1); // Service - Dich vu co mo lop hoc ServiceCourses

define('FEATUREOPTIONFEATURE_TYPE_INPUT', 2); // Input text
define('FEATUREOPTIONFEATURE_TYPE_CHECKIN', 1); // Check in
                                    
// msg_code result
//define('MSG_CODE_FALSE', 0); // Co loi xay ra
//define('MSG_CODE_TRUE', 1); // Ko loi
//define('MSG_CODE_NOT_REGISTER_DEVICEID', 3); // Chua dang ky DEVICE ID len he thong

// Member status
define('HR_STATUS_WORKING', 'W'); // Dang lam
define('HR_STATUS_LEAVE', 'L');// Da nghi
//
define('USER_NOT_ACTIVE', 0); // Chua kich hoat
define('USER_ACTIVE', 1); // Da kich hoat, dang hoat dong
define('USER_LOCK', 2); // Da kich hoat, bi khoa
                        
// Dinh nghia hang hoat dong - dùng chung
define('STATUS_ACTIVE', 1);
define('STATUS_NOT_ACTIVE', 0);
define('STATUS_LOCK', 2);

define('M_FEE_NOTIFICATION', 0);
define('M_FEE', 1);
define('M_FEE_NEWSLETTER', 2);

define('M_VIEW_FEE_DETAIL', 0);
define('M_VIEW_FEE_TOTAL', 1);
define('M_VIEW_FEE_CATEGORY', 2);

// Camera
define('CAMERA_GLOBAL', 1);
define('CAMERA_NOT_GLOBAL', 0);

// User type
define('USER_TYPE_RELATIVE', 'R');
define('USER_TYPE_TEACHER', 'T');

// Media type of cache data
define('MEDIA_TYPE_TEACHER', '01');
define('MEDIA_TYPE_RELATIVE', '02');
define('MEDIA_TYPE_STUDENT', '03');
define('MEDIA_TYPE_CAMERA', 'camera');
define('MEDIA_TYPE_ARTICLE', 'article');


define('UNIT_HEIGHT', 'cm');
define('UNIT_WEIGHT', 'kg');

// ic_small_notification
define('IC_SMALL_NOTIFICATION', 'ic_small_notification');

// Size Avatar
define('SIZE_FILE_AVATAR', '500KB');

// Default config app
global $APP_CONFIG_VALUES;
$APP_CONFIG_VALUES = array(
		'language' => array(
				'VN',
				'EN'
		),
		'style' => array(
				'green',
				'blue',
				'yellow_orange'
		)
);

define('APP_CONFIG_STYLE', 'green');
define('APP_CONFIG_LANGUAGE', 'VN');

// key salt
define('PS_API_SALT', 'tg5uoORvvWYWPGJlKuldZZjvJF2zWcJt');

// private_key
define('PS_API_PRIVATE_KEY', md5('tg5uoORvvWYWPGJlKuldZZjvJF2zWcJt'));

define('PS_API_HASH_USER_ENCRYPT_KEY', 'MTM2YWIzODY0NjYzOGJlZWI1MjgxOTZlOGM5ZWZjZTcxNzc3YTcyYjQ3ZjVjODZhND');

// Diary
define('PS_CONST_LIMIT_DIARY', 9);

// Max thong bao mot trang
define('PS_CONST_LIMIT_NOTIFICATION', 9);

// Max ngay xem lịch Hoat dong+Lịch học
define('PS_CONST_LIMIT_DAY_FEATURE', 30);

define('PS_CONST_LIMIT_WEEK_SHEDULE', 3);

define('PS_CONST_LIMIT_MONTH_SHEDULE', 10);

define('PS_CONST_LIMIT_DAY_MENU', 20);

// PLATFORM name
define('PS_CONST_PLATFORM_IOS', 'IOS');
define('PS_CONST_PLATFORM_ANDROID', 'ANDROID');

// Max dan do mot trang
define('PS_CONST_LIMIT_ADVICE', 3);

define('PS_CONST_LIMIT_ITEM', 3);

// So item tren trang cho tin tuc
define('PS_CONST_LIMIT_ARTICLE', 5);

// Toàn bộ tin tức: Tin từ Kidsschool + Tin từ nhà trường + tin từ cơ sở đào tạo đăng
define('PS_ARTICLE_ALL', 'all');

// Các bài viết từ KidsSchool đăng (tin từ hệ thống)
define('PS_ARTICLE_GLOBAL', 'global');

define('PS_ARTICLE_SCHOOL', 'school');

// Tin từ cơ sở đăng
define('PS_ARTICLE_BASIC', 'basic');

define('PS_PAYMENT_STATUS_PAID', 1);
define('PS_PAYMENT_STATUS_UNPAID', 0);

define('PS_APP_LIMIT_SELECT_IMAGE', 10);
 

