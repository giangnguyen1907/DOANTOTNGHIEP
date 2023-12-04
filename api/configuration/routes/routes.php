<?php
// index
$app->get('/', 'DefaultController:index');

// Movo to API web app
$app->get('/image_show/{img}/{path_virtual}/{ps_code}', 'DefaultController:imageShow');
// upload file image
$app->post('/upload_avatar', 'DefaultController:uploadAvatar');
$app->get('/appversion', 'DefaultController:checkAppVersion');
/*
$app->post('/upload', function ($request, $response) {
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['file'];

    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $uploadPath = '../media/'; // Set your upload directory path
        $uploadedFileName = $uploadedFile->getClientFilename();
		
		if (!is_dir($uploadPath))
        mkdir($uploadPath, 0777, true);
        chmod($uploadPath, 0777); 
		
        $uploadedFile->moveTo($uploadPath . $uploadedFileName);
        return $response->withJson(['message' => 'File uploaded successfully']);
    } else {
        return $response->withJson(['error' => 'File upload failed'], 400);
    }
});
*/
// BEGIN: ps_user group
$app->group('/ps_user', function () {
	
	$this->post('/upload_avatar', 'UserController:uploadAvatar');
	
	// index
	$this->get('', 'UserController:index');

	$this->get('/home_relative', 'UserController:HomeRelative');

	// home
	$this->get('/home', 'UserController:home');

	// login
	$this->post('/login', 'UserController:do_login');

	// logout
	$this->post('/logout', 'UserController:do_logout');

	// Active app
	$this->post('/active', 'UserController:registerDeviceId');

	// forgot password - Y/c cap lai mat khau
	$this->put('/forgot_password', 'UserController:forgotPassword');

	// reset password
	// $this->put('/reset_password', 'UserController:resetPassword');

	// change password
	$this->put('/change_password', 'UserController:changePassword');

	// notification update notification token
	$this->put('/notification', 'UserController:updateNotificationToken');

	// get user profile
	$this->get('/profile', 'UserController:userProfile');

	// update user profile
	$this->put('/profile', 'UserController:updateProfile');

	// update avatar
	$this->post('/avatar', 'UserController:avatar');

	// get relatives - Lay danh sach nguoi than cua 1 hoc sinh
	$this->get('/relatives/{student_id}/student', 'UserController:informationRelatives');

	$this->get('/relatives/{student_id}', 'UserController:informationRelatives'); // v-iOS

	// get relative - Chi tiet nguoi than cua hoc sinh
	$this->get('/relative/{student_id}/{relative_id}', 'UserController:informationRelative');
	$this->get('/relatives/{student_id}/{relative_id}', 'UserController:informationRelative'); // v-iOS

	// update language
	$this->put('/language', 'UserController:config');

	// update style
	$this->put('/style', 'UserController:config');
});
// END: ps_user group

// BEGIN: ps_student group
$app->group('/ps_student', function () {
	
	$this->get('/newfeed/{student_id}', 'StudentController:newfeedStudent');
	
	// get student
	$this->get('/{student_id}', 'StudentController:informationStudent');

	// get home growth of student
	// $this->get('/{student_id}/homegrowth', 'StudentController:homeGrowthStudent');

	//get examination
	$this->get('/{student_id}/growth/examination', 'StudentController:growthExaminationStudent');
	// $this->get('/examination', 'StudentController:growthExaminationStudent');
	// get growth of student
	$this->get('/{student_id}/growth', 'StudentController:growthStudent');

	$this->get('/growth/{student_id}', 'StudentController:growthStudent'); // v-iOS

	// Bang tang truong chieu cao
	$this->get('/{student_id}/growth_height', 'StudentController:growthHeightStudent');

	// Bang tang truong can nang
	$this->get('/{student_id}/growth_weight', 'StudentController:growthWeightStudent');

	// Bieu do chieu cao
	$this->get('/{student_id}/growth_chart/height', 'StudentController:growthChartHeightStudent');

	// Bieu do can nang
	$this->get('/{student_id}/growth_chart/weight', 'StudentController:growthChartWeightStudent');

	/**
	 * NEW URI diary
	 */
	// get diary of student - Diem danh/Nhat ky bat dau ngay hom nay
	$this->get('/diary/{student_id}', 'StudentController:diarysStudent'); // v-iOS

	// Xem diem danh cua trang ke tiep
	$this->get('/diary/{student_id}/{page}', 'StudentController:diarysStudent'); // v-iOS

	// get diary of student - Diem danh/Nhat ky bat dau ngay hom nay
	//$this->get('/{student_id}/diary', 'StudentController:diaryStudent');
	$this->get('/{student_id}/diary', 'StudentController:diaryStudentAndroid');

	// Xem diem danh cua trang ke tiep
	//$this->get('/{student_id}/{page}/diary', 'StudentController:diaryStudent');
	$this->get('/{student_id}/{page}/diary', 'StudentController:diaryStudentAndroid');

	// URI Nhat ky diem danh moi $month = 202308
	$this->get('/attendance/{student_id}/{month}', 'StudentController:diaryAttendanceMonthStudent'); //

	// Xem thong tin giao vien
	$this->get('/{m_id}/teacher', 'StudentController:teacherStudent');
	$this->get('/teachers/{m_id}', 'StudentController:teacherStudent'); // v-iOS

	// get Services of Student - Dich vu trong nha truong hoc sinh
	$this->get('/{student_id}/services', 'StudentController:servicesStudent');
	$this->get('/services/{student_id}', 'StudentController:servicesStudent'); // v-iOS

	// get Services is user of Student - Lay dich vu dang su dung cua hoc sinh
	$this->get('/{student_id}/services_used', 'StudentController:servicesUsedStudent');
	$this->get('/services_used/{student_id}', 'StudentController:servicesUsedStudent'); // v-iOS

	// get Services unregister of Student - Lay dich vu chua dang ky cua hoc sinh
	$this->get('/{student_id}/service_unregister', 'StudentController:servicesUnregisterStudent');
	$this->get('/service_unregister/{student_id}', 'StudentController:servicesUnregisterStudent'); // v-iOS

	// get Service detail - Chi tiet 1 dich vu
	$this->get('/{student_id}/service/{service_id}', 'StudentController:serviceStudent');
	$this->get('/services/{student_id}/{service_id}', 'StudentController:serviceStudent'); // v-iOS

	// remove Service of student- Huy hoac dang ky 1 dich vu da dang ky cua hoc sinh
	// $this->post('/{student_id}/service', 'StudentController:removeServiceStudent');

	$this->post('/service', 'StudentController:removeServiceStudent');

	// Dang ky 1 dich vu
	$this->post('/services', 'StudentController:addServiceStudent'); // v-iOS
	$this->delete('/services/{service_student_id}', 'StudentController:deleteServiceStudent'); // Huy dich vu v-iOS

	// Lay thong tin menu ngay hom nay - Thuc don trong ngay
	// $this->get('/{student_id}/{page_day}/menus', 'StudentController:menuStudent');

	// Lay thong tin menu ngay hom nay - Thuc don theo ngay
	$this->get('/menus/{student_id}', 'StudentController:menuStudent'); // v-iOS
	$this->get('/menus/{student_id}/{date}', 'StudentController:menuStudent'); // v-iOS

	// Lay thong tin hoat dong cua mot ngay
	$this->get('/{student_id}/{page_day}/features', 'StudentController:featureStudent');

	$this->get('/features/{student_id}/{page_day}', 'StudentController:featureStudent'); // v-iOS

	// Danh gia cuoi ngay
	//$this->get('/today/{student_id}', 'StudentController:todayStudent');
	$this->get('/today/{student_id}', 'StudentController:commentOfStudent');

	// get Report fees of Student - Bao phi cua hoc sinh thang nam hien tai	
	$this->get('/{student_id}/report_fees', 'StudentController:feeStudent'); // Android
	// Bao phi cua hoc sinh theo thang- nam
	$this->get('/{student_id}/{date}/report_fees', 'StudentController:feeStudent'); // Android

	// Bao phi cua hoc sinh thang nam hien tai
	$this->get('/report_fees/{student_id}', 'StudentController:feeStudent'); // v-iOS
	// Bao phi cua hoc sinh theo thang- nam
	$this->get('/report_fees/{student_id}/{date}', 'StudentController:feeStudent'); // v-iOS

	// Tai bao phi
	$this->get('/{student_id}/{date}/download_report_fees', 'StudentController:downloadReportFees');

	$this->get('/download_report_fees/{student_id}/{date}', 'StudentController:downloadReportFees'); // v-iOS

	// Lấy danh sách hoat động mới theo ngày
	$this->get('/{student_id}/active/{date}', 'StudentController:ClassActive');

	/*
	 * Lay danh sach cac camera cua lop hoc
	 * $student_id - ID cua hoc sinh
	 */
	$this->get('/{student_id}/class_camera', 'StudentController:classCamera');
	$this->get('/class_camera/{student_id}', 'StudentController:classCamera'); // v-iOS

	/*
	 * Lay danh sach cac camera ngoai canh
	 * $student_id - ID cua hoc sinh
	 */
	$this->get('/{student_id}/global_camera', 'StudentController:globalCamera');
	$this->get('/global_camera/{student_id}', 'StudentController:globalCamera'); // v-iOS

	// Chi tiet 1 camera
	$this->get('/{student_id}/camera/{camera_id}', 'StudentController:cameraPlay');
	$this->get('/camera/{student_id}/{camera_id}', 'StudentController:cameraPlay'); // v-iOS


	$this->get('/{student_id}/{month}/schedule', 'StudentController:studentScheduleForAndroid'); // Android	

	$this->get('/schedule/{student_id}', 'StudentController:studentScheduleForIos'); // v-iOS
	$this->get('/schedule/{student_id}/{month}', 'StudentController:studentScheduleForIos'); // v-iOS ; month = YYYYMM

	$this->get('/newschedule/{student_id}/{month}', 'StudentController:studentScheduleTest');
});

// BEGIN: ps_teacher group
$app->group('/ps_teacher', function () {

	/** VESION 2.1.0 **/
	$this->get('/home_teacher', 'TeacherController:HomeTeacher');



	/**END: VESION 2.1.0 **/

	// index giao vien
	$this->get('/', 'TeacherController:home');
	$this->get('/home', 'TeacherController:home');

	$this->get('/test', 'TeacherController:homeTest');

	// Lay thong tin diem danh theo lop thêm ?attendancetype=in or ?attendancetype=out
	$this->get('/attendance', 'TeacherController:attendancefastStudent');

	// Luu diem danh theo lop
	// $this->post('/attendancefast', 'TeacherController:saveAttendance');
	$this->post('/attendance', 'TeacherController:saveAttendance');

	// Lay thong tin diem danh tung hoc sinh thêm đuôi params ?attendancetype=out or in 
	$this->get('/attendances/{student_id}', 'TeacherController:attendanceStudent');

	// Diem danh tung hoc sinh
	$this->post('/attendances', 'TeacherController:saveAttendanceStudent');

	// Lich giao vien: Bao gom lich giang day va lich tham gia cac hoat dong
	//$this->get('/feature', 'TeacherController:featureTeacher'); // Ngay hom nay - today
	$this->get('/features', 'TeacherController:featureTeacher'); // v-iOS

	// Lich giang day theo ngay
	//$this->get('/{page_day}/feature', 'TeacherController:featureTeacher');	
	$this->get('/features/{page_day}', 'TeacherController:featureTeacher'); // v-iOS

	// Hien thi Danh gia hoat dong hoc sinh
	//$this->get('/{feature_id}/rate_feature', 'TeacherController:rateFeatureStudent');
	// Luu danh gia hoat dong hoc sinh
	//$this->post('/{feature_id}/rate_feature', 'TeacherController:saveRateFeatureStudent');

	// Hien thi Danh gia hoat dong hoc sinh
	$this->get('/rate_feature/{feature_id}', 'TeacherController:rateFeatureStudent'); // v-iOS	
	// Luu danh gia hoat dong hoc sinh
	$this->post('/rate_feature/{feature_id}', 'TeacherController:saveRateFeatureStudent'); // v-iOS

	$this->post('/rate_feature', 'TeacherController:saveRateFeatureStudent'); // v-iOS

	// Nhan xet mon hoc hoc sinh
	$this->get('/{course_schedules_id}/comment_service', 'TeacherController:commentServiceStudent');
	$this->get('/comment_service/{course_schedules_id}', 'TeacherController:commentServiceStudent'); // v-iOS

	// Luu nhan xet mon hoc hoc sinh
	$this->post('/{course_schedules_id}/comment_service', 'TeacherController:saveCommentServiceStudent');
	$this->post('/comment_service/{course_schedules_id}', 'TeacherController:saveCommentServiceStudent'); // v-iOS


});

// BEGIN: ps_cms group
$app->group('/ps_cms', function () {

	// So thong bao chua doc - chi tinh voi thong bao nhan
	$this->get('/notread', 'CmsNotificationController:notRead'); 

	$this->get('/notreadteacher', 'CmsNotificationController:notReadAppT');

	// Lay danh sach thong bao: ntype Xac dinh gui hoac nhan
	$this->get('/notifications/{ntype}', 'CmsNotificationController:listNotifications');

	$this->get('/notifications/{ntype}/{page}', 'CmsNotificationController:listNotifications'); // phan trang

	// Lay chi tiet 1 notification theo n_id
	$this->get('/notifications/show/{ntype}/{n_id}', 'CmsNotificationController:detail');

	// Lay danh sach nguoi gui - danh cho app giao vien
	$this->get('/listsend', 'CmsNotificationController:listUserSendForTeacher');

	// Lay danh sach nguoi gui - danh cho app phu huynh
	$this->get('/listsend/{student_id}', 'CmsNotificationController:listUserSendForRelative');

	// Gui thong bao toi nguoi duoc chon
	$this->post('/notifications', 'CmsNotificationController:send');

	// Xoa thong bao
	$this->delete('/notifications/{ntype}/{n_id}', 'CmsNotificationController:delete');
});

// BEGIN: ps_message group
$app->group('/ps_message', function () {

	// Lay danh sach nguoi chat - danh cho app phu huynh
	$this->get('/users_chat/{student_id}', 'CmsChatController:listUserSendForRelative');

	// Lay danh sach nguoi chat - danh cho app giao vien
	$this->get('/users_chat', 'CmsChatController:listUserSendForTeacher');

	// Bao co tin nhan
	$this->post('/notification_chat', 'CmsChatController:pushNotificationChat');
	$this->post('/push_chat', 'CmsChatController:pushChat');
});

// BEGIN: ps_albums group
$app->group('/ps_albums', function () {

	// Lay danh sach album duoc phep xem cua giao vien
	$this->get('/album', 'AlbumController:listAlbums');

	//Hien thi chi tiet mot album
	$this->get('/album/{album_id}', 'AlbumController:showAlbumDetail');

	// Tao album
	$this->post('/album', 'AlbumController:addAlbum'); 

	// Tao album + upload anh
	$this->post('/album/create', 'AlbumController:createAlbum');


	// Lay danh sach album duoc phep xem theo 1 hoc sinh - danh cho phu huynh
	$this->get('/album/student/{student_id}', 'AlbumController:listAlbums');

	// Xoa album
	$this->delete('/album/{album_id}', 'AlbumController:deleteAlbum');

	// Hien thi danh sach album trong lop - cua giao vien
	$this->get('/album/class/{ps_class_id}', 'AlbumController:showAlbumsOfClass');

	// Chinh sua title va note cua mot album
	$this->put('/album/{album_id}', 'AlbumController:updateAlbum');

	// Update trang thai album
	$this->put('/album/status/{album_id}', 'AlbumController:updateStatusAlbum');

	// upload anh len album
	$this->post('/album/upload', 'AlbumController:uploadItemToAlbum');

	// Update trang thai album item
	$this->put('/albumitem/status/{img_id}', 'AlbumController:updateStatusAlbumItem');

	// Xoa anh
	$this->delete('/albumitem/{img_id}', 'AlbumController:deleteAlbumItem'); 

	//like album 
    $this->post('/album/like', 'AlbumController:likeToAlbum');
    //binh luan album
    $this->get('/albums/show_comment', 'AlbumController:showCommentToAlbum'); 

    $this->post('/album/comment', 'AlbumController:SaveComment');

    $this->delete('/albums/bl/{comment_id}', 'AlbumController:deleteComment');

}); 

//san pham cho be 

$app->group('/ps_products', function (){ 

	//Hiển danh sach san pham 
	$this->get('/showlist', 'PsCmsProductsController:getProducts'); 
	//Hiển thị chi tiết sản phẩm params id = ?
	$this->get('/detail', 'PsCmsProductsController:getProduct'); 

});

// BEGIN: ps_advice group - dặn dò
$app->group('/ps_advice', function () {

	// Gui dan do cho giao vien
	$this->post('/advice', 'AdviceController:sendAdvice');
    
	// Hiển thị chi tiết 1 dặn dò 
	$this->get('/advices/show/{advice_id}', 'AdviceController:detailAdvice');

	// Số dặn dò chưa đọc - app giáo viên
	$this->get('/advices/notread','AdviceController:numberNotRead');

	// Danh sách dặn dò -- app phu huynh
	$this->get('/advices/{student_id}', 'AdviceController:listAdvices');

	$this->get('/advices/{student_id}/{page}', 'AdviceController:listAdvices');

	//Danh sách dặn dò app giáo viên
	$this->get('/teacher/advices', 'AdviceController:listAdvicesForTeacher');

	$this->get('/teacher/advices/{page}', 'AdviceController:listAdvicesForTeacher');

	// Danh sach người để gửi dặn dò -- app phụ huynh
	$this->get('/listsend/{student_id}', 'AdviceController:listUserSendForRelative');

	// Danh mục dan do
	$this->get('/a_categories', 'AdviceController:adviceCategories'); // APP giao vien
	$this->get('/a_categories/{student_id}', 'AdviceController:adviceCategories'); // APP phu huynh

	// Xac nhan dan do
	$this->put('/confirm/{advice_id}', 'AdviceController:confirmAdvice');
});

// BEGIN: ps_offschool group - Bao nghi hoc
$app->group('/ps_offschool', function () {

	// Gửi yêu cầu xin nghỉ học
	$this->post('/offschool', 'OffSchoolController:sendOffSchool');

	// Hiển thị chi tiết 1 yêu cầu xin nghỉ học
	$this->get('/offschool/show/{offschool_id}', 'OffSchoolController:detailOffSchool');

	// Danh sach người nhận để gửi y/c xin nghỉ học -- app phụ huynh
	$this->get('/listsend/{student_id}', 'OffSchoolController:listUserSendForRelative');

	// Danh sach xin nghỉ học -- phụ huynh
	$this->get('/offschool/{student_id}', 'OffSchoolController:listOffSchool');

	$this->get('/offschool/{student_id}/{page}', 'OffSchoolController:listOffSchool');

	// Danh sach xin nghỉ học -- cua giao vien
	$this->get('/teacher/offschool', 'OffSchoolController:listOffSchoolForTeacher');

	$this->get('/teacher/offschool/{page}', 'OffSchoolController:listOffSchoolForTeacher');

	// Sửa xin nghỉ học
	$this->put('/offschool/edit/{offschool_id}', 'OffSchoolController:editOffSchool');

	// Xóa xin nghỉ học
	$this->delete('/offschool/delete/{offschool_id}', 'OffSchoolController:deleteOffSchool');

	// Xác nhận nghỉ học
	$this->put('/offschool/confirm/{offschool_id}', 'OffSchoolController:confirmOffSchool');
});

// Tin tức
$app->group('/ps_articles', function () {

	//Lay danh sach tin tuc theo loại: type = global (tin tức chung trong hệ thống KidsSchool); type=school; type = basic; type = all - tất cả tin tức: KidsSchool + school

	//Lay danh sach tin tuc theo trang, ban đầu page = 1; type - do APIs trả về 
	// link /list/{type}/{page}/?student_id=(id học sinh)
	$this->get('/list/{type}/{page}', 'PsCmsArticlesController:getArticles');

	//get article by id
	// /{id}/?student_id=(id học sinh)
	$this->get('/{id}', 'PsCmsArticlesController:getArticle');
	
	$this->get('/category/{id}', 'PsCmsArticlesController:getListCategory');
	
});

/**/
// Nhận xét
$app->group('/ps_review', function () {
	
	// Danh sách danh mục đánh giá - theo trường
	$this->get('/category', 'ReviewController:getListCategory');
	
	// Option lựa chọn đánh giá theo danh mục
	// Param category_id
	$this->get('/option', 'ReviewController:getListOption');
	
	// Lưu đánh giá của học sinh
	$this->post('/save_option', 'ReviewController:saveOption');
	
	// Danh sách nhận xét theo danh mục
	// Param student_id = null , category_id, date_at
	$this->get('/list_review', 'ReviewController:getListReview');
	
	// Sửa nhận xét
	$this->put('/edit/{id}', 'ReviewController:editReview');
	
	$this->post('/save_edit', 'ReviewController:saveEditReview');
	
	// Xóa nhận xét
	$this->delete('/delete/{id}', 'ReviewController:deleteReview');
	
	// Phụ huynh xem nhận xét của con
	// Param student_id = null , category_id, date_at
	$this->get('/relative_view', 'ReviewController:getRelativeView');
	
	
	/////////////////////////////////////////////////////////
	//lấy ra các tuần trong năm params year_data , month_data
	$this->get('/get_week', 'ReviewController:getWeekofYear');


	// Thêm mới nhận xét tháng tuần 


	$this->post('/save_comment_week', 'ReviewController:saveCommentWeek');
	
	// Sửa nhận xét tháng tuần
	$this->put('/comment_week/{id}', 'ReviewController:editCommentWeek');
	
	//  Lưu chỉnh sửa
	$this->post('/comment_week_save_edit', 'ReviewController:saveEditCommentWeek');
	
	// Danh sách nhận xét tháng - tuần theo lớp
	$this->get('/list_coment_week', 'ReviewController:getListCommentWeek');
	
	// Xóa nhận xét tháng tuần
	$this->delete('/delete_comment/{id}', 'ReviewController:deleteCommentWeek');
	
});

