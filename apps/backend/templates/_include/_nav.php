<?php
$APP_URL = url_for ( '@homepage' );

$breadcrumbs = array (
		"Home" => $APP_URL );

// Xu ly bo sung quyen hien thi, xu ly: add, edit, delete cho GV neu duoc phan cong phu trach lop, khoa hoc
/**
 * * BEGIN: Kiem tra phan cong lop hoc **
 */
$access_ps_logtimes = $sf_user->hasCredential ( array (
		'PS_STUDENT_ATTENDANCE_SHOW',
		'PS_STUDENT_ATTENDANCE_ADD',
		'PS_STUDENT_ATTENDANCE_EDIT',
		'PS_STUDENT_ATTENDANCE_DELETE',
		'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL',
		'PS_STUDENT_ATTENDANCE_TEACHER' ), // truong hop giao vien duoc phan cong phu trach lop
false );
/*
 * $access_constant = $sf_user->hasCredential ( array (
 * 'PS_SYSTEM_CONSTANT_SHOW',
 * 'PS_SYSTEM_CONSTANT_DETAIL',
 * 'PS_SYSTEM_CONSTANT_ADD',
 * 'PS_SYSTEM_CONSTANT_EDIT',
 * 'PS_SYSTEM_CONSTANT_DELETE',
 * 'PS_SYSTEM_CONSTANT_OPTION_SHOW',
 * 'PS_SYSTEM_CONSTANT_OPTION_DETAIL',
 * 'PS_SYSTEM_CONSTANT_OPTION_ADD',
 * 'PS_SYSTEM_CONSTANT_OPTION_EDIT',
 * 'PS_SYSTEM_CONSTANT_OPTION_DELETE' ), false );
 */
$defined_application = $sf_user->hasCredential ( array (
		'PS_SYSTEM_APPLICATION_SHOW',
		'PS_SYSTEM_APPLICATION_DETAIL',
		'PS_SYSTEM_APPLICATION_ADD',
		'PS_SYSTEM_APPLICATION_EDIT',
		'PS_SYSTEM_APPLICATION_DELETE' ), false );

$defined_function_application = $sf_user->hasCredential ( array (
		'PS_SYSTEM_APP_PERMISSION_SHOW',
		'PS_SYSTEM_APP_PERMISSION_DETAIL',
		'PS_SYSTEM_APP_PERMISSION_ADD',
		'PS_SYSTEM_APP_PERMISSION_EDIT',
		'PS_SYSTEM_APP_PERMISSION_DELETE' ), false );

$page_nav = array ();

$page_nav ['root_dashboard'] = array (
		"title" => __ ( "Dashboard" ),
		"url" => $APP_URL,
		"active" => PreSchool::askActiveMenu ( 'psCpanel' ),
		"access" => true,
		"icon" => "fa-home" );
		
$page_nav ['review'] = array (
	"title" => 'Nhận xét',
	"url" => '#',
	"active" => PreSchool::askActiveMenu ( 'psCategoryReview' ) || PreSchool::askActiveMenu ( 'psReviewRelative' ) || PreSchool::askActiveMenu ( 'psReview' ),
	"access" => true,
	"icon" => "fa-folder-open-o",
	"sub" => array(
		"ps_category_review" => array (
			"title" => __ ( 'Danh mục nhận xét' ),
			"url" => url_for ( '@ps_category_review' ),
			"active" => PreSchool::askCurrentMenu ( 'psCategoryReview','index' ) || PreSchool::askCurrentMenu ( 'psCategoryReview','new' ) || PreSchool::askCurrentMenu ( 'psCategoryReview','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
		"ps_review_relative" => array (
			"title" => __ ( 'Nhận xét - danh mục' ),
			"url" => url_for ( '@ps_review_relative' ),
			"active" => PreSchool::askCurrentMenu ( 'psReviewRelative','index' ) || PreSchool::askCurrentMenu ( 'psReviewRelative','new' ) || PreSchool::askCurrentMenu ( 'psReviewRelative','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
		"ps_review" => array (
			"title" => __ ( 'Nhận xét học sinh' ),
			"url" => url_for ( '@ps_review' ),
			"active" => PreSchool::askCurrentMenu ( 'psReview','index' ) || PreSchool::askCurrentMenu ( 'psReview','new' ) || PreSchool::askCurrentMenu ( 'psReview','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
	)
);

$page_nav ['other_list'] = array (
	"title" => 'Danh mục khác',
	"url" => '#',
	"active" => PreSchool::askActiveMenu ( 'psSymbol' ) || PreSchool::askActiveMenu ( 'psSymbolGroup' ) || PreSchool::askActiveMenu ( 'psRegularity' ) || PreSchool::askActiveMenu ( 'psReduceYourself' ) || PreSchool::askActiveMenu ( 'psPolicyGroup' ) || PreSchool::askActiveMenu ( 'psTargetGroup' ) || PreSchool::askActiveMenu ( 'psTypeAge' ),
	"access" => true,
	"icon" => "fa-folder-open-o",
	"sub" => array(
		"ps_symbol" => array (
			"title" => __ ( 'Danh Sách ký hiệu' ),
			"url" => url_for ( '@ps_symbol' ),
			"active" => PreSchool::askCurrentMenu ( 'psSymbol','index' ) || PreSchool::askCurrentMenu ( 'psSymbol','new' ) || PreSchool::askCurrentMenu ( 'psSymbol','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
		"ps_symbol_group" => array (
			"title" => __ ( 'Danh Sách Nhóm ký hiệu' ),
			"url" => url_for ( '@ps_symbol_group' ),
			"active" => PreSchool::askCurrentMenu ( 'psSymbolGroup','index' ) || PreSchool::askCurrentMenu ( 'psSymbolGroup','new' ) || PreSchool::askCurrentMenu ( 'psSymbolGroup','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
		"ps_regularity" => array (
			"title" => __ ( 'Tần xuất thu' ),
			"url" => url_for ( '@ps_regularity' ),
			"active" => PreSchool::askCurrentMenu ( 'psRegularity','index' ) || PreSchool::askCurrentMenu ( 'psRegularity','new' ) || PreSchool::askCurrentMenu ( 'psRegularity','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
		"ps_reduce_yourself" => array (
			"title" => __ ( 'Danh sách giảm trừ' ),
			"url" => url_for ( '@ps_reduce_yourself' ),
			"active" => PreSchool::askCurrentMenu ( 'psReduceYourself','index' ) || PreSchool::askCurrentMenu ( 'psReduceYourself','new' ) || PreSchool::askCurrentMenu ( 'psReduceYourself','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
		"ps_policy_group" => array (
			"title" => __ ( 'Chế độ chính sách' ),
			"url" => url_for ( '@ps_policy_group' ),
			"active" => PreSchool::askCurrentMenu ( 'psPolicyGroup','index' ) || PreSchool::askCurrentMenu ( 'psPolicyGroup','new' ) || PreSchool::askCurrentMenu ( 'psPolicyGroup','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
		"ps_target_group" => array (
			"title" => __ ( 'Nhóm đối tượng' ),
			"url" => url_for ( '@ps_target_group' ),
			"active" => PreSchool::askCurrentMenu ( 'psTargetGroup','index' ) || PreSchool::askCurrentMenu ( 'psTargetGroup','new' ) || PreSchool::askCurrentMenu ( 'psTargetGroup','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
		"ps_type_age" => array (
			"title" => __ ( 'Độ tuổi' ),
			"url" => url_for ( '@ps_type_age' ),
			"active" => PreSchool::askCurrentMenu ( 'psTypeAge','index' ) || PreSchool::askCurrentMenu ( 'psTypeAge','new' ) || PreSchool::askCurrentMenu ( 'psTypeAge','edit' ), 
			"access" => true,
			"icon" => "fa-television"
		),
	)
);

$page_nav ['root_personal'] = array (
		"title" => __ ( 'Personal register' ),
		"url" => '#',
		"active" => (PreSchool::askActiveMenu ( 'psCmsNotification' ) || PreSchool::askActiveMenu ( 'psEvaluateSemester' ) || PreSchool::askActiveMenu ( 'psCommentWeek' ) || PreSchool::askActiveMenu ( 'psServiceCourseSchedules' ) || PreSchool::askCurrentMenu ( 'psLogtimes', 'delay' ) || PreSchool::askActiveMenu ( 'psStudentFeatures' ) || PreSchool::askActiveMenu ( 'psStudentInfo' ) || PreSchool::askActiveMenu ( 'psStudentServiceCourseComment' ) || PreSchool::askActiveMenu ( 'psAttendances' )),
		"access" => true,
		"icon" => "fa-book",
		"sub" => array (
				/*
				"notification_sent" => array (
						"title" => __ ( 'Notification' ),
						"url" => url_for ( '@ps_cms_notifications' ),
						"active" => PreSchool::askActiveMenu ( 'psCmsNotifications' ),
						"access" => true,
						"icon" => "fa-bell-o" ),
				*/
				"notification_new" => array (
						"title" => __ ( 'Notification' ),
						"url" => url_for ( '@ps_cms_notifications_ps_cms_notification' ),
						"active" => PreSchool::askCurrentMenu ( 'psCmsNotification', 'index' ),
						"access" => true/*myUser::isAdministrator()*/,
						"icon" => "fa-bell-o" ),

				"birthday_notify" => array (
						"title" => __ ( 'Birthday Notification' ),
						"url" => url_for ( '@ps_cms_notifications_ps_cms_notification_birthday_notify' ),
						"active" => PreSchool::askCurrentMenu ( 'psCmsNotification', 'birthdayNotify' ),
						"access" => true,
						"icon" => "fa-birthday-cake" ),

				'schedules' => array (
						"title" => __ ( 'Personal schedule' ),
						"url" => url_for ( '@ps_service_course_schedules_personal' ),
						"active" => PreSchool::askActiveMenu ( 'psServiceCourseSchedules' ),
						"access" => false,
						"icon" => "fa-calendar-o" ),
				/*
				'diary' => array (
						"title" => __ ( 'Diary' ),
						"url" => url_for ( '@ps_logtimes' ),
						"active" => PreSchool::askCurrentMenu ( 'psLogtimes', 'index' ),
						"access" => $access_ps_logtimes,
						"icon" => "fa-clock-o" ),*/
				
				'attendances' => array (
						"title" => __ ( 'Diary' ),
						"url" => url_for ( '@ps_attendances' ),
						"active" => PreSchool::askCurrentMenu ( 'psAttendances', 'index' ),
						"access" => $access_ps_logtimes, // $access_ps_logtimes, myUser::isAdministrator()
						"icon" => "fa-clock-o" ),
				'delay' => array (
						"title" => __ ( 'Delay' ),
						"url" => url_for ( '@ps_logtimes_delay' ),
						"active" => PreSchool::askCurrentMenu ( 'psLogtimes', 'delay' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_ATTENDANCE_DELAY',
								'PS_STUDENT_ATTENDANCE_ADD',
								'PS_STUDENT_ATTENDANCE_DELETE',
								'PS_STUDENT_ATTENDANCE_EDIT' ), false ),
						"icon" => "fa-caret-square-o-right" ),
				/*
				'track' => array (
						"title" => __ ( 'Track activities' ),
						"url" => url_for ( '@ps_student_features' ),
						"active" => (PreSchool::askCurrentMenu ( 'psStudentFeatures', 'warning' ) || PreSchool::askCurrentMenu ( 'psStudentFeatures', 'index' )),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_FEATURE_ADD',
								'PS_STUDENT_FEATURE_EDIT',
								'PS_STUDENT_FEATURE_DELETE',
								'PS_STUDENT_ATTENDANCE_TEACHER' ), false ),
						"icon" => "fa-tasks" ),

				'root_student_service_course_comment' => array (
						"title" => __ ( 'Study notes' ),
						"url" => url_for ( '@ps_student_service_course_comment' ),
						"active" => (PreSchool::askCurrentMenu ( 'psStudentServiceCourseComment', 'index' ) || PreSchool::askCurrentMenu ( 'psStudentServiceCourseComment', 'warning' )),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_SERVICE_COURSE_COMMENT_SHOW',
								'PS_STUDENT_SERVICE_COURSE_COMMENT_DETAIL',
								'PS_STUDENT_SERVICE_COURSE_COMMENT_ADD',
								'PS_STUDENT_SERVICE_COURSE_COMMENT_DELETE',
								'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL',
								'PS_STUDENT_SERVICE_COURSE_COMMENT_TEACHER' ), false ),
						"icon" => "fa-comments-o" ),
				*/
				"ps_comment_week" => array (
						"title" => __ ( 'Comment week' ),
						"url" => url_for ( '@ps_comment_week' ),
						"active" => PreSchool::askActiveMenu ( 'psCommentWeek' ),
						"access" => array (
								'PS_STUDENT_FEATURE_ADD',
								'PS_STUDENT_FEATURE_EDIT',
								'PS_STUDENT_FEATURE_DELETE',
								'PS_STUDENT_ATTENDANCE_TEACHER' ),
						"icon" => "fa-comment" ),
				/*
				"ps_evaluate_semester" => array (
						"title" => __ ( 'Evaluate semester' ),
						"url" => url_for ( '@ps_evaluate_semester' ),
						"active" => PreSchool::askActiveMenu ( 'psEvaluateSemester' ),
						"access" => array (
								'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ),
						"icon" => "fa-address-book" ),
				
				'trackbook' => array (
						"title" => __ ( 'Trackbook' ),
						"url" => url_for ( '@ps_student_info' ),
						"active" => PreSchool::askCurrentMenu ( 'psStudentInfo', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_MSTUDENT_SHOW',
								'PS_STUDENT_MSTUDENT_DETAIL' ), false ),
						"icon" => "fa-calendar-check-o" ),
				*/
				'logtime_statistic' => array (
						"title" => __ ( 'Logtime statistic' ),
						"url" => url_for ( '@ps_attendance_statistic' ),
						"active" => PreSchool::askCurrentMenu ( 'psAttendances', 'statistic' ),
						"access" => true,
						"icon" => "fa-clock-o" ),
		        /*
    		    "student_synthetic" => array (
    		        "title" => __ ( 'Student synthetic' ),
    		        "url" => url_for ( '@ps_students_synthetic' ),
    		        "active" => PreSchool::askCurrentMenu ( 'psStudents', 'synthetic' ),
    		        "access" => myUser::isAdministrator (),
    		        "icon" => "fa-calendar-o" ),
    		    */
				$page_nav_synthetic = array (
						"title" => __ ( 'Synthetic statistic' ),
						"url" => '#',
						"active" => (PreSchool::askCurrentMenu ( 'psAttendances', 'index' ) || PreSchool::askCurrentMenu ( 'psAttendances', 'syntheticMonthOld' ) || PreSchool::askCurrentMenu ( 'psAttendances', 'synthetic' ) || PreSchool::askCurrentMenu ( 'psAttendances', 'syntheticMonth' )),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_ATTENDANCE_STATISTIC' ), false ),
						"icon" => "fa-table",
						"sub" => array (
								"attendance_synthetic" => array (
										"title" => __ ( 'Statistic by day' ),
										"url" => url_for ( '@ps_attendances_synthetic' ),
										"active" => PreSchool::askCurrentMenu ( 'psAttendances', 'synthetic' ),
										"access" => true,
										"icon" => "fa-bar-chart-o" ),
								'attendance_synthetic_month' => array (
										"title" => __ ( 'Statistic by month' ),
										"url" => url_for ( '@ps_attendances_synthetic_month' ),
										"active" => PreSchool::askCurrentMenu ( 'psAttendances', 'syntheticMonth' ),
										"access" => true,
										"icon" => "fa-bar-chart-o" ),
								'attendance_synthetic_updated' => array (
										"title" => __ ( 'Statistic synthetic updated' ),
										"url" => url_for ( '@ps_attendances_synthetic_updated_month' ),
										"active" => PreSchool::askCurrentMenu ( 'psAttendances', 'syntheticMonthOld' ),
										"access" => true,
										"icon" => "fa-bar-chart-o" ) ) ) ) );

// if (myUser::isAdministrator ()) :
// if (1==1) :

//$root_evaluate_access = (PreSchool::askActiveMenu ( 'psEvaluateIndexCriteria' ) || PreSchool::askActiveMenu ( 'psEvaluateIndexStudent' ) || PreSchool::askActiveMenu ( 'psEvaluateIndexSymbol' ) || PreSchool::askActiveMenu ( 'PsEvaluateSubject' ));

$evaluate_index_symbol_access = $sf_user->hasCredential ( array (
								'PS_EVALUATE_INDEX_SYMBOL_ADD',
								'PS_EVALUATE_INDEX_SYMBOL_EDIT',
								'PS_EVALUATE_INDEX_SYMBOL_DELETE',
								'PS_EVALUATE_INDEX_SYMBOL_SHOW',
								'PS_EVALUATE_INDEX_SYMBOL_DETAIL',
								'PS_EVALUATE_INDEX_SYMBOL_FILTERS_SCHOOL' ), false );
								
$evaluate_subject_access = $sf_user->hasCredential ( array (
								'PS_EVALUATE_INDEX_SUBJECT_SHOW',
								'PS_EVALUATE_INDEX_SUBJECT_DETAIL',
								'PS_EVALUATE_INDEX_SUBJECT_ADD',
								'PS_EVALUATE_INDEX_SUBJECT_EDIT',
								'PS_EVALUATE_INDEX_SUBJECT_DELETE',
								'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' ), false );
								
$evaluate_index_criteria = 	$sf_user->hasCredential ( array (
								'PS_EVALUATE_INDEX_CRITERIA_SHOW',
								'PS_EVALUATE_INDEX_CRITERIA_DETAIL',
								'PS_EVALUATE_INDEX_CRITERIA_ADD',
								'PS_EVALUATE_INDEX_CRITERIA_EDIT',
								'PS_EVALUATE_INDEX_CRITERIA_DELETE' ), false );

$evaluate_index_student = $sf_user->hasCredential ( array (
								'PS_EVALUATE_INDEX_STUDENT_SHOW',
								'PS_EVALUATE_INDEX_STUDENT_DETAIL',
								'PS_EVALUATE_INDEX_STUDENT_ADD',
								'PS_EVALUATE_INDEX_STUDENT_EDIT',
								'PS_EVALUATE_INDEX_STUDENT_DELETE' ), false );

$root_evaluate_access = ($evaluate_index_symbol_access || $evaluate_subject_access || $evaluate_index_criteria || $evaluate_index_student);
/*
$page_nav ['root_evaluate'] = array (
		"title" => __ ( 'Evaluate index' ),
		"url" => '#',
		"active" => (PreSchool::askActiveMenu ( 'psEvaluateIndexCriteria' ) || PreSchool::askActiveMenu ( 'psEvaluateIndexStudent' ) || PreSchool::askActiveMenu ( 'psEvaluateIndexSymbol' ) || PreSchool::askActiveMenu ( 'PsEvaluateSubject' )),
		"access" => $root_evaluate_access,
		"icon"   => "fa-star",
		"sub"    => array (
				'evaluate_index_symbol' => array (
						"title" => __ ( 'Evaluate index symbol' ),
						"url" => url_for ( '@ps_evaluate_index_symbol' ),
						"active" => PreSchool::askCurrentMenu ( 'psEvaluateIndexSymbol', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_EVALUATE_INDEX_SYMBOL_ADD',
								'PS_EVALUATE_INDEX_SYMBOL_EDIT',
								'PS_EVALUATE_INDEX_SYMBOL_DELETE',
								'PS_EVALUATE_INDEX_SYMBOL_SHOW',
								'PS_EVALUATE_INDEX_SYMBOL_DETAIL',
								'PS_EVALUATE_INDEX_SYMBOL_FILTERS_SCHOOL' ), false ),
						"icon" => "fa-hashtag" ),

				'evaluate_subject' => array (
						"title" => __ ( 'Evaluate index subject' ),
						"url" => url_for ( '@ps_evaluate_subject' ),
						"active" => PreSchool::askCurrentMenu ( 'PsEvaluateSubject', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_EVALUATE_INDEX_SUBJECT_SHOW',
								'PS_EVALUATE_INDEX_SUBJECT_DETAIL',
								'PS_EVALUATE_INDEX_SUBJECT_ADD',
								'PS_EVALUATE_INDEX_SUBJECT_EDIT',
								'PS_EVALUATE_INDEX_SUBJECT_DELETE',
								'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' ), false ),
						"icon" => "fa-calendar-check-o" ),

				'evaluate_index_criteria' => array (
						"title" => __ ( 'Evaluate index criteria' ),
						"url" => url_for ( '@ps_evaluate_index_criteria' ),
						"active" => PreSchool::askCurrentMenu ( 'PsEvaluateIndexCriteria', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_EVALUATE_INDEX_CRITERIA_SHOW',
								'PS_EVALUATE_INDEX_CRITERIA_DETAIL',
								'PS_EVALUATE_INDEX_CRITERIA_ADD',
								'PS_EVALUATE_INDEX_CRITERIA_EDIT',
								'PS_EVALUATE_INDEX_CRITERIA_DELETE' ), false ),
						"icon" => "fa-tasks" ),

				'evaluate_index_student' => array (
						"title" => __ ( 'Evaluate index student' ),
						"url" => url_for ( '@ps_evaluate_index_student' ),
						"active" => PreSchool::askCurrentMenu ( 'PsEvaluateIndexStudent', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_EVALUATE_INDEX_STUDENT_SHOW',
								'PS_EVALUATE_INDEX_STUDENT_DETAIL',
								'PS_EVALUATE_INDEX_STUDENT_ADD',
								'PS_EVALUATE_INDEX_STUDENT_EDIT',
								'PS_EVALUATE_INDEX_STUDENT_DELETE' ), false ),
						"icon" => "fa-user-circle" ) ) );
*/
$page_nav ["root_activitie"] = array (
		"title" => __ ( "Activity" ),
		"access" => true,
		"url" =>  url_for ( "@ps_active_student" ),
		"active" => PreSchool::askActiveMenu ( 'psActiveStudent' ) ,
		"icon" => "fa-openid",
		"sub" => array (
				/*
				"ps_active_student" => array (
						"title" => __ ( "Lịch hoạt động" ),
						"url" => url_for ( "@ps_active_student" ),
						"active" => PreSchool::askCurrentMenu ( 'psActiveStudent', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SYSTEM_ACTIVE_STUDENT_SHOW',
								'PS_SYSTEM_ACTIVE_STUDENT_DETAIL',
								'PS_SYSTEM_ACTIVE_STUDENT_ADD',
								'PS_SYSTEM_ACTIVE_STUDENT_EDIT',
								'PS_SYSTEM_ACTIVE_STUDENT_DELETE' ), false ),
						"icon" => "fa-calendar-o" ),
				
				"ps_feature_branch_times" => array (
						"title" => __ ( "Schedule activities" ),
						"url" => url_for ( "@ps_feature_branch_times_by_week" ),
						"active" => PreSchool::askCurrentMenu ( 'psFeatureBranchTimes', 'show' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SYSTEM_FEATURE_BRANCH_SHOW',
								'PS_SYSTEM_FEATURE_BRANCH_DETAIL',
								'PS_SYSTEM_FEATURE_BRANCH_ADD',
								'PS_SYSTEM_FEATURE_BRANCHE_EDIT',
								'PS_SYSTEM_FEATURE_BRANCH_DELETE' ), false ),
						"icon" => "fa-calendar-o" ),

				"ps_feature_branch_times_by_week" => array (
				"title" => __ ( "Schedule activities by week" ),
				"url" => url_for ( "@ps_feature_branch_times_show" ),
				"active" => PreSchool::askActiveMenu ( 'psFeatureBranchTimes' ),
				"access" => $sf_user->hasCredential ( array (
				'PS_SYSTEM_FEATURE_BRANCH_SHOW'), false ),
				"icon" => "fa-calendar-o" ),

				"children_activities" => array (
						"title" => __ ( "Children's Activities" ),
						"url" => url_for ( "@ps_feature_branch" ),
						"active" => PreSchool::askCurrentMenu ( 'psFeatureBranch', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SYSTEM_FEATURE_BRANCH_SHOW',
								'PS_SYSTEM_FEATURE_BRANCH_DETAIL',
								'PS_SYSTEM_FEATURE_BRANCH_ADD',
								'PS_SYSTEM_FEATURE_BRANCHE_EDIT',
								'PS_SYSTEM_FEATURE_BRANCH_DELETE' ), false ),
						"icon" => "fa-bed" ),
				"group_activities" => array (
						"title" => __ ( "Feature" ),
						"url" => url_for ( "@ps_feature" ),
						"active" => PreSchool::askCurrentMenu ( 'psFeature', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SYSTEM_FEATURE_SHOW',
								'PS_SYSTEM_FEATURE_DETAIL',
								'PS_SYSTEM_FEATURE_ADD',
								'PS_SYSTEM_FEATURE_EDIT',
								'PS_SYSTEM_FEATURE_DELETE' ), false ),
						"icon" => "fa-cubes" ),
				"feature_option" => array (
						"title" => __ ( "Declaring the unit" ),
						"url" => url_for ( "@feature_option" ),
						"active" => PreSchool::askCurrentMenu ( 'featureoption', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SYSTEM_FEATURE_OPTION_SHOW',
								'PS_SYSTEM_FEATURE_OPTION_DETAIL',
								'PS_SYSTEM_FEATURE_OPTION_ADD',
								'PS_SYSTEM_FEATURE_OPTION_EDIT',
								'PS_SYSTEM_FEATURE_OPTION_DELETE' ), false ),
						"icon" => "fa-balance-scale" ),
				
				$page_nav_branch_import = array (
						"title" => __ ( 'Feature branch import' ),
						"url" => '#',
						"active" => (PreSchool::askCurrentMenu ( 'psFeatureBranch', 'import' ) || PreSchool::askCurrentMenu ( 'psFeatureBranchTimes','import' ) || PreSchool::askCurrentMenu ( 'psFeatureBranchTimes','importTem3' )),
						"access" => $sf_user->hasCredential ( array (
								'PS_SYSTEM_FEATURE_BRANCH_IMPORT' ), false ),
						"icon" => "fa-table",
						"sub" => array (
								"ps_feature_branch_import" => array (
										"title" => __ ( "Feature branch import 1" ),
										"url" => url_for ( "@ps_feature_branch_import" ),
										"active" => PreSchool::askCurrentMenu ( 'psFeatureBranch', 'import' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_FEATURE_BRANCH_IMPORT' ), false ),
										"icon" => "fa-upload" ),
								"ps_feature_branch_import_times" => array (
										"title" => __ ( "Feature branch import 2" ),
										"url" => url_for ( "@ps_feature_branch_times_import" ),
										"active" => PreSchool::askCurrentMenu ( 'psFeatureBranchTimes', 'import' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_FEATURE_BRANCH_IMPORT' ), false ),
										"icon" => "fa-upload" ),
								"ps_feature_branch_import_times_tem3" => array (
										"title" => __ ( "Feature branch import 3" ),
										"url" => url_for ( "@ps_feature_branch_times_import_tem3" ),
										"active" => PreSchool::askCurrentMenu ( 'psFeatureBranchTimes', 'importTem3' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_FEATURE_BRANCH_IMPORT' ), false ),
										"icon" => "fa-upload" ),
						) ),
					*/
				 ) );

$page_nav ['root_learn'];

$page_nav_academic = array (
		"title" => __ ( 'Management Academic' ),
		"url" => '#',
		"active" => (PreSchool::askActiveMenu ( 'psServiceCourses' ) || PreSchool::askActiveMenu ( 'psServiceCourseSchedules' ) || PreSchool::askActiveMenu ( 'psSubjects' ) || PreSchool::askActiveMenu ( 'psSubjectSplits' )),
		"access" => $sf_user->hasCredential ( 'PS_STUDENT_SERVICE_COURSES_SHOW' ) || $sf_user->hasCredential ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_SHOW' ) || $sf_user->hasCredential ( 'PS_STUDENT_SERVICE_COURSES_SHOW' ) || $sf_user->hasCredential ( 'PS_STUDENT_SUBJECT_SHOW' ),
		"icon" => "fa-leanpub",
		"sub" => array (
				"root_my_class_service_courses_statistic" => array (
						"title" => __ ( 'Courses statistic student not yet in any class' ),
						"url" => url_for ( '@ps_service_courses_statistic' ),
						"active" => PreSchool::askCurrentMenu ( 'psServiceCourses', 'statistic' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_SERVICE_COURSES_SHOW',
								'PS_STUDENT_SERVICE_COURSES_DETAIL',
								'PS_STUDENT_SERVICE_COURSES_ADD',
								'PS_STUDENT_SERVICE_COURSES_EDIT',
								'PS_STUDENT_SERVICE_COURSES_DELETE',
								'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' ), false ),
						"icon" => "fa-language" ),
				'root_my_class_schedules' => array (
						"title" => __ ( 'Course schedules' ),
						"url" => url_for ( '@ps_service_course_schedules_new' ),
						"active" => PreSchool::askCurrentMenu ( 'psServiceCourseSchedules', 'new' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_SERVICE_COURSE_SHEDULES_SHOW',
								'PS_STUDENT_SERVICE_COURSE_SHEDULES_DETAIL',
								'PS_STUDENT_SERVICE_COURSE_SHEDULES_ADD',
								'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT',
								'PS_STUDENT_SERVICE_COURSE_SHEDULES_DELETE',
								'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' ), false ),
						"icon" => "fa-calendar" ),

				"root_my_class_service_courses" => array (
						"title" => __ ( 'Courses' ),
						"url" => url_for ( '@ps_service_courses' ),
						"active" => PreSchool::askCurrentMenu ( 'psServiceCourses', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_SERVICE_COURSES_SHOW',
								'PS_STUDENT_SERVICE_COURSES_DETAIL',
								'PS_STUDENT_SERVICE_COURSES_ADD',
								'PS_STUDENT_SERVICE_COURSES_EDIT',
								'PS_STUDENT_SERVICE_COURSES_DELETE',
								'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' ), false ),
						"icon" => "fa-language" ),

				"root_my_class_subject" => array (
						"title" => __ ( 'Subject' ),
						"url" => url_for ( '@ps_subjects' ),
						"active" => PreSchool::askActiveMenu ( 'psSubjects' ) || PreSchool::askActiveMenu ( 'psSubjectSplits' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_SUBJECT_SHOW',
								'PS_STUDENT_SUBJECT_DETAIL',
								'PS_STUDENT_SUBJECT_ADD',
								'PS_STUDENT_SUBJECT_EDIT',
								'PS_STUDENT_SUBJECT_DELETE',
								'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ), false ),
						"icon" => "fa-book" ) ) );

$access_root_service = $sf_user->hasCredential ( array (
		'PS_STUDENT_SERVICE_SHOW',
		'PS_STUDENT_SERVICE_DETAIL',
		'PS_STUDENT_SERVICE_ADD',
		'PS_STUDENT_SERVICE_EDIT',
		'PS_STUDENT_SERVICE_DELETE',
		'PS_STUDENT_SERVICE_GROUP_SHOW',
		'PS_STUDENT_SERVICE_GROUP_DETAIL',
		'PS_STUDENT_SERVICE_GROUP_ADD',
		'PS_STUDENT_SERVICE_GROUP_EDIT',
		'PS_STUDENT_SERVICE_GROUP_DELETE',
		'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' ), false );

$page_nav ['root_service'] = array (
		"title" => __ ( 'Service' ),
		"url" => '#',
		"access" => $access_root_service,
		"active" => PreSchool::askActiveMenu ( 'psService' ) || PreSchool::askActiveMenu ( 'servicegroup' ) || PreSchool::askActiveMenu ( 'psServiceSaturday' ) || PreSchool::askActiveMenu ( 'psServiceCourses' ) || PreSchool::askActiveMenu ( 'psServiceCourseSchedules' ) || PreSchool::askActiveMenu ( 'psSubjects' ) || PreSchool::askActiveMenu ( 'psSubjectSplits' ) || PreSchool::askCurrentMenu ( 'psStudentService', 'registration' ),
		"icon" => "fa-recycle",
		"sub" => array (
				"service" => array (
						"title" => __ ( 'Services' ),
						"url" => url_for ( '@ps_service' ),
						"active" => PreSchool::askCurrentMenu ( 'psService', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_SERVICE_SHOW',
								'PS_STUDENT_SERVICE_DETAIL',
								'PS_STUDENT_SERVICE_ADD',
								'PS_STUDENT_SERVICE_EDIT',
								'PS_STUDENT_SERVICE_DELETE' ), false ),
						"icon" => "fa-recycle" ),
				"registration" => array (
						"title" => __ ( 'Registration service' ),
						"url" => url_for ( '@ps_service_registration_student' ),
						"active" => PreSchool::askCurrentMenu ( 'psStudentService', 'registration' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_SERVICE_REGISTER_STUDENT' ), false ),
						"icon" => "fa-plus-circle" ),
				/*
				"service_academic" => $page_nav_academic,
				
				"root_my_class_service_saturday" => array (
						"title" => __ ( 'Service saturday' ),
						"url" => url_for ( '@ps_service_saturday' ),
						"active" => PreSchool::askCurrentMenu ( 'psServiceSaturday', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SERVICE_SATURDAY_SHOW',
								'PS_SERVICE_SATURDAY_DETAIL',
								'PS_SERVICE_SATURDAY_ADD',
								'PS_SERVICE_SATURDAY_EDIT',
								'PS_SERVICE_SATURDAY_DELETE',
								'PS_SERVICE_SATURDAY_FILTER_SCHOOL' ), false ),
						"icon" => "fa-calendar-o" ),

				"root_service_saturday_statistic" => array (
						"title" => __ ( 'Service saturday statistic' ),
						"url" => url_for ( '@ps_service_saturday_statistic' ),
						"active" => PreSchool::askCurrentMenu ( 'psServiceSaturday', 'statistic' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SERVICE_SATURDAY_SHOW',
								'PS_SERVICE_SATURDAY_STATISTIC',
								'PS_SERVICE_SATURDAY_DETAIL',
								'PS_SERVICE_SATURDAY_ADD',
								'PS_SERVICE_SATURDAY_EDIT',
								'PS_SERVICE_SATURDAY_DELETE',
								'PS_SERVICE_SATURDAY_FILTER_SCHOOL' ), false ),
						"icon" => "fa-calendar-o" ),
				*/
				"service_group" => array (
						"title" => __ ( 'Service group' ),
						"url" => url_for ( '@service_group' ),
						"active" => PreSchool::askActiveMenu ( 'servicegroup' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_SERVICE_GROUP_SHOW',
								'PS_STUDENT_SERVICE_GROUP_DETAIL',
								'PS_STUDENT_SERVICE_GROUP_ADD',
								'PS_STUDENT_SERVICE_GROUP_EDIT',
								'PS_STUDENT_SERVICE_GROUP_DELETE',
								'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' ), false ),
						"icon" => "fa-snowflake-o" ) ) );

$page_nav ['root_student'] = array (
		"title" => __ ( 'Student' ),
		"url" => url_for ( '@ps_students' ),
		"active" => (PreSchool::askCurrentMenu ( 'psStudents','index' ) || PreSchool::askActiveMenu ( 'psRelationShip' ) || PreSchool::askActiveMenu ( 'psMobileApps' ) || PreSchool::askActiveMenu ( 'psRelatives' ) || PreSchool::askActiveMenu ( 'psOffSchool' ) || PreSchool::askActiveMenu ( 'psAdvices' )),
		"access" => true,
		"icon" => "fa-graduation-cap",
		"sub" => array (
				"psOffSchool" => array (
						"title" => __ ( 'Student Off School' ),
						"url" => url_for ( '@ps_off_school' ),
						"active" => PreSchool::askActiveMenu ( 'psOffSchool' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_OFF_SCHOOL_SHOW',
								'PS_STUDENT_OFF_SCHOOL_ADD',
								'PS_STUDENT_OFF_SCHOOL_DETAIL',
								'PS_STUDENT_OFF_SCHOOL_EDIT',
								'PS_STUDENT_OFF_SCHOOL_DELETE' ), false ),
						"icon" => "fa-calendar-o" ),

				"psAdvices" => array (
						"title" => __ ( 'Advices' ),
						"url" => url_for ( '@ps_advices' ),
						"active" => PreSchool::askActiveMenu ( 'psAdvices' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_RELATIVE_ADVICE_SHOW',
								'PS_STUDENT_RELATIVE_ADVICE_ADD',
								'PS_STUDENT_RELATIVE_ADVICE_DETAIL',
								'PS_STUDENT_RELATIVE_ADVICE_EDIT',
								'PS_STUDENT_RELATIVE_ADVICE_DELETE',
								'PS_STUDENT_RELATIVE_ADVICE_FILTER_SCHOOL' ), false ),
						"icon" => "fa-comments-o" ),

				"students" => array (
						"title" => __ ( 'Student list' ),
						"url" => url_for ( '@ps_students' ),
						"icon" => "fa-child",
						"active" => PreSchool::askCurrentMenu ( 'psStudents', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_MSTUDENT_SHOW',
								'PS_STUDENT_MSTUDENT_FILTER_SCHOOL',
								'PS_STUDENT_MSTUDENT_DETAIL',
								'PS_STUDENT_MSTUDENT_ADD',
								'PS_STUDENT_MSTUDENT_EDIT',
								'PS_STUDENT_MSTUDENT_DELETE' ), false ) ),
				"psRelatives" => array (
						"title" => __ ( 'Relative manager' ),
						"url" => url_for ( '@ps_relatives' ),
						"active" => PreSchool::askActiveMenu ( 'psRelatives' )  || PreSchool::askActiveMenu ( 'psMobileApps' ) ,
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_RELATIVE_SHOW' ), false ),
						"icon" => "fa-street-view",
						"sub" => array (
								"ps_relative_statistic" => array (
										"title" => __ ( 'Relative account statistic' ),
										"url" => url_for ( '@ps_relative_statistic' ),
										"active" => PreSchool::askCurrentMenu ( 'psRelatives', 'statistic' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_STUDENT_RELATIVE_SHOW' ), false ),
										"icon" => "fa fa-bar-chart" ),
								"ps_mobile_apps" => array (
										"title" => __ ( 'List relative accounts active by month' ),
										"url" => url_for ( '@ps_mobile_apps' ),
										"active" => PreSchool::askCurrentMenu ( 'psMobileApps', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_REPORT_MOBILE_APPS_SHOW',
												'PS_REPORT_MOBILE_APPS_DETAIL',
												'PS_REPORT_MOBILE_APPS_EXPORT',
												'PS_REPORT_MOBILE_APPS_FILTER_SCHOOL' ), false ),
										"icon" => "fa fa-bar-chart" ),
								"ps_mobile_apps_cross_checking" => array (
										"title" => __ ( 'Cross checking relative accounts' ),
										"url" => url_for ( '@ps_mobile_apps_cross_checking' ),
										"active" => PreSchool::askCurrentMenu ( 'psMobileApps', 'crossChecking' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_MOBILE_APPS_SHOW',
												'PS_SYSTEM_MOBILE_APPS_DETAIL',
												'PS_REPORT_MOBILE_APPS_FILTER_SCHOOL' ), false ),
										"icon" => "fa fa-bar-chart" ) ) ),

				"import students" => array (
						"title" => __ ( 'Import student' ),
						"url" => url_for ( '@ps_students_relationship_import' ),
						"icon" => "fa-cloud-upload",
						"active" => PreSchool::askCurrentMenu ( 'psRelationship', 'import' ),
						"access" => $sf_user->hasCredential ( array ('PS_STUDENT_MSTUDENT_IMPORT'), false ) ) ) );

$page_nav ['root_my_class'] = array (
		"title" => __ ( 'Class' ),
		"url" => url_for ( '@ps_class' ),
		"active" => (PreSchool::askActiveMenu ( 'psClass' ) || PreSchool::askCurrentMenu ( 'psStudents', 'syntheticExport' )),
		"access" => true,
		"icon" => "fa-television",
		"sub" => array (

				"root_my_class_class" => array (
						"title" => __ ( 'Class students' ),
						"url" => url_for ( '@ps_class' ),
						"active" => PreSchool::askCurrentMenu ( 'psClass', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_CLASS_SHOW',
								'PS_STUDENT_CLASS_DETAIL',
								'PS_STUDENT_CLASS_ADD',
								'PS_STUDENT_CLASS_EDIT',
								'PS_STUDENT_CLASS_DELETE',
								'PS_STUDENT_CLASS_FILTER_SCHOOL' ), false ),
						"icon" => "fa-television" ),

				"root_my_class_assign_students" => array (
						"title" => __ ( 'Classes for students' ),
						"url" => url_for ( '@ps_class_assign_students' ),
						"active" => PreSchool::askCurrentMenu ( 'psClass', 'assign_students' ),
						"access" => false,
						"icon" => "fa-cube" ),

				"root_my_class_class_move" => array (
						"title" => __ ( 'Move class' ),
						"url" => url_for ( '@ps_class_move' ),
						"active" => PreSchool::askCurrentMenu ( 'psClass', 'move' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_STUDENT_CLASS_SHOW',
								'PS_STUDENT_CLASS_DETAIL',
								'PS_STUDENT_CLASS_ADD',
								'PS_STUDENT_CLASS_EDIT',
								'PS_STUDENT_CLASS_DELETE',
								'PS_STUDENT_CLASS_FILTER_SCHOOL' ), false ),
						"icon" => "fa-random" ),
				/*
				"root_my_class_check_data_student" => array (
						"title" => __ ( 'Check data students' ),
						"url" => '@ps_check_class_students',
						"active" => PreSchool::askActiveMenu ( 'psCheckClassStudents' ),
						"access" => myUser::isAdministrator (),
						"icon" => "fa-eye-slash" ),
				*/
				"ps_student_class_synthetic_export" => array (
						"title" => __ ( 'Student class synthetic export' ),
						"url" => url_for ( '@ps_student_class_synthetic_export' ),
						"active" => PreSchool::askCurrentMenu ( 'psStudents', 'syntheticExport' ),
						"access" => myUser::isAdministrator ()/* $sf_user->hasCredential ( array (
								'PS_STUDENT_CLASS_SHOW',
								'PS_STUDENT_CLASS_DETAIL',
								'PS_STUDENT_CLASS_ADD',
								'PS_STUDENT_CLASS_EDIT',
								'PS_STUDENT_CLASS_DELETE',
								'PS_STUDENT_CLASS_FILTER_SCHOOL' ), false )*/,
						"icon" => "fa-cloud-download" ),
				
		) );
								

$page_nav ["root_nutrition"] = array (
		"title" => __ ( 'Menu-Nutrition' ),
		"url" => '#',
		"active" => PreSchool::askActiveMenu ( 'psMenusImports' ) || PreSchool::askActiveMenu ( 'psMenus' ) || PreSchool::askActiveMenu ( 'psMeals' ) || PreSchool::askActiveMenu ( 'psFoods' ),
		"access" => true,
		"icon" => "fa-cutlery",
		"sub" => array (
				/*"ps_menus" => array (
						"title" => __ ( 'Menus' ),
						"url" => url_for ( '@ps_menus' ),
						"active" => PreSchool::askActiveMenu ( 'psMenus' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_NUTRITION_MENUS_SHOW',
								'PS_NUTRITION_MENUS_DETAIL',
								'PS_NUTRITION_MENUS_ADD',
								'PS_NUTRITION_MENUS_EDIT',
								'PS_NUTRITION_MENUS_DELETE' 
						), false ),
						"icon" => "fa-recycle" ),
				"ps_foods" => array (
						"title" => __ ( 'Foods' ),
						"url" => url_for ( '@ps_foods' ),
						"active" => PreSchool::askActiveMenu ( 'psFoods' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_NUTRITION_FOOD_SHOW',
								'PS_NUTRITION_FOOD_DETAIL',
								'PS_NUTRITION_FOOD_ADD',
								'PS_NUTRITION_FOOD_EDIT',
								'PS_NUTRITION_FOOD_DELETE',
								'PS_NUTRITION_FOOD_FILTER_SCHOOL' ), false ),
						"icon" => "fa-gg-circle" ),
				*/
				"ps_meals" => array (
						"title" => __ ( 'Meals' ),
						"url" => url_for ( '@ps_meals' ),
						"active" => PreSchool::askActiveMenu ( 'psMeals' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_NUTRITION_MEALS_SHOW',
								'PS_NUTRITION_MEALS_DETAIL',
								'PS_NUTRITION_MEALS_ADD',
								'PS_NUTRITION_MEALS_EDIT',
								'PS_NUTRITION_MEALS_DELETE',
								'PS_NUTRITION_MEALS_FILTER_SCHOOL' ), false ),
						"icon" => "fa-snowflake-o" ),

				"ps_menus_imports=" => array (
						"title" => __ ( 'Menus imports' ),
						"url" => url_for ( '@ps_menus_imports_by_week' ),
						"active" => PreSchool::askActiveMenu ( 'psMenusImports' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_NUTRITION_MENUS_IMPORT' ), false ),
						"icon" => "fa-recycle" )
		) );

$page_nav ["root_medical"] = array (
		"title" => __ ( 'Medical' ),
		"url" => '#',
		"active" => PreSchool::askActiveMenu ( 'psStudentGrowths' ) || PreSchool::askActiveMenu ( 'psStudentBmi' ) || PreSchool::askActiveMenu ( 'psExamination' ),
		"access" => $sf_user->hasCredential ( 'PS_MEDICAL_GROWTH_SHOW' ) || $sf_user->hasCredential ( 'PS_MEDICAL_EXAMINATION_SHOW' ),
		"icon" => "fa-medkit",
		"sub" => array (
				array (
						"title" => __ ( 'Statistic' ),
						"url" => url_for ( '@ps_student_growths_statistic' ),
						"active" => PreSchool::askCurrentMenu ( 'psStudentGrowths', 'statistic' ),
						"access" => true,
						"icon" => "fa-bar-chart-o" ),

				array (
						"title" => __ ( 'Height Weight' ),
						"url" => url_for ( '@ps_student_growths' ),
						"active" => PreSchool::askCurrentMenu ( 'psStudentGrowths', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_MEDICAL_GROWTH_SHOW',
								'PS_MEDICAL_GROWTH_DETAIL',
								'PS_MEDICAL_GROWTH_ADD',
								'PS_MEDICAL_GROWTH_EDIT',
								'PS_MEDICAL_GROWTH_DELETE',
								'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), false ),
						"icon" => "fa-stethoscope" ),
				array (
						"title" => __ ( 'Examination' ),
						"url" => url_for ( '@ps_examination' ),
						"active" => PreSchool::askActiveMenu ( 'psExamination' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_MEDICAL_EXAMINATION_SHOW',
								'PS_MEDICAL_EXAMINATION_DETAIL',
								'PS_MEDICAL_EXAMINATION_ADD',
								'PS_MEDICAL_EXAMINATION_EDIT',
								'PS_MEDICAL_EXAMINATION_DELETE',
								'PS_MEDICAL_EXAMINATION_FILTER_SCHOOL' ), false ),
						"icon" => "fa-hospital-o" ),
				array (
						"title" => __ ( 'Table index Height and weight' ),
						"url" => url_for ( '@ps_student_bmi' ),
						"active" => PreSchool::askCurrentMenu ( 'psStudentBmi', 'index' ),
						"access" => true,
						"icon" => "fa-h-square" ),
				array (
						"title" => __ ( 'Import student growth' ),
						"url" => url_for ( '@ps_student_bmi_import' ),
						"active" => PreSchool::askCurrentMenu ( 'psStudentBmi', 'import' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_MEDICAL_GROWTH_IMPORT',
								'PS_MEDICAL_GROWTH_SHOW',
								'PS_MEDICAL_GROWTH_DETAIL',
								'PS_MEDICAL_GROWTH_ADD',
								'PS_MEDICAL_GROWTH_EDIT',
								'PS_MEDICAL_GROWTH_DELETE',
								'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), false ),
						"icon" => "fa-cloud-upload" ) ) );

$page_nav ["root_social_media"] = array (
		"title" => __ ( 'Media' ),
		"url" => '#',
		"active" => PreSchool::askActiveMenu ( 'psCmsArticles' ) || PreSchool::askActiveMenu ( 'psAlbums' ),
		"access" => true,
		"icon" => "fa-globe",
		"sub" => array (
				array (
						"title" => __ ( 'News' ),
						"url" => url_for ( '@ps_cms_articles' ),
						"active" => PreSchool::askActiveMenu ( 'psCmsArticles' ),
						"access" => true,
						"icon" => "fa-newspaper-o" ),
				array (
						"title" => __ ( 'Albums' ),
						"url" => url_for ( '@ps_albums' ),
						"active" => PreSchool::askActiveMenu ( 'psAlbums' ),
						"access" => true,
						"icon" => "fa-picture-o" ) ) );

$page_nav ["root_receipt"] = array (
		"title" => __ ( 'Management fee' ),
		"url" => '#',
		"active" => PreSchool::askActiveMenu ( 'psFeeReports' ) || PreSchool::askActiveMenu ( 'psReceipts' ) || PreSchool::askActiveMenu ( 'psReceivableStudents' ) || PreSchool::askActiveMenu ( 'receivable' ) || PreSchool::askCurrentMenu ( 'psFeeReceipt', 'studentSyntheticExport' ) || PreSchool::askCurrentMenu ( 'psFeeReceipt', 'statistic' ) || PreSchool::askCurrentMenu ( 'psFeeReceipt', 'paymentSynthetic' ) || PreSchool::askActiveMenu ( 'psReceivableTemporary' ),
		"access" => true,
		"icon" => "fa-calculator",
		"sub" => array (
				"fee_reports_panel" => array (
						"title" => __ ( 'Fee Overview' ),
						"url" => url_for ( '@ps_fee_reports_panel' ),
						"active" => PreSchool::askCurrentMenu ( 'psFeeReports', 'feePanel' ),
						"access" => /*$sf_user->hasCredential ( array (
								'PS_FEE_REPORT_SHOW',
								'PS_FEE_REPORT_DETAIL',
								'PS_FEE_REPORT_ADD',
								'PS_FEE_REPORT_EDIT',
								'PS_FEE_REPORT_DELETE',
								'PS_FEE_REPORT_FILTER_SCHOOL' ), false )*/false,
						"icon" => "fa-signal" ),

				"fee_reports" => array (
						"title" => __ ( 'Find fee reports' ),
						"url" => url_for ( '@ps_receipts' ),
						"active" => PreSchool::askCurrentMenu ( 'psReceipts', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_REPORT_SHOW',
								'PS_FEE_REPORT_DETAIL',
								'PS_FEE_REPORT_ADD',
								'PS_FEE_REPORT_EDIT',
								'PS_FEE_REPORT_DELETE',
								'PS_FEE_REPORT_FILTER_SCHOOL' ), false ),
						"icon" => "fa-search-plus" ),

				"fee_reports_process" => array (
						"title" => __ ( 'Process fee' ),
						"url" => url_for ( '@ps_fee_reports_control_step1' ),
						"active" => PreSchool::askCurrentMenu ( 'psFeeReports', 'feeControlStep1' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_REPORT_ADD',
								'PS_FEE_REPORT_EDIT',
								'PS_FEE_REPORT_DELETE' ), false ),
						"icon" => "fa-cog" ),

				"receivable" => array (
						"title" => __ ( 'Receivable' ),
						"url" => '#',
						"active" => PreSchool::askActiveMenu ( 'psReceivableStudents' ) || PreSchool::askActiveMenu ( 'receivable' ) || PreSchool::askCurrentMenu ( 'psFeeReports', 'feeReceivableStudentStep1' ) || PreSchool::askActiveMenu ( 'psReceivableTemporary' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_RECEIVABLE_SHOW',
								'PS_FEE_RECEIVABLE_DETAIL',
								'PS_FEE_RECEIVABLE_ADD',
								'PS_FEE_RECEIVABLE_EDIT',
								'PS_FEE_RECEIVABLE_DELETE',
								'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), false ),
						"icon" => "fa-gg-circle",
						"sub" => array (
								"ps_receivable_student" => array (
										"title" => __ ( 'Receivable of student' ),
										"url" => url_for ( '@ps_receivable_students' ),
										"active" => PreSchool::askActiveMenu ( 'psReceivableStudents' ) ,
										"access" => $sf_user->hasCredential ( array (
												'PS_FEE_REPORT_ADD',
												'PS_FEE_REPORT_EDIT',
												'PS_FEE_REPORT_DELETE',
												'PS_FEE_REPORT_FILTER_SCHOOL' ), false ),
										"icon" => "fa-money" ),
								"ps_fee_reports_receivable_student" => array (
										"title" => __ ( 'Receivables of the month' ),
										"url" => url_for ( '@ps_fee_reports_receivable_student_step1' ),
										"active" => PreSchool::askCurrentMenu ( 'psFeeReports', 'feeReceivableStudentStep1' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_FEE_REPORT_ADD',
												'PS_FEE_REPORT_EDIT',
												'PS_FEE_REPORT_DELETE',
												'PS_FEE_REPORT_FILTER_SCHOOL' ), false ),
										"icon" => "fa-money" ),
								"catalog_receivable" => array (
										"title" => __ ( 'Catalog receivable' ),
										"url" => url_for ( '@receivable' ),
										"active" => PreSchool::askActiveMenu ( 'receivable' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_FEE_RECEIVABLE_SHOW',
												'PS_FEE_RECEIVABLE_DETAIL',
												'PS_FEE_RECEIVABLE_ADD',
												'PS_FEE_RECEIVABLE_EDIT',
												'PS_FEE_RECEIVABLE_DELETE',
												'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), false ),
										"icon" => "fa-gg-circle" ),
								"ps_receivable_temporary" => array (
										"title" => __ ( 'Receivable temporary' ),
										"url" => url_for ( '@ps_receivable_temporary' ),
										"active" => PreSchool::askCurrentMenu ( 'psReceivableTemporary', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_FEE_RECEIVABLE_ADD',
												'PS_FEE_RECEIVABLE_EDIT',
												'PS_FEE_RECEIVABLE_DELETE',
												'PS_FEE_RECEIVABLE_IMPORT',
												'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), false ),
										"icon" => "fa-list-alt" ),
								"receivable_students_statistic" => array (
										"title" => __ ( 'Receivable students statistic' ),
										"url" => url_for ( '@ps_receivable_students_statistic' ),
										"active" => PreSchool::askActiveMenu ( 'psReceivableStudents' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_FEE_REPORT_FILTER_SHOW',
												'PS_FEE_REPORT_FILTER_DETAIL',
												'PS_FEE_REPORT_FILTER_ADD',
												'PS_FEE_REPORT_FILTER_EDIT',
												'PS_FEE_REPORT_FILTER_DELETE',
												'PS_FEE_REPORT_FILTER_SCHOOL' ), false ),
										"icon" => "fa-bar-chart-o" ) ) ),
				/*
    		    "ps_receivable_class" => array (
    		        "title" => __ ( 'Receivable Class' ),
    		        "url" => url_for ( '@ps_receivable_class' ),
    		        "active" => PreSchool::askActiveMenu ( 'psReceivableClass' ),
    		        "access" => $sf_user->hasCredential ( array (
    		            'PS_FEE_RECEIVABLE_SHOW',
    		            'PS_FEE_RECEIVABLE_DETAIL',
    		            'PS_FEE_RECEIVABLE_ADD',
    		            'PS_FEE_RECEIVABLE_EDIT',
    		            'PS_FEE_RECEIVABLE_DELETE',
    		            'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), false ),
    		        "icon" => "fa-gg-circle" ),*/
				/*
				"ps_receipt_temporary" => array (
						"title" => __ ( 'Receipt Temporary' ),
						"url" => url_for ( '@ps_receipt_temporary' ),
						"active" => PreSchool::askCurrentMenu ( 'psReceiptTemporary', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_REPORT_IMPORT' ), false ),
						"icon" => "fa-list-alt" ),*/
				/*
				"payment_for_account" => array (
						"title" => __ ( 'Manage payment amount' ),
						"url" => url_for ( '@ps_mobile_app_amounts' ),
						"active" => PreSchool::askActiveMenu ( 'psMobileAppAmounts' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_MOBILE_APP_AMOUNTS_SHOW',
								'PS_MOBILE_APP_AMOUNTS_DETAIL',
								'PS_MOBILE_APP_AMOUNTS_ADD',
								'PS_MOBILE_APP_AMOUNTS_EDIT',
								'PS_MOBILE_APP_AMOUNTS_DELETE',
								'PS_MOBILE_APP_AMOUNTS_FILTER_SCHOOL' ), false ),
						"icon" => "fa-dollar" ),
				*/
				"receipt_statistic" => array (
						"title" => __ ( 'Receipt statistic' ),
						"url" => '#',
						"active" => PreSchool::askCurrentMenu ( 'psFeeReceipt', 'statistic' ) || PreSchool::askCurrentMenu ( 'psFeeReceipt', 'studentSyntheticExport' ) || PreSchool::askCurrentMenu ( 'psFeeReceipt', 'paymentSynthetic' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_RECEIVABLE_SHOW',
								'PS_FEE_RECEIVABLE_DETAIL',
								'PS_FEE_RECEIVABLE_ADD',
								'PS_FEE_RECEIVABLE_EDIT',
								'PS_FEE_RECEIVABLE_DELETE',
								'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), false ),
						"icon" => "fa-gg-circle",
						"sub" => array (
								
								"ps_fee_receipt_payment_synthetic_export" => array (
										"title" => __ ( 'Receipt payment synthetic export' ),
										"url" => url_for ( '@ps_fee_receipt_payment_synthetic_export' ),
										"active" => PreSchool::askCurrentMenu ( 'psFeeReceipt', 'paymentSynthetic' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_FEE_REPORT_STATISTIC' ), false ),
										"icon" => "fa-archive" ),
								
								"ps_fee_receipt_student_synthetic_export" => array (
										"title" => __ ( 'Receipt student synthetic export' ),
										"url" => url_for ( '@ps_fee_receipt_student_synthetic_export' ),
										"active" => PreSchool::askCurrentMenu ( 'psFeeReceipt', 'studentSyntheticExport' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_FEE_REPORT_STATISTIC' ), false ),
										"icon" => "fa-archive" ),

								"ps_fee_receipt_statistic" => array (
										"title" => __ ( 'Fee receipt statistic' ),
										"url" => url_for ( '@ps_fee_receipt_statistic' ),
										"active" => PreSchool::askCurrentMenu ( 'psFeeReceipt', 'statistic' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_FEE_REPORT_STATISTIC' ), false ),
										"icon" => "fa-file-excel-o" ) ) ) ) );
$role_root_ps_fee_receipt = $sf_user->hasCredential(array(
		'PS_FEE_RECEIPT_NOTICATION_SHOW',
		'PS_FEE_RECEIPT_NOTICATION_DETAIL',
		'PS_FEE_RECEIPT_NOTICATION_ADD',
		'PS_FEE_RECEIPT_NOTICATION_EDIT',
		'PS_FEE_RECEIPT_NOTICATION_DELETE',
		'PS_FEE_RECEIPT_NOTICATION_PUSH',
		'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL'
), false) || $sf_user->hasCredential(array(
		'PS_FEE_RECEIPT_NOTICATION_IMPORT'
), false);

if ($role_root_ps_fee_receipt) {
$page_nav ["root_ps_fee_receipt"] = array (
		"title" => __ ( 'Management fee notification' ),
		"url" => '#',
		"active" => PreSchool::askCurrentMenu ( 'psFeeReceipt', 'index' ) || PreSchool::askCurrentMenu ( 'psFeeReceipt', 'import' ),
		"access" => false,
		"icon" => "fa-calculator",
		"sub" => array (
				"ps_fee_news_letters" => array (
						"title" => __ ( 'Fee Newsletters' ),
						"url" => url_for ( '@ps_fee_news_letters' ),
						"active" => PreSchool::askActiveMenu ( 'psFeeNewsLetters'),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_NEWSLETTER_SHOW',
								'PS_FEE_NEWSLETTER_DETAIL',
								'PS_FEE_NEWSLETTER_ADD',
								'PS_FEE_NEWSLETTER_EDIT',
								'PS_FEE_NEWSLETTER_DELETE',
								'PS_FEE_NEWSLETTER_PUSH',
								'PS_FEE_NEWSLETTER_FILTER_SCHOOL'), false ),
						"icon" => "fa-dollar" ),
				
				"fee_receipt_index" => array (
						"title" => __ ( 'Find fee reports' ),
						"url" => url_for ( '@ps_fee_receipt' ),
						"active" => PreSchool::askCurrentMenu ( 'psFeeReceipt', 'index' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_RECEIPT_NOTICATION_SHOW',
								'PS_FEE_RECEIPT_NOTICATION_DETAIL',
								'PS_FEE_RECEIPT_NOTICATION_ADD',
								'PS_FEE_RECEIPT_NOTICATION_EDIT',
								'PS_FEE_RECEIPT_NOTICATION_DELETE',
								'PS_FEE_RECEIPT_NOTICATION_PUSH',
								'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' ), false ),
						"icon" => "fa-search-plus" ),

				"fee_receipt_import" => array (
						"title" => __ ( 'Fee receipt import' ),
						"url" => url_for ( '@ps_fee_receipt_import' ),
						"active" => PreSchool::askCurrentMenu ( 'psFeeReceipt', 'import' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_RECEIPT_NOTICATION_IMPORT' ), false ),
						"icon" => "fa-inbox" ) ) );
}

$page_nav ['hr'] = array (
		"title" => __ ( 'HR' ),
		"url" => url_for ( '@ps_member' ),
		"active" => PreSchool::askActiveMenu ( 'psHrDepartments' ) || PreSchool::askActiveMenu ( 'psMember' ) || PreSchool::askActiveMenu ( 'psMemberAbsents' ) || PreSchool::askActiveMenu ( 'psTimesheet' ) || PreSchool::askActiveMenu ( 'psDepartment' ) || PreSchool::askActiveMenu ( 'psFunction' ) || PreSchool::askActiveMenu ( 'psContract' ) || PreSchool::askActiveMenu ( 'psProfessional' ) || PreSchool::askActiveMenu ( 'psCertificate' ) || PreSchool::askCurrentMenu ( 'psAttendances', 'manipulation' ) || PreSchool::askActiveMenu ( 'psTimesheetSummarys' ) || PreSchool::askActiveMenu ( 'psSalary' ) || PreSchool::askActiveMenu ( 'psAllowance' ) || PreSchool::askActiveMenu ( 'psWorkingTime' ) || PreSchool::askCurrentMenu ( 'psRelationship','importMember' ),
		"access" => true,
		"icon" => "fa-address-card-o",
		"sub" => array (
				/*
				"ps_hr_departments" => array (
						"title" => __ ( 'Hr Department' ),
						"url" => url_for ( '@ps_hr_departments' ),
						"active" => PreSchool::askActiveMenu ( 'psHrDepartments' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SYSTEM_USER_MANAGER_DEPARTMENT',
								'PS_SYSTEM_USER_MANAGER_SUB_DEPARTMENT'), false ),
						"icon" => "fa-male" ),
				*/
				"ps_member" => array (
						"title" => __ ( 'HR' ),
						"url" => url_for ( '@ps_member' ),
						"active" => PreSchool::askActiveMenu ( 'psMember' ),						
						"access" => $sf_user->hasCredential ( array (
								'PS_HR_HR_SHOW',
								'PS_HR_HR_DETAIL',
								'PS_HR_HR_ADD',
								'PS_HR_HR_EDIT',
								'PS_HR_HR_DELETE',
								'PS_HR_HR_FILTER_SCHOOL' ), false ),
						"icon" => "fa-users" ),
				"root_attendance_hr" => array (
						"title" => __ ( 'Attendance management' ),
						"url" => '#',
						"active" => PreSchool::askActiveMenu ( 'psMemberAbsents' ) || PreSchool::askActiveMenu ( 'psTimesheet' ) || PreSchool::askCurrentMenu ( 'psAttendances', 'manipulation' ) || PreSchool::askActiveMenu ( 'psTimesheetSummarys' ),
						"access" => false,
						"icon" => "fa-code",
						"sub" => array (
								"ps_timesheet" => array (
										"title" => __ ( 'Timesheet' ),
										"url" => url_for ( '@ps_timesheet' ),
										"active" => PreSchool::askCurrentMenu ( 'psTimesheet', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_TIMESHEET_SHOW',
												'PS_HR_TIMESHEET_DETAIL',
												'PS_HR_TIMESHEET_ADD',
												'PS_HR_TIMESHEET_EDIT',
												'PS_HR_TIMESHEET_DELETE',
												'PS_HR_TIMESHEET_FILTER_SCHOOL' ), false ),
										"icon" => "fa-code" ),

								"ps_timesheet_summarys_statistic" => array (
										"title" => __ ( 'Timesheet statistic' ),
										"url" => url_for ( '@ps_timesheet_summarys_statistic' ),
										"active" => PreSchool::askCurrentMenu ( 'psTimesheetSummarys', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_TIMESHEETSUMMARY_SHOW',
												'PS_HR_TIMESHEETSUMMARY_DETAIL',
												'PS_HR_TIMESHEETSUMMARY_ADD',
												'PS_HR_TIMESHEETSUMMARY_EDIT',
												'PS_HR_TIMESHEETSUMMARY_DELETE',
												'PS_HR_TIMESHEETSUMMARY_FILTER_SCHOOL' ), false ),
										"icon" => "fa-code" ),

								"ps_timesheet_review" => array (
										"title" => __ ( 'Timesheet review' ),
										"url" => url_for ( '@ps_timesheet_review' ),
										"active" => PreSchool::askCurrentMenu ( 'psTimesheet', 'review' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_TIMESHEET_SHOW',
												'PS_HR_TIMESHEET_DETAIL',
												'PS_HR_TIMESHEET_ADD',
												'PS_HR_TIMESHEET_EDIT',
												'PS_HR_TIMESHEET_REVIEW',
												'PS_HR_TIMESHEET_DELETE',
												'PS_HR_TIMESHEET_FILTER_SCHOOL' ), false ),
										"icon" => "fa-code" ),

								"ps_member_absents" => array (
										"title" => __ ( 'Member absents' ),
										"url" => url_for ( '@ps_member_absents' ),
										"active" => PreSchool::askCurrentMenu ( 'psMemberAbsents', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_ABSENTS_SHOW',
												'PS_HR_ABSENTS_DETAIL',
												'PS_HR_ABSENTS_ADD',
												'PS_HR_ABSENTS_EDIT',
												'PS_HR_ABSENTS_DELETE',
												'PS_HR_ABSENTS_FILTER_SCHOOL' ), false ),
										"icon" => "fa-code" ),
								"ps_timesheet_summarys_synthetic" => array (
										"title" => __ ( 'Timesheet summarys' ),
										"url" => url_for ( '@ps_timesheet_summarys_synthetic' ),
										"active" => PreSchool::askCurrentMenu ( 'psTimesheetSummarys', 'synthetic' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_TIMESHEETSUMMARY_SHOW',
												'PS_HR_TIMESHEETSUMMARY_DETAIL',
												'PS_HR_TIMESHEETSUMMARY_ADD',
												'PS_HR_TIMESHEETSUMMARY_EDIT',
												'PS_HR_TIMESHEETSUMMARY_DELETE',
												'PS_HR_TIMESHEETSUMMARY_FILTER_SCHOOL' ), false ),
										"icon" => "fa-code" ),
								"ps_attendances_manipulation" => array (
										"title" => __ ( 'Statistic attendances' ),
										"url" => url_for ( '@ps_attendances_manipulation' ),
										"active" => PreSchool::askCurrentMenu ( 'psAttendances', 'manipulation' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_STUDENT_ATTENDANCE_STATISTIC',
												'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), false ),
										"icon" => "fa-code" ) ) ),

				"root_catalog_hr" => array (
						"title" => __ ( 'Catalog management' ),
						"url" => '#',
						"active" => PreSchool::askActiveMenu ( 'psDepartment' ) || PreSchool::askActiveMenu ( 'psFunction' ) || PreSchool::askActiveMenu ( 'psContract' ) || PreSchool::askActiveMenu ( 'psProfessional' ) || PreSchool::askActiveMenu ( 'psCertificate' ) || PreSchool::askActiveMenu ( 'psSalary' ) || PreSchool::askActiveMenu ( 'psAllowance' ) || PreSchool::askActiveMenu ( 'psWorkingTime' ),
						"access" => true,
						"icon" => "fa-code",

						"sub" => array (

								"ps_department" => array (
										"title" => __ ( 'Catalog Department' ),
										"url" => url_for ( '@ps_department' ),
										"active" => PreSchool::askCurrentMenu ( 'psDepartment', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_DEPARTMENT_SHOW',
												'PS_HR_DEPARTMENT_DETAIL',
												'PS_HR_DEPARTMENT_ADD',
												'PS_HR_DEPARTMENT_EDIT',
												'PS_HR_DEPARTMENT_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_function" => array (
										"title" => __ ( 'Catalog Function' ),
										"url" => url_for ( '@ps_function' ),
										"active" => PreSchool::askCurrentMenu ( 'psFunction', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_FUNCTION_SHOW',
												'PS_HR_FUNCTION_DETAIL',
												'PS_HR_FUNCTION_ADD',
												'PS_HR_FUNCTION_EDIT',
												'PS_HR_FUNCTION_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_contract" => array (
										"title" => __ ( 'Catalog Contract' ),
										"url" => url_for ( '@ps_contract' ),
										"active" => PreSchool::askCurrentMenu ( 'psContract', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_MEMBERCONTRACT_SHOW',
												'PS_HR_MEMBERCONTRACT_DETAIL',
												'PS_HR_MEMBERCONTRACT_ADD',
												'PS_HR_MEMBERCONTRACT_EDIT',
												'PS_HR_MEMBERCONTRACT_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_professional" => array (
										"title" => __ ( 'Catalog Professional' ),
										"url" => url_for ( '@ps_professional' ),
										"active" => PreSchool::askCurrentMenu ( 'psProfessional', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_PROFESSIONAL_SHOW',
												'PS_HR_PROFESSIONAL_DETAIL',
												'PS_HR_PROFESSIONAL_ADD',
												'PS_HR_PROFESSIONAL_EDIT',
												'PS_HR_PROFESSIONAL_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_certificate" => array (
										"title" => __ ( 'Catalog Certificate' ),
										"url" => url_for ( '@ps_certificate' ),
										"active" => PreSchool::askCurrentMenu ( 'psCertificate', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_CERTIFICATE_SHOW',
												'PS_HR_CERTIFICATE_DETAIL',
												'PS_HR_CERTIFICATE_ADD',
												'PS_HR_CERTIFICATE_EDIT',
												'PS_HR_CERTIFICATE_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_salary" => array (
										"title" => __ ( 'Catalog Salary' ),
										"url" => url_for ( '@ps_salary' ),
										"active" => PreSchool::askCurrentMenu ( 'psSalary', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_SALARY_SHOW',
												'PS_HR_SALARY_DETAIL',
												'PS_HR_SALARY_ADD',
												'PS_HR_SALARY_EDIT',
												'PS_HR_SALARY_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_allowance" => array (
										"title" => __ ( 'Catalog Allowance' ),
										"url" => url_for ( '@ps_allowance' ),
										"active" => PreSchool::askCurrentMenu ( 'psAllowance', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_SALARY_SHOW',
												'PS_HR_SALARY_DETAIL',
												'PS_HR_SALARY_ADD',
												'PS_HR_SALARY_EDIT',
												'PS_HR_SALARY_DELETE' ), false ),
										"icon" => "fa-code" ),
								"ps_workingtime" => array (
										"title" => __ ( 'Catalog Working Time' ),
										"url" => url_for ( '@ps_working_time' ),
										"active" => PreSchool::askCurrentMenu ( 'psWorkingTime', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_HR_WORKINGTIME_SHOW',
												'PS_HR_WORKINGTIME_DETAIL',
												'PS_HR_WORKINGTIME_ADD',
												'PS_HR_WORKINGTIME_EDIT',
												'PS_HR_WORKINGTIME_DELETE' ), false ),
										"icon" => "fa-code" ) ) ),
				"ps_member_import" => array (
						"title" => __ ( 'Import member' ),
						"url" => url_for ( '@ps_member_import' ),
						"active" => PreSchool::askCurrentMenu ( 'psRelationship', 'importmember' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_HR_HR_IMPORT',
								'PS_HR_HR_FILTER_SCHOOL' ), false ),
						"icon" => "fa-cloud-upload" ) ) );

$access_ps_customer = $sf_user->hasCredential ( array (
		'PS_SYSTEM_CUSTOMER_SHOW',
		'PS_SYSTEM_CUSTOMER_DETAIL',
		'PS_SYSTEM_CUSTOMER_ADD',
		'PS_SYSTEM_CUSTOMER_EDIT',
		'PS_SYSTEM_CUSTOMER_DELETE',
		'PS_SYSTEM_CUSTOMER_FILTER_SCHOOL' ), false );

$page_nav ["root_report"] = array (
		"title" => __ ( 'Report' ),
		"url" => '#',
		"access" => false,
		"icon" => "fa-bar-chart" );

if ($access_ps_customer) {
	$ps_customer_url = '@ps_customer';
} else {
	$ps_customer_url = '@ps_customer_view';
}

$arr_partner = array ();

array_push ( $arr_partner, array (
		"psCustomer" => array (
				"title" => __ ( 'Customers' ),
				"url" => url_for ( $ps_customer_url ),
				"active" => PreSchool::askActiveMenu ( 'psCustomer' ),
				"access" => true,
				"icon" => "fa-ravelry" ) ) );

array_push ( $arr_partner, array (
		"ps_work_places" => array (
				"title" => __ ( 'Work places' ),
				"url" => url_for ( "@ps_work_places" ),
				"active" => PreSchool::askActiveMenu ( 'psWorkPlaces' ),
				"access" => true,
				"icon" => "fa-building-o" ) ) );

$page_nav ['partner'] = array (
		"title" => __ ( 'Partner' ),
		"active" => (PreSchool::askActiveMenu ( 'psSemester' ) || PreSchool::askActiveMenu ( 'psCustomer' ) || PreSchool::askActiveMenu ( 'psWorkPlaces' ) || PreSchool::askActiveMenu ( 'psClassRooms' ) || PreSchool::askActiveMenu ( 'psCameras' ) || PreSchool::askActiveMenu ( 'psConfigLateFees' ) || PreSchool::askActiveMenu ( 'psConfigLatePayments' )),
		"access" => true,
		"icon" => "fa-university",
		"sub" => array (
				"psCustomer" => array (
						"title" => __ ( 'Customers' ),
						"url" => url_for ( $ps_customer_url ),
						"active" => PreSchool::askActiveMenu ( 'psCustomer' ),
						"access" => true,
						"icon" => "fa-ravelry" ),

				"ps_work_places" => array (
						"title" => __ ( 'Work places' ),
						"url" => url_for ( "@ps_work_places" ),
						"active" => PreSchool::askActiveMenu ( 'psWorkPlaces' ),
						"access" => true,
						"icon" => "fa-building-o" ),

				"ps_class_rooms" => array (
						"title" => __ ( 'Class room' ),
						"url" => url_for ( "@ps_class_rooms" ),
						"active" => PreSchool::askActiveMenu ( 'psClassRooms' ),
						"access" => true,
						"icon" => "fa-columns" ),
				/*
				"ps_semester_config" => array (
						"title" => __ ( 'Semester config' ),
						"url" => url_for ( '@ps_semester_config' ),
						"active" => PreSchool::askActiveMenu ( 'psSemester' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_SYSTEM_ROOMS_FILTER_SCHOOL',
								'PS_SYSTEM_ROOMS_ADD',
								'PS_SYSTEM_ROOMS_EDIT',
								'PS_SYSTEM_ROOMS_DELETE' ), false ),
						"icon" => "fa-code" ),
				*/
				"ps_config_late_fees" => array (
						"title" => __ ( 'Overtime fees' ),
						"url" => url_for ( '@ps_config_late_fees' ),
						"active" => PreSchool::askActiveMenu ( 'psConfigLateFees' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_CONFIG_LATE_FEES_FILTER_SCHOOL',
								'PS_FEE_CONFIG_LATE_FEES_SHOW',
								'PS_FEE_CONFIG_LATE_FEES_DETAIL',
								'PS_FEE_CONFIG_LATE_FEES_ADD',
								'PS_FEE_CONFIG_LATE_FEES_EDIT',
								'PS_FEE_CONFIG_LATE_FEES_DELETE' ), false ),
						"icon" => "fa-dollar" ),

				"config_late_payments" => array (
						"title" => __ ( 'Overtime payment' ),
						"url" => url_for ( '@config_late_payments' ),
						"active" => PreSchool::askActiveMenu ( 'psConfigLatePayments' ),
						"access" => $sf_user->hasCredential ( array (
								'PS_FEE_CONFIG_LATE_PAYMENT_FILTER_SCHOOL',
								'PS_FEE_CONFIG_LATE_PAYMENT_SHOW',
								'PS_FEE_CONFIG_LATE_PAYMENT_ADD',
								'PS_FEE_CONFIG_LATE_PAYMENT_EDIT',
								'PS_FEE_CONFIG_LATE_PAYMENT_DELETE' ), false ),
						"icon" => "fa-dollar" ),
				
				/*
		 * "ps_cameras" => array (
		 * "title" => __ ( 'Camera' ),
		 * "url" => url_for ( '@ps_cameras' ),
		 * "active" => PreSchool::askActiveMenu ( 'psCameras' ),
		 * "access" => $sf_user->hasCredential ( array (
		 * 'PS_SYSTEM_CAMERA_SHOW',
		 * 'PS_SYSTEM_CAMERA_DETAIL',
		 * 'PS_SYSTEM_CAMERA_ADD',
		 * 'PS_SYSTEM_CAMERA_EDIT',
		 * 'PS_SYSTEM_CAMERA_DELETE' ), false ),
		 * "icon" => "fa-file-video-o" ),
		 */

		/*
		 * "ps_chattime" => array (
		 * "title" => __ ( 'Chat Time Config' ),
		 * "url" => url_for ( '@ps_chat_time' ),
		 * "active" => PreSchool::askActiveMenu ( 'psChatTime' ),
		 * "access" => $sf_user->hasCredential ( array (
		 * 'PS_SYSTEM_CHAT_TIME_CONFIG_SHOW',
		 * 'PS_SYSTEM_CHAT_TIME_CONFIG_DETAIL',
		 * 'PS_SYSTEM_CHAT_TIME_CONFIG_ADD',
		 * 'PS_SYSTEM_CHAT_TIME_CONFIG_EDIT',
		 * 'PS_SYSTEM_CHAT_TIME_CONFIG_DELETE' ), false ),
		 * "icon" => "fa-clock-o" )
		 */
		) );

$access_users = $sf_user->hasCredential ( array (
		'PS_SYSTEM_USER_SHOW',
		'PS_SYSTEM_USER_DETAIL',
		'PS_SYSTEM_USER_ADD',
		'PS_SYSTEM_USER_EDIT',
		'PS_SYSTEM_USER_DELETE',
		'PS_SYSTEM_USER_FILTER_SCHOOL',
		'PS_SYSTEM_GROUP_USER_SHOW',
		'PS_SYSTEM_GROUP_USER_DETAIL',
		'PS_SYSTEM_GROUP_USER_ADD',
		'PS_SYSTEM_GROUP_USER_EDIT',
		'PS_SYSTEM_GROUP_USER_EDIT_DETAIL',
		'PS_SYSTEM_GROUP_USER_DELETE',
		'PS_SYSTEM_GROUP_USER_FILTER_SCHOOL' ), false );

$is_active = (PreSchool::askCurrentMenu ( 'psAttendances', 'import' ) || PreSchool::askActiveMenu ( 'sfGuardUser' ) || PreSchool::askActiveMenu ( 'sfGuardGroup' ) || PreSchool::askActiveMenu ( 'psObjectGroups' ) || PreSchool::askActiveMenu ( 'psSchoolYear' ) || PreSchool::askActiveMenu ( 'psConstantOption' ) || PreSchool::askActiveMenu ( 'psConstant' ) || PreSchool::askActiveMenu ( 'psImages' ) || PreSchool::askActiveMenu ( 'psAdviceCategories' ) || PreSchool::askActiveMenu ( 'psTemplateExports' ) || PreSchool::askActiveMenu ( 'psObjectGroups' ) || PreSchool::askActiveMenu ( 'psSchoolYear' ) || PreSchool::askActiveMenu ( 'psImages' ) || PreSchool::askActiveMenu ( 'Relationship' ) || PreSchool::askActiveMenu ( 'psTypeSchool' ) || PreSchool::askActiveMenu ( 'psProvince' ) || PreSchool::askActiveMenu ( 'psDistrict' ) || PreSchool::askActiveMenu ( 'psWard' ) || PreSchool::askActiveMenu ( 'psEthnic' ) || PreSchool::askActiveMenu ( 'psReligion' ) || PreSchool::askActiveMenu ( 'psContract' ) || PreSchool::askActiveMenu ( 'psProfessional' ) || PreSchool::askActiveMenu ( 'psCertificate' ) || PreSchool::askActiveMenu ( 'psFunction' ) || PreSchool::askActiveMenu ( 'psDepartment' ) || PreSchool::askActiveMenu ( 'psFunction' )
		|| PreSchool::askActiveMenu ( 'psSystemCmsContent' ) || PreSchool::askActiveMenu ( 'psApp' ) || PreSchool::askActiveMenu ( 'psAppPermission' ) || PreSchool::askActiveMenu ( 'psHistoryScreenRelatives' ) || PreSchool::askActiveMenu ( 'psStudentServiceCourseComment' ) || PreSchool::askActiveMenu ( 'psStudentFeatures' )
		);

$page_nav ['root_system'] = array (
		"title" => __ ( "System" ),
		"active" => $is_active,
		"icon" => "fa-cube txt-color-blue",
		"access" => true,
		"sub" => array (
				"users" => array (
						"title" => __ ( 'Users manager' ),
						"active" => (PreSchool::askActiveMenu ( 'sfGuardUser' ) || PreSchool::askActiveMenu ( 'sfGuardGroup' )),
						"access" => $access_users,
						"icon" => "fa-user-circle-o",
						"sub" => array (
								"users" => array (
										"title" => __ ( 'Users' ),
										"url" => url_for ( '@sf_guard_user' ),
										"active" => PreSchool::askActiveMenu ( 'sfGuardUser' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_USER_SHOW',
												'PS_SYSTEM_USER_DETAIL',
												'PS_SYSTEM_USER_ADD',
												'PS_SYSTEM_USER_EDIT',
												'PS_SYSTEM_USER_DELETE' ), false ),
										"icon" => "fa-user-o" ),
								"groups" => array (
										"title" => __ ( 'Groups' ),
										"url" => url_for ( '@sf_guard_group' ),
										"active" => PreSchool::askActiveMenu ( 'sfGuardGroup' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_GROUP_USER_SHOW',
												'PS_SYSTEM_GROUP_USER_DETAIL',
												'PS_SYSTEM_GROUP_USER_ADD',
												'PS_SYSTEM_GROUP_USER_EDIT',
												'PS_SYSTEM_GROUP_USER_DELETE' ), false ),
										"icon" => "fa-users" ),
								"created_account" => array (
    						        "title" => __ ( 'Created account' ),
    						        "url" => url_for ( '@ps_user_created_account_auto' ),
    						        "active" => PreSchool::askCurrentMenu ( 'sfGuardUser', 'createdAccount'),
    						        "access" => $sf_user->hasCredential ( array (
    						            'PS_SYSTEM_USER_SHOW',
    						            'PS_SYSTEM_USER_ADD',
    						            'PS_SYSTEM_USER_EDIT',
    						            'PS_SYSTEM_USER_DELETE'
    						        ), false ),
    						        "icon" => "fa-users"
    						    )
							) ),

				"catalog_general" => array (
						"title" => __ ( "Catalog general" ),
						"icon" => "fa-th-list",
						"active" => PreSchool::askActiveMenu ( 'psAdviceCategories' ) || PreSchool::askActiveMenu ( 'featureoption' ) || PreSchool::askActiveMenu ( 'psTemplateExports' ) || PreSchool::askActiveMenu ( 'psObjectGroups' ) || PreSchool::askActiveMenu ( 'psSchoolYear' ) || PreSchool::askActiveMenu ( 'psImages' ) || PreSchool::askActiveMenu ( 'Relationship' ) || PreSchool::askActiveMenu ( 'psTypeSchool' ) || PreSchool::askActiveMenu ( 'psProvince' ) || PreSchool::askActiveMenu ( 'psDistrict' ) || PreSchool::askActiveMenu ( 'psWard' ) || PreSchool::askActiveMenu ( 'psEthnic' ) || PreSchool::askActiveMenu ( 'psReligion' ) || PreSchool::askActiveMenu ( 'psContract' ) || PreSchool::askActiveMenu ( 'psProfessional' ) || PreSchool::askActiveMenu ( 'psCertificate' ) || PreSchool::askActiveMenu ( 'psFunction' ) || PreSchool::askActiveMenu ( 'psDepartment' ) || PreSchool::askActiveMenu ( 'psFunction' ),
						"access" => true,
						"sub" => array (

								"PsAdviceCategories" => array (
										"title" => __ ( 'Advices Category' ),
										"url" => url_for ( '@ps_advice_categories' ),
										"active" => PreSchool::askActiveMenu ( 'psAdviceCategories' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_STUDENT_ADVICE_CATEGORIES_SHOW',
												'PS_STUDENT_ADVICE_CATEGORIES_ADD',
												'PS_STUDENT_ADVICE_CATEGORIES_DETAIL',
												'PS_STUDENT_ADVICE_CATEGORIES_EDIT',
												'PS_STUDENT_ADVICE_CATEGORIES_DELETE' ), false ),
										"icon" => "fa-code" ),
/*
								"ps_template_exports" => array (
										"title" => __ ( "Catalog template exports" ),
										"url" => url_for ( "@ps_template_exports" ),
										"active" => PreSchool::askCurrentMenu ( 'psTemplateExports', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_TEMPLATE_REPORT_SHOW',
												'PS_SYSTEM_TEMPLATE_REPORT_DETAIL',
												'PS_SYSTEM_TEMPLATE_REPORT_ADD',
												'PS_SYSTEM_TEMPLATE_REPORT_EDIT',
												'PS_SYSTEM_TEMPLATE_REPORT_DELETE' ), false ),
										"icon" => "fa-file-excel-o" ),*/

								"ps_object_groups" => array (
										"title" => __ ( 'Object group' ),
										"url" => url_for ( "@ps_object_groups" ),
										"active" => PreSchool::askCurrentMenu ( 'psObjectGroups', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_OBJECT_GROUPS_SHOW',
												'PS_OBJECT_GROUPS_DETAIL',
												'PS_OBJECT_GROUPS_ADD',
												'PS_OBJECT_GROUPS_EDIT',
												'PS_OBJECT_GROUPS_DELETE' ), false ),
										"icon" => "fa-code" ),
								"ps_school_year" => array (
										"title" => __ ( 'School year' ),
										"url" => url_for ( "@ps_school_year" ),
										"active" => PreSchool::askCurrentMenu ( 'psSchoolYear', 'index' ),
										"access" => true,
										"icon" => "fa-code" ),

								"ps_images" => array (
										"title" => __ ( 'Images' ),
										"url" => url_for ( "@ps_images" ),
										"active" => PreSchool::askCurrentMenu ( 'psImages', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_IMAGES_SHOW',
												'PS_SYSTEM_IMAGES_ADD',
												'PS_SYSTEM_IMAGES_EDIT',
												'PS_SYSTEM_IMAGES_DELETE' ), false ),
										"icon" => "fa-picture-o" ),

								"ps_relationship" => array (
										"title" => __ ( 'Relationship' ),
										"url" => url_for ( '@relationship_psRelationship' ),
										"active" => PreSchool::askCurrentMenu ( 'Relationship', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_RELATIONSHIP_SHOW',
												'PS_SYSTEM_RELATIONSHIP_DETAIL',
												'PS_SYSTEM_RELATIONSHIP_ADD',
												'PS_SYSTEM_RELATIONSHIP_EDIT',
												'PS_SYSTEM_RELATIONSHIP_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_type_school" => array (
										"title" => __ ( 'Catalog type school' ),
										"url" => url_for ( '@ps_type_school' ),
										"active" => PreSchool::askCurrentMenu ( 'psTypeSchool', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_TYPE_SCHOOL_SHOW',
												'PS_SYSTEM_TYPE_SCHOOL_DETAIL',
												'PS_SYSTEM_TYPE_SCHOOL_ADD',
												'PS_SYSTEM_TYPE_SCHOOL_EDIT',
												'PS_SYSTEM_TYPE_SCHOOL_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_province" => array (
										"title" => __ ( 'Catalog Province' ),
										"url" => url_for ( '@ps_province' ),
										"active" => PreSchool::askCurrentMenu ( 'psProvince', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_PROVINCE_SHOW',
												'PS_SYSTEM_PROVINCE_DETAIL',
												'PS_SYSTEM_PROVINCE_ADD',
												'PS_SYSTEM_PROVINCE_EDIT',
												'PS_SYSTEM_PROVINCE_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_district" => array (
										"title" => __ ( 'Catalog District' ),
										"url" => url_for ( '@ps_district' ),
										"active" => PreSchool::askCurrentMenu ( 'psDistrict', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_DISTRICT_SHOW',
												'PS_SYSTEM_DISTRICT_DETAIL',
												'PS_SYSTEM_DISTRICT_ADD',
												'PS_SYSTEM_DISTRICT_EDIT',
												'PS_SYSTEM_DISTRICT_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_ward" => array (
										"title" => __ ( 'Catalog Ward' ),
										"url" => url_for ( '@ps_ward' ),
										"active" => PreSchool::askCurrentMenu ( 'psWard', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_WARD_SHOW',
												'PS_SYSTEM_WARD_DETAIL',
												'PS_SYSTEM_WARD_ADD',
												'PS_SYSTEM_WARD_EDIT',
												'PS_SYSTEM_WARD_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_ethnic" => array (
										"title" => __ ( 'Catalog Ethnic' ),
										"url" => url_for ( '@ps_ethnic' ),
										"active" => PreSchool::askCurrentMenu ( 'psEthnic', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_ETHNIC_SHOW',
												'PS_SYSTEM_ETHNIC_DETAIL',
												'PS_SYSTEM_ETHNIC_ADD',
												'PS_SYSTEM_ETHNIC_EDIT',
												'PS_SYSTEM_ETHNIC_DELETE' ), false ),
										"icon" => "fa-code" ),

								"ps_religion" => array (
										"title" => __ ( 'Catalog Religion' ),
										"url" => url_for ( '@ps_religion' ),
										"active" => PreSchool::askCurrentMenu ( 'psReligion', 'index' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_RELIGION_SHOW',
												'PS_SYSTEM_RELIGION_DETAIL',
												'PS_SYSTEM_RELIGION_ADD',
												'PS_SYSTEM_RELIGION_EDIT',
												'PS_SYSTEM_RELIGION_DELETE' ), false ),
										"icon" => "fa-code" ) ) ),
				// end array sub

				"key_application" => array (
						"title" => __ ( "Application" ),
						"active" => PreSchool::askActiveMenu ( 'psSystemCmsContent' ) || PreSchool::askActiveMenu ( 'psApp' ) || PreSchool::askActiveMenu ( 'psAppPermission' ),
						"access" => myUser::isAdministrator (),
						"icon" => "fa-crosshairs",
						"sub" => array (
								"systemcmscontent" => array (
										"title" => __ ( "System CMS Content" ),
										"url" => url_for ( "@ps_system_cms_content" ),
										"active" => PreSchool::askActiveMenu ( 'psSystemCmsContent' ),
										"access" => $sf_user->hasCredential ( array (
												'PS_SYSTEM_CMS_CONTENT_SHOW',
												'PS_SYSTEM_CMS_CONTENT_DETAIL',
												'PS_SYSTEM_CMS_CONTENT_ADD',
												'PS_SYSTEM_CMS_CONTENT_EDIT',
												'PS_SYSTEM_CMS_CONTENT_DELETE' ), false ),
										"icon" => "fa-book" ),
								"defined_application" => array (
										"title" => __ ( "Defined application" ),
										"url" => url_for ( "@ps_app" ),
										"active" => PreSchool::askActiveMenu ( 'psApp' ),
										"access" => $defined_application,
										"icon" => "fa-suitcase" ),
								"defined_function_application" => array (
										"title" => __ ( "Application permission" ),
										"url" => url_for ( "@ps_app_permission" ),
										"active" => PreSchool::askActiveMenu ( 'psAppPermission' ),
										"access" => $defined_function_application,
										"icon" => "fa-asterisk" ) ) ),

				"support " => array (
						"title" => __ ( "Support" ),
						"url" => "#",
						"access" => true,
						"icon" => "fa-support ",
						"sub" => array (
								/*
								"livechat" => array (
										"title" => __ ( "Support online" ),
										"url" => "#",
										"active" => true,
										"access" => true,
										"icon" => "fa-comments-o" 
								),*/
								
								"use_guide" => array (
										"title" => __ ( "Use guide" ),
										"url" => url_for ( "@ps_cms_use_guides" ),
										"active" => PreSchool::askActiveMenu ( 'psCmsUseGuide' ),
										"access" => true,
										"url_target" => "_blank",
										"icon" => "fa-question-circle-o" ),
								/*
								"help" => array (
										"title" => __ ( "Help" ),
										"url" => "#",
										"active" => true,
										"access" => true,
										"icon" => "fa-question-circle-o" 
								),*/
								
								"about" => array (
										"title" => __ ( "About" ),
										"url" => "#about",
										"active" => true,
										"access" => true,
										"icon" => "fa-info-circle" ) ) ),
				"history " => array (
						"title" => __ ( "History" ),
						"url" => "#",
						"access" => true,
						"active" => PreSchool::askActiveMenu ( 'psHistoryScreenRelatives' ) || PreSchool::askActiveMenu ( 'psStudentServiceCourseComment' ) || PreSchool::askActiveMenu ( 'psStudentFeatures' ),
						"icon" => "fa-history ",
						"sub" => array (
								
								"service_course_history" => array (
										"title" => __ ( "Service course history" ),
										"url" => url_for ( "@ps_student_service_course_comment_history" ),
										"active" => PreSchool::askCurrentMenu ( 'psStudentServiceCourseComment', 'history' ),
										"access" => false,
										"icon" => "fa-indent" ),

								"features_history" => array (
										"title" => __ ( "Features history" ),
										"url" => url_for ( "@ps_student_features_history" ),
										"active" => PreSchool::askCurrentMenu ( 'psStudentFeatures', 'history' ),
										"access" => false,
										"icon" => "fa-delicious" ),
										
								"logtime_history" => array (
										"title" => __ ( "Logtime history" ),
										"url" => url_for ( "@ps_logtimes_history" ),
										"active" => PreSchool::askCurrentMenu ( 'psLogtimes', 'history' ),
										"access" => true,
										"icon" => "fa-book" ),
								"import_history" => array (
										"title" => __ ( "Import history" ),
										"url" => url_for ( "@ps_history_import" ),
										"active" => PreSchool::askCurrentMenu ( 'psReceiptTemporary', 'import' ),
										"access" => true,
										"icon" => "fa-level-up" ),
								"history_screen_relatives" => array (
										"title" => __ ( "History screen relatives" ),
										"url" => url_for ( "@ps_history_screen_relatives" ),
										"active" => PreSchool::askActiveMenu ( 'psHistoryScreenRelatives' ),
										"access" => true,
										"icon" => "fa-history" ) ) ),
				"root_import_attendance" => array (
					"title" => __ ( "Import" ),
					"url" => "#",
					"access" => true,
					"active" => PreSchool::askCurrentMenu ( 'psAttendances', 'import' ) || PreSchool::askCurrentMenu ( 'psReceipts', 'importAmountLastMonth' ),
					"icon" => "fa-upload",
					"sub" => array (
						"import_attendance" => array (
							"title" => __ ( "Import logtimes" ),
							"url" => url_for ( "@ps_attendances_import" ),
							"active" => PreSchool::askCurrentMenu ( 'psAttendances', 'import' ),
							"access" => $sf_user->hasCredential ( array (
									'PS_STUDENT_ATTENDANCE_IMPORT' ), false ),
							"icon" => "fa-upload" ),
						"ps_receipts_import_amount_last_month" => array (
							"title" => __ ( "Import balance last month" ),
							"url" => url_for ( "@ps_receipts_import_amount_last_month" ),
							"active" => PreSchool::askCurrentMenu ( 'psReceipts', 'importAmountLastMonth' ),
							"access" => $sf_user->hasCredential ( array (
									'PS_STUDENT_ATTENDANCE_IMPORT' ), false ),
							"icon" => "fa-upload" ),
						"import_register_service" => array (
							"title" => __ ( "Import register service" ),
							"url" => url_for ( "@import_register_service" ),
							"active" => PreSchool::askCurrentMenu ( 'psReceipts', 'importRegisterService' ),
							"access" => $sf_user->hasCredential ( array (
									'PS_STUDENT_ATTENDANCE_IMPORT' ), false ),
							"icon" => "fa-upload" ),
						"import_template_receipt" => array (
							"title" => __ ( "Import templete receipt" ),
							"url" => url_for ( "@import_template_receipt" ),
							"active" => PreSchool::askCurrentMenu ( 'psReceipts', 'importTemplate' ),
							"access" => $sf_user->hasCredential ( array (
									'PS_STUDENT_ATTENDANCE_IMPORT' ), false ),
							"icon" => "fa-upload" )
					)
				)
			) );

?>
<nav>
	<ul>
		<?php PreNav::nav($page_nav);?>
	</ul>
</nav>
<!-- END NAVIGATION -->