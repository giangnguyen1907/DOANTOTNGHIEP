<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/25/2018
 * Time: 3:13 PM
 */
namespace Api\PsAdvices\Model;

use App\Model\BaseModel;

class AdviceModel extends BaseModel
{
    protected $table = TBL_PS_ADVICES;
    
    /**
     * Ham tra ve chi tiet dan do cho giao vien
    **/
    public static function detailForTeacher($id, $user_id) {
        
    	$tbl = TBL_PS_ADVICES;
    	
    	$ps_advice = AdviceModel::select ( $tbl.'.id as id', $tbl.'.title as title', $tbl.'.content as content', $tbl.'.is_activated as is_activated', 'AC.title as category_name', $tbl.'.date_at as date_at_send','MC.name as class_name', 'U.id as user_id', 'M.avatar as avatar', 'M.year_data as year_data', 'C.cache_data as cache_data', 'S.birthday AS birthday','S.year_data AS s_year_data', 'S.avatar AS s_avatar', 'RE.title AS re_title')
    	->selectRaw ( 'CONCAT(U.first_name," ", U.last_name) AS teacher_fullname' )
    	->selectRaw ( 'CONCAT(S.first_name," ", S.last_name) AS student_fullname')
    	->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS relative_fullname')    	
    	->selectRaw ( 'AF.content as af_content' )
    	->join ( TBL_PS_ADVICE_CATEGORIES . ' as AC', 'AC.id', '=', $tbl.'.category_id' )
    	->join ( CONST_TBL_STUDENT . ' as S', 'S.id', '=', $tbl.'.student_id' )
    	->join ( CONST_TBL_USER . ' as U', $tbl.'.user_id', '=', 'U.id' )// Giao vien nhan
    	->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id' )    	
    	->join ( CONST_TBL_PS_MEMBER . ' as M', function ($q) {
    		$q->on ( 'M.id', '=', 'U.member_id' )->where( 'U.user_type', USER_TYPE_TEACHER);
    	} )
    	
    	->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use($tbl){
    		$q->on ( 'SC.student_id', '=', 'S.id' )
    		->whereRaw('(DATE_FORMAT(SC.created_at,"%Y%m%d") <= DATE_FORMAT('.$tbl.'.created_at,"%Y%m%d") AND  (DATE_FORMAT(SC.stop_at,"%Y%m%d") >= DATE_FORMAT('.$tbl.'.created_at,"%Y%m%d") OR SC.stop_at IS NULL) )');
    	})
    	->join ( CONST_TBL_MYCLASS . ' as MC', 'SC.myclass_id', '=', 'MC.id' )
    	->join ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', $tbl.'.relative_id' )
    	->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', function ($q) use ($tbl) {
    		$q->on ( 'RS.student_id', '=', 'S.id' )->whereRaw ( 'RS.student_id = '.$tbl.'.student_id');
    	} )
    	->leftjoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
    	->leftjoin ( TBL_PS_ADVICE_FEEDBACKS . ' as AF', 'AF.advice_id', '=', $tbl.'.id' )// Phan hoi
    	->where ( $tbl.'.id',$id )
    	//->where ( 'U.id',$user_id)
    	->get()->first ();
    	
    	return $ps_advice;
    }
    
    /**
     * Ham tra ve chi tiet dan do cho nguoi than
    **/
    public static function detailForRelative($id) {
        
        $tbl = TBL_PS_ADVICES;
        
        $ps_advice = AdviceModel::select ( $tbl.'.id as id', $tbl.'.title as title', $tbl.'.content as content', $tbl.'.is_activated as is_activated', 'AC.title as category_name', $tbl.'.date_at as date_at_send','MC.name as class_name', 'U.id as user_id', 'M.avatar as avatar', 'M.year_data as year_data', 'C.cache_data as cache_data', 'S.birthday AS birthday','S.year_data AS s_year_data', 'S.avatar AS s_avatar', 'RE.title AS re_title')
        ->selectRaw ( 'CONCAT(U.first_name," ", U.last_name) AS teacher_fullname' )
        ->selectRaw ( 'CONCAT(S.first_name," ", S.last_name) AS student_fullname')
        ->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS relative_fullname')
        
        ->selectRaw ( 'AF.content as af_content' )
        
        ->join ( CONST_TBL_USER . ' as U', $tbl.'.user_id', '=', 'U.id' )// Giao vien nhan
        ->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id' )
        
        ->join ( CONST_TBL_PS_MEMBER . ' as M', function ($q) {
            $q->on ( 'M.id', '=', 'U.member_id' )->where( 'U.user_type', USER_TYPE_TEACHER);
        } )
        
        ->join ( TBL_PS_ADVICE_CATEGORIES . ' as AC', 'AC.id', '=', $tbl.'.category_id' )
        ->join ( CONST_TBL_STUDENT . ' as S', 'S.id', '=', $tbl.'.student_id' )        
        ->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use($tbl){
            $q->on ( 'SC.student_id', '=', 'S.id' )
            ->whereRaw('(DATE_FORMAT(SC.created_at,"%Y%m%d") <= DATE_FORMAT('.$tbl.'.created_at,"%Y%m%d") AND  (DATE_FORMAT(SC.stop_at,"%Y%m%d") >= DATE_FORMAT('.$tbl.'.created_at,"%Y%m%d") OR SC.stop_at IS NULL) )');
        })
        ->join ( CONST_TBL_MYCLASS . ' as MC', 'SC.myclass_id', '=', 'MC.id' )        
        ->join ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', $tbl.'.relative_id' )        
        ->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', function ($q) use ($tbl) {
            $q->on ( 'RS.student_id', '=', 'S.id' )->whereRaw ( 'RS.student_id = '.$tbl.'.student_id');
        } )        
        ->leftjoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )        
        ->leftjoin ( TBL_PS_ADVICE_FEEDBACKS . ' as AF', 'AF.advice_id', '=', $tbl.'.id' )// Phan hoi        
        ->where ( $tbl.'.id',$id )
        ->get()->first ();
        
        return $ps_advice;
    }
}