<?php
/**
 * Dinh nghia cac gia tri hang so goc. Khong duoc phep xoa
 */
class PreConst {

	public static function InitPsConst() {

		return array (
				'DEFAULT_LOGIN', // Thoi gian vao hoc
				'DEFAULT_LOGOUT', // Thoi gian ket thuc hoc tap chinh khoa. Ngoai thoi gian nay se tinh phi trong ngoai gio
				'LATE_MONEY', // Gia tien thu neu ve muon
				'FULL_DAY', // So ngay tam tinh neu di hoc ngay thu 7
				'NORMAL_DAY' ); // So ngay tam tinh neu khong di hoc ngay thu 7
	}

	// Ham tra ve ma dinh nghia cac chức năng
	public static function InitAppPermissionCode() {

		return array (
			'SHOW' => _ ( 'See the list' ), // Xem danh sách
			'DETAIL' => _ ( 'See details' ), // Xem chi tiết
			'ADD' => _ ( 'Add new' ), // Thêm mới
			'EDIT' => _ ( 'Edit' ), // Sửa
			'EDIT_DETAIL' => _ ( 'Edit detail' ), // Sửa chi tiết - một số thuộc tính đặc biệt
			'DELETE' => _ ( 'Delete' ), // Xóa
			'RESTORE' => _ ( 'Restore' ), // Khôi phục
			'RESET_PASSWORD' => _ ( 'Reset password' ), // Cấp lại mật khẩu
			//'MANAGER_LEVEL_DEPARTMENT' => _ ( 'Manager department' ), // Quản lý cấp Sở giáo dục/ Phòng giáo dục
			'MANAGER_DEPARTMENT' => _ ( 'Manager department' ), // Quản lý Sở giáo dục/ Phòng giáo dục
			'MANAGER_SUB_DEPARTMENT' => _ ( 'Manager sub department' ), // Dành cho User của Sở /Phòng
			'FILTER_SCHOOL' => _ ( 'Filter information by school' ), // Lọc dữ liệu theo trường của mỗi module
			//'MANAGER_FILTER_SCHOOL' => _ ( 'Filter information by school' ), // Lọc dữ liệu của trường chung. Neu co quyen nay thi nguoi dung chi co quyen loc du lieu chung
			'FILTER_WORKPLACES' => _ ( 'Filter information by basic' ), // Quản lý dữ liệu của các cơ sở của trường
			'FILTER_WORKPLACE' 	=> _ ( 'Filter information one basic' ), // Quản lý dữ liệu của một cơ sở được chỉ định
			'PUBLISH' 			=> _ ( 'Publish' ), // Duyệt xuất bản nội dung
			'LOCK' 				=> _ ( 'Lock' ), // Khóa nội dung
			'GLOBAL'			=> _ ('Global'),
			//'IS_GLOBAL' 		=> _(''),
			'CASHIER' 			=> _ ( 'Cashier' ), // Thu ngân - Lưu thanh toán học phí
			'PUSH' 				=> _ ( 'Push' ), // Gui notication đến APP
			'REGISTER_STUDENT'  => _ ( 'Enter this information for students' ), // Gán các dữ liệu: Người thân, dịch vụ cho học sinh
			'STATISTIC' => _ ( 'Statistic' ), // Thống kê
			'SETUP' => _ ( 'Setup' ),
			'REMOVE' => _ ( 'Remove' ), // Gỡ
			'ROLE' => _ ( 'Decentral (User and Group users only)' ),
			'SYSTEM' => _ ( 'All system' ), // Liên quan tới toàn hệ thống
			'ALL' => _ ( 'All' ), // Tất cả
			'WORKPLACE' => _ ( 'Basic' ), // Cơ sở
			'IMPORT' => _ ( 'Import data' ), // Import du lieu
			'EXPORT' => _ ( 'Export data' ), // xuat ra file xls
			'IMPORT_LAST_MONTH' => _ ( 'Import last month' ), // Import du dau ky
			'DELAY' => _ ( 'Delay' ) ); // Dành cho phân hệ điểm danh => Điểm danh về muộn
	}
	
	/**  **/
	public static function InitCronCode() {
		
		return array (
				'CRON_SCHEDULE_STUDENT',// Gui lich hoc cua hoc sinh
				'CRON_GOODMORNING_TEACHER', // Gui chao giao vien đầu ngày
				'CRON_HAPPY_BIRTHDAY_STUDENT', // Chuc mung sinh nhat
				'CRON_SCHEDULE_TEACHER' ); // Lich cua giao vien
	}
}