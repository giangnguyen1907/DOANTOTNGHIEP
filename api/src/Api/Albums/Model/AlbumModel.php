<?php

/**
 *
 * @package truongnet.com
 * @subpackage API app
 *
 * @file AlbumModel.php
 * @author trint
 *
 */
namespace Api\Albums\Model;

use App\Model\BaseModel;

class AlbumModel extends BaseModel {
	
	protected $table = TBL_PS_ALBUMS;

	/**
	 * Lay danh sach album cho mot giao vien
	 *
	 * $member_id - ID Nhân sự, $ps_customer_id - ID trường học
	 */
	public static function getListAlbumsOfMember($member_id, $ps_customer_id, $user_id) {
		
		$tbl = TBL_PS_ALBUMS;

		$list_albums = AlbumModel::select ( 'TC.ps_myclass_id as ps_class_id', $tbl . '.album_key', $tbl . '.id', $tbl . '.title', $tbl . '.is_activated', $tbl . '.note', $tbl . '.number_like', $tbl . '.number_dislike', $tbl . '.created_at', $tbl . '.number_img', $tbl . '.number_view', $tbl . '.user_created_id AS user_created_id', 'abi.url_thumbnail AS url_thumbnail','MC.name AS class_name' )
		
		->leftJoin ( TBL_PS_ALBUM_ITEMS . ' as abi', function ($q) use ($member_id, $user_id, $tbl) {
			$q->on ( 'abi.album_id', '=', $tbl . '.id' )
			->whereRaw ( '((abi.user_created_id = ' . $user_id . ' AND abi.is_activated IN (' . STATUS_NOT_ACTIVE . ',' . STATUS_ACTIVE . ') ) OR (abi.user_created_id <> ' . $user_id . ' AND abi.is_activated = ' . STATUS_ACTIVE . ' ))' );
		} )
		->join ( TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($member_id, $tbl) {
			$q->on ( 'TC.ps_myclass_id', '=', $tbl . '.ps_class_id' )
			
			->whereRaw('(DATE_FORMAT(TC.start_at, "%Y%m%d") <= DATE_FORMAT('.$tbl.'.created_at, "%Y%m%d") AND  (TC.stop_at >= DATE_FORMAT('.$tbl.'.created_at, "%Y%m%d") OR TC.stop_at IS NULL) )')
			
			->whereRaw ( '(TC.ps_member_id = ' . $member_id . ' OR (' . $tbl . '.ps_class_id IS NULL AND ' . $tbl . '.is_activated = ' . STATUS_ACTIVE . '))' );
		} )
		->join ( CONST_TBL_MYCLASS . ' as MC', function ($q){
			$q->on ( 'TC.ps_myclass_id', '=', 'MC.id' )
			->where ( 'MC.is_activated', '=', STATUS_ACTIVE );
		} )
		
		->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id );

		$list_albums = $list_albums->whereRaw ( '(' . $tbl . '.user_created_id = ' . $user_id . ' OR (' . $tbl . '.user_created_id <> ' . $user_id . ' AND ' . $tbl . '.is_activated = ' . STATUS_ACTIVE . ' ) )' );

		$list_albums = $list_albums->groupBy ( $tbl . '.id' );

		$list_albums = $list_albums->orderByRaw ( $tbl . '.created_at desc', 'abi.created_at desc' )->distinct ( $tbl . '.id' )->get ();

		return $list_albums;
	}

	/**
	 * Lấy chi tiết Album
	 *
	 * @author thangnc
	 *        
	 * @param $ps_album_id int
	 *        	Album
	 * @param $ps_customer_id int
	 *        	ID trường học
	 * @param $_arr_img_activated mixed
	 *        	chứa trạngt hái cần lấy
	 *        	
	 * @return mixed
	 */
	public static function getAlbumById($ps_album_id, $ps_customer_id, $_arr_img_activated = null) {
		
		$tbl = TBL_PS_ALBUMS;
		
		if (count ( $_arr_img_activated ) <= 0)			
			return AlbumModel::select ('MC.name AS class_name', $tbl.'.*')
			->leftJoin ( CONST_TBL_MYCLASS . ' as MC', function ($q) use($tbl){
				$q->on ( 'MC.id', '=', $tbl.'.ps_class_id' )
				->where ( 'MC.is_activated', '=', STATUS_ACTIVE );
			} )->where ( $tbl.'.id', $ps_album_id )->where ( $tbl.'.ps_customer_id', '=', $ps_customer_id )->get ()->first ();
			//return AlbumModel::where ( 'id', $ps_album_id )->where ( 'ps_customer_id', '=', $ps_customer_id )->get ()->first ();
		else
			return AlbumModel::select ('MC.name AS class_name', $tbl.'.*')
			->leftJoin ( CONST_TBL_MYCLASS . ' as MC', function ($q)use($tbl){
				$q->on ( 'MC.id', '=', $tbl.'.ps_class_id' )
				->where ( 'MC.is_activated', '=', STATUS_ACTIVE );
			} )
			->where ( $tbl.'.id', $ps_album_id )->where ($tbl.'.ps_customer_id', $ps_customer_id )->whereIn ( $tbl.'.is_activated', $_arr_img_activated )->get ()->first ();
			//return AlbumModel::where ( 'id', $ps_album_id )->where ( 'ps_customer_id', $ps_customer_id )->whereIn ( 'is_activated', $_arr_img_activated )->get ()->first ();
	}

	/**
	 * Lay danh sach album của 1 học sinh cho mot phu huynh
	 *
	 * @author thangnc
	 *        
	 * @param $student_id - int ID học sinh
	 * @param $ps_customer_id - int ID trường học
	 * @return mixed
	 */
	public static function getListAlbumsOfRelative($student_id, $ps_customer_id) {
		
		$tbl = TBL_PS_ALBUMS;

		$list_albums = AlbumModel::select ('MC.name AS class_name' ,'SC.myclass_id AS ps_class_id', $tbl . '.album_key AS album_key', $tbl . '.id AS id', $tbl . '.title AS title', $tbl . '.is_activated AS is_activated', $tbl . '.note AS note', $tbl . '.number_view', $tbl . '.number_like AS number_like', $tbl . '.number_dislike AS number_dislike', 'abi.url_thumbnail AS url_thumbnail' )
		->selectRaw ( $tbl . '.number_img as number_img' )
		->leftJoin ( TBL_PS_ALBUM_ITEMS . ' as abi', function ($q) use ($tbl) {
			$q->on ( 'abi.album_id', '=', $tbl . '.id' )
			->where ( 'abi.is_activated', '=', STATUS_ACTIVE );
		} )->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($student_id, $tbl) {
			$q->on ( 'SC.myclass_id', '=', $tbl . '.ps_class_id' )
			->whereRaw('(DATE_FORMAT(SC.start_at, "%Y%m%d") <= DATE_FORMAT('.$tbl.'.created_at, "%Y%m%d") AND  (SC.stop_at >= DATE_FORMAT('.$tbl.'.created_at, "%Y%m%d") OR SC.stop_at IS NULL) )')
			->whereRaw ( '(SC.student_id = ' . $student_id . ' OR ' . $tbl . '.ps_class_id IS NULL)' );			
		} )		
		->join ( CONST_TBL_MYCLASS . ' as MC', function ($q){
			$q->on ( 'SC.myclass_id', '=', 'MC.id' )
			->where ( 'MC.is_activated', '=', STATUS_ACTIVE );
		} )		
		->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id )
		->where ( $tbl . '.is_activated', '=', STATUS_ACTIVE )
		->groupBy ( 'abi.album_id' )
		->orderByRaw ( $tbl . '.created_at desc' )->get ();

		return $list_albums;
	}
	
	/**
	 * Lay n album moi nhat của 1 học sinh cho phu huynh
	 *
	 * @author thangnc
	 *
	 * @param $student_id - int ID học sinh
	 * @param $ps_customer_id - int ID trường học
	 * @return mixed
	 */
	public static function getAlbumsOfStudent($student_id, $ps_customer_id, $limit = 0) {
		
		$tbl = TBL_PS_ALBUMS;
		
		$list_albums = AlbumModel::select ($tbl . '.id AS id', 'abi.url_thumbnail AS url_thumbnail', 'abi.id AS image_id' )
		->leftJoin ( TBL_PS_ALBUM_ITEMS . ' as abi', function ($q) use ($tbl) {
			$q->on ( 'abi.album_id', '=', $tbl . '.id' )
			->where ( 'abi.is_activated', '=', STATUS_ACTIVE );
		})->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($student_id, $tbl) {
			$q->on ( 'SC.myclass_id', '=', $tbl . '.ps_class_id' )
			->whereRaw('(DATE_FORMAT(SC.start_at, "%Y%m%d") <= DATE_FORMAT('.$tbl.'.created_at, "%Y%m%d") AND  (SC.stop_at >= DATE_FORMAT('.$tbl.'.created_at, "%Y%m%d") OR SC.stop_at IS NULL) )')
			->whereRaw ( '(SC.student_id = ' . $student_id . ' OR ' . $tbl . '.ps_class_id IS NULL)' );
		} )
		->join ( CONST_TBL_MYCLASS . ' as MC', function ($q){
			$q->on ( 'SC.myclass_id', '=', 'MC.id' )
			->where ( 'MC.is_activated', '=', STATUS_ACTIVE );
		} )
		->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id )
		->where ( $tbl . '.is_activated', '=', STATUS_ACTIVE )
		->whereRaw ( 'abi.url_thumbnail IS NOT NULL')
		->groupBy ( 'abi.album_id' )
		->orderBy( $tbl . '.created_at','desc' )
		->orderBy( 'abi.created_at','desc' )
		->limit($limit)->get ();
		
		return $list_albums;
	}

	/**
	 * ----------- ------------*
	 */

	// Lay danh sach album theo lop
	/**
	 * Danh cho giao vien - Lay các Album cua lop trong do cac Album đã public + Album chưa public của giáo viên tạo
	 *
	 * *
	 */
	public static function getListAlbumsForHR($ps_class_id, $user_id = null) {
		$tbl = TBL_PS_ALBUMS;

		$list_albums = AlbumModel::select ( $tbl . '.id', $tbl . '.album_key', $tbl . '.title', $tbl . '.is_activated', $tbl . '.note', $tbl . '.number_like', $tbl . '.count_comment', $tbl . '.number_dislike', $tbl . '.number_img', $tbl . '.user_created_id', $tbl . '.updated_at', $tbl . '.ps_class_id', 'abi.url_thumbnail AS url_thumbnail' )->leftjoin ( TBL_PS_ALBUM_ITEMS . ' as abi', 'abi.album_id', '=', $tbl . '.id' )->where ( $tbl . '.ps_class_id', '=', $ps_class_id )->whereRaw ( '(' . $tbl . '.is_activated = 0 AND ' . $tbl . '.user_created_id = ' . $user_id . ') OR (' . $tbl . '.is_activated = 1 )' )->groupBy ( 'abi.album_id' )->orderByRaw ( $tbl . '.created_at desc', 'abi.created_at desc' )->get ();

		return $list_albums;
	}

	/**
	 * Lay danh sach album cho cua giao vien
	 * $member_id - ID Nhân sự, $ps_customer_id - ID trường học
	 */
	public static function getListAlbumsOfTeacher($user_id, $member_id, $ps_customer_id) {
		$tbl = TBL_PS_ALBUMS;

		$list_albums = AlbumModel::select ( 'TC.ps_myclass_id as ps_class_id', $tbl . '.album_key', $tbl . '.id', $tbl . '.album_key', $tbl . '.title', $tbl . '.is_activated', $tbl . '.note', $tbl . '.number_like', $tbl . '.number_dislike', $tbl . '.created_at', $tbl . '.number_img', $tbl . '.user_created_id AS user_created_id', 'abi.url_thumbnail AS url_thumbnail' )
		->leftJoin ( TBL_PS_ALBUM_ITEMS . ' as abi', 'abi.album_id', '=', $tbl . '.id' )
		->leftJoin ( TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($member_id, $tbl) {
			$q->on ( 'TC.ps_myclass_id', '=', $tbl . '.ps_class_id' )
			
			//->whereRaw('(DATE_FORMAT(TC.start_at, "%Y%m%d") <= DATE_FORMAT('.$tbl.'.created_at, "%Y%m%d") AND  (TC.stop_at >= DATE_FORMAT('.$tbl.'.created_at, "%Y%m%d") OR TC.stop_at IS NULL) )')
			
			->whereRaw ( '(TC.ps_member_id = ' . $member_id . ' OR (' . $tbl . '.ps_class_id IS NULL AND ' . $tbl . '.is_activated = 1))' );
		} )->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id );

		// ->where($tbl.'.user_created_id', '=', $user_id);

		// $list_albums = $list_albums->whereRaw('('.$tbl.'.user_created_id = '.$user_id.' )');

		$list_albums = $list_albums->groupBy ( 'abi.album_id' );

		$list_albums = $list_albums->orderByRaw ( $tbl . '.created_at desc', 'abi.created_at desc' )->get ();

		return $list_albums;
	}
}

class AlbumModelOLD extends BaseModel {
	
	protected $table = TBL_PS_ALBUMS . ' as alb';

	// Lay album theo album_key
	public static function getAlbumByAlbumKey($album_key) {
		$album = AlbumModel::where ( 'album_key', $album_key )->get ()->first ();
		return $album;
	}

	// Lay chi tiết Album
	public static function getAlbumByAlbumId($ps_album_id) {
		$album = AlbumModel::select ( 'alb.id' )->where ( 'id', $ps_album_id )->get ()->first ();
		return $album;
	}

	// Lay danh sach album theo lop
	public static function getListOfAlbums($ps_class_id) {
		$list_albums = AlbumModel::select ( 'id', '.album_key', '.title', '.is_activated', '.note', '.number_like', '.number_dislike' )->where ( 'ps_class_id', '=', $ps_class_id )->orderBy ( 'created_at', 'desc' )->get ();
		return $list_albums;
	}

	// Lay danh sach album cho mot giao vien
	// $member_id - ID Nhân sự, $ps_customer_id - ID trường học
	public static function getListAlbumsOfMember($member_id, $ps_customer_id, $user_id = null) {
		// $tbl = TBL_PS_ALBUMS;
		$list_albums = AlbumModel::select ( 'TC.ps_myclass_id as ps_class_id', 'alb.album_key', 'alb.id', 'alb.album_key', 'alb.title', 'alb.is_activated', 'alb.note', 'alb.number_like', 'alb.number_dislike', 'alb.created_at', 'alb.number_img', 'alb.user_created_id AS user_created_id', 'abi.url_thumbnail AS url_thumbnail' )->leftjoin ( TBL_PS_ALBUM_ITEMS . ' as abi', 'abi.album_id', '=', 'alb.id' )->join ( TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($member_id) {
			$q->on ( 'TC.ps_myclass_id', '=', 'alb.ps_class_id' )->whereRaw ( '(TC.ps_member_id = ' . $member_id . ' OR (alb.ps_class_id IS NULL AND alb.is_activated = 1))' );
		} )->where ( 'alb.ps_customer_id', '=', $ps_customer_id );

		$list_albums = $list_albums->whereRaw ( '(alb.user_created_id = ' . $user_id . ' OR (alb.user_created_id <> ' . $user_id . ' AND alb.is_activated = 1 ) )' );

		$list_albums = $list_albums->groupBy ( 'abi.album_id' );

		$list_albums = $list_albums->orderByRaw ( 'alb.created_at desc', 'abi.created_at desc' )->get ();

		return $list_albums;
	}

	// Lay danh sach album cho mot phu huynh
	public static function getListAlbumsOfRelative($student_id, $ps_customer_id) {
		$tbl = TBL_PS_ALBUMS;

		$list_albums = AlbumModel::select ( 'SC.myclass_id as ps_class_id', $tbl . '.album_key', $tbl . '.id', $tbl . '.album_key', $tbl . '.title', $tbl . '.is_activated', $tbl . '.note', $tbl . '.number_like', $tbl . '.number_dislike', 'abi.url_thumbnail AS url_thumbnail' )->leftJoin ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($student_id, $tbl) {
			$q->on ( 'SC.myclass_id', '=', $tbl . '.ps_class_id' )->whereRaw ( '(SC.student_id = ' . $student_id . ' OR ' . $tbl . '.ps_class_id IS NULL)' );
		} )->leftjoin ( TBL_PS_ALBUM_ITEMS . ' as abi', 'abi.album_id', '=', $tbl . '.id' )->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id )->where ( $tbl . '.is_activated', '=', 1 )->groupBy ( 'abi.album_id' )->orderByRaw ( $tbl . '.created_at desc', 'abi.created_at desc' )->get ();

		return $list_albums;
	}

	/**
	 * ----------- ------------*
	 */

	// Lay danh sach album theo lop
	/**
	 * Danh cho giao vien - Lay các Album cua lop trong do cac Album đã public + Album chưa public của giáo viên tạo
	 *
	 * *
	 */
	public static function getListAlbumsForHR($ps_class_id, $user_id = null) {
		$tbl = TBL_PS_ALBUMS;

		$list_albums = AlbumModel::select ( $tbl . '.id', $tbl . '.album_key', $tbl . '.title', $tbl . '.is_activated', $tbl . '.note', $tbl . '.number_like', $tbl . '.number_dislike', $tbl . '.number_img', $tbl . '.user_created_id', $tbl . '.ps_class_id', 'abi.url_thumbnail AS url_thumbnail' )->leftjoin ( TBL_PS_ALBUM_ITEMS . ' as abi', 'abi.album_id', '=', $tbl . '.id' )->where ( $tbl . '.ps_class_id', '=', $ps_class_id )->whereRaw ( '(' . $tbl . '.is_activated = 0 AND ' . $tbl . '.user_created_id = ' . $user_id . ') OR (' . $tbl . '.is_activated = 1 )' )->groupBy ( 'abi.album_id' )->orderByRaw ( $tbl . '.created_at desc', 'abi.created_at desc' )->get ();

		return $list_albums;
	}

	/**
	 * Lay danh sach album cho cua giao vien
	 * $member_id - ID Nhân sự, $ps_customer_id - ID trường học
	 */
	public static function getListAlbumsOfTeacher($user_id, $member_id, $ps_customer_id) {
		$tbl = TBL_PS_ALBUMS;

		$list_albums = AlbumModel::select ( 'TC.ps_myclass_id as ps_class_id', $tbl . '.album_key', $tbl . '.id', $tbl . '.album_key', $tbl . '.title', $tbl . '.is_activated', $tbl . '.note', $tbl . '.number_like', $tbl . '.number_dislike', $tbl . '.created_at', $tbl . '.number_img', $tbl . '.user_created_id AS user_created_id', 'abi.url_thumbnail AS url_thumbnail' )->leftjoin ( TBL_PS_ALBUM_ITEMS . ' as abi', 'abi.album_id', '=', $tbl . '.id' )->leftJoin ( TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($member_id, $tbl) {
			$q->on ( 'TC.ps_myclass_id', '=', $tbl . '.ps_class_id' )->whereRaw ( '(TC.ps_member_id = ' . $member_id . ' OR (' . $tbl . '.ps_class_id IS NULL AND ' . $tbl . '.is_activated = 1))' );
		} )->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id );

		// ->where($tbl.'.user_created_id', '=', $user_id);

		// $list_albums = $list_albums->whereRaw('('.$tbl.'.user_created_id = '.$user_id.' )');

		$list_albums = $list_albums->groupBy ( 'abi.album_id' );

		$list_albums = $list_albums->orderByRaw ( $tbl . '.created_at desc', 'abi.created_at desc' )->get ();

		return $list_albums;
	}
}