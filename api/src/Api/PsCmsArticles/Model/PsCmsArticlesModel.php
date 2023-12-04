<?php
namespace Api\PsCmsArticles\Model;

// use Illuminate\Database\Eloquent\Model;
use App\Model\BaseModel;

class PsCmsArticlesModel extends BaseModel {

	protected $table = TBL_PS_CMS_ARTICLES;

	public function customer() {

		return $this->belongsTo ( 'Api\PsCustomer\Model\PsCustomerModel', 'ps_customer_id', 'id' );
	}
	
	/**
	 * Lấy tin tức được đăng cho toàn hệ thống xem. Giáo viên hay Phụ huynh phụ thuộc $is_access
	 *
	 * @author Nguyen Chien Thang
	 *
	 * @param $is_access - vùng hiển thị: 0 - Nội bộ Giáo viên trong toàn hệ thống được xem(APP giáo viên); 1 : Giáo viên được xem + Phụ huynh được xem (APP phụ huynh)
	 * @param $is_global - Tin do KidsSchool đăng cho toàn hệ thống xem
	 * @return String SQL
	 **/
	public function getArticlesGlobal($is_access = null) {
		
		$articles = PsCmsArticlesModel::select ( 'id', 'title', 'note', 'file_name AS url_image', 'created_at AS create_date' );
		
		$articles->where ( 'is_publish', STATUS_ACTIVE );
		$articles->where ( 'is_global', STATUS_ACTIVE );
		
		if ($is_access !== null)
			$articles->where ( 'is_access', (int)$is_access );
			
			return $articles;
	}
	
	/**
	 * Lấy tin tức được đăng cho toàn hệ thống xem. Giáo viên hay Phụ huynh phụ thuộc $is_access
	 * 
	 * @author Nguyen Chien Thang
	 * 
	 * @param $is_access - vùng hiển thị: 0 - Nội bộ Giáo viên trong toàn hệ thống được xem(APP giáo viên); 1 : Giáo viên được xem + Phụ huynh được xem (APP phụ huynh)
	 * @param $is_global - Tin do KidsSchool đăng cho toàn hệ thống xem
	 * @return String SQL
	**/
	public function getArticlesOfKidsSchool($ps_customer_id, $ps_workplace_id, $is_access = null) {
		
		$articles = PsCmsArticlesModel::select ( 'id', 'title', 'note', 'file_name AS url_image', 'created_at AS create_date' );
		
		$articles->where ( 'is_publish', STATUS_ACTIVE );
		
		if ($is_access !== null) {
			$articles->where ( 'is_access', (int)$is_access );
		} 
		
		/*else {
			$articles->where ( 'is_global', STATUS_ACTIVE );
		}
		*/
		
		//$articles->where ( 'is_global', STATUS_ACTIVE );
		
		$articles->whereRaw ( '((is_global = '.STATUS_ACTIVE.' ) OR  ( (ps_customer_id = '.(int)$ps_customer_id.' AND ps_workplace_id IS NULL) OR  (ps_customer_id = '.(int)$ps_customer_id.' AND ps_workplace_id = '.(int)$ps_workplace_id.') ))');
		
		return $articles;
	}
	
	/**
	 * Lấy tin tức được đăng của trường cho giáo viên
	 * vùng hiển thị: 0 - Nội bộ Giáo viên trong toàn hệ thống được xem(APP giáo viên); 1 : Giáo viên được xem + Phụ huynh được xem (APP phụ huynh)
	 *
	 * @param $ps_customer_id
	 * @param $ps_workplace_id - Tin do KidsSchool đăng cho toàn hệ thống xem
	 * @return String SQL
	 **/
	public function getArticlesMemberOfSchool($ps_customer_id, $ps_workplace_id) {
		
		$articles = PsCmsArticlesModel::select ( 'id', 'title', 'note', 'file_name AS url_image', 'created_at AS create_date' );
		
		$articles->where ( 'is_publish', STATUS_ACTIVE );
		
		$articles->whereRaw ( '( (ps_customer_id = '.(int)$ps_customer_id.' AND ps_workplace_id IS NULL) OR  (ps_customer_id = '.(int)$ps_customer_id.' AND ps_workplace_id = '.(int)$ps_workplace_id.') )');		
		
		return $articles;
	}
	
	/**
	 * Lấy tất cả tin tức cho giáo viên
	 *
	 * @param $ps_customer_id
	 * @param $ps_workplace_id
	 * @return String SQL
	**/
	public function getAllArticlesForMember($ps_customer_id, $ps_workplace_id) {
		
		$articles = PsCmsArticlesModel::select ( 'id', 'title', 'note', 'file_name AS url_image', 'created_at AS create_date' );
		
		$articles->where ( 'is_publish', STATUS_ACTIVE );
		
		$articles->whereRaw ( '( (is_global = '.STATUS_ACTIVE.') OR ((ps_customer_id = '.(int)$ps_customer_id.' AND ps_workplace_id IS NULL) OR  (ps_customer_id = '.(int)$ps_customer_id.' AND ps_workplace_id = '.(int)$ps_workplace_id.') ))');
		
		return $articles;
	}
	
	
	/**
	 * Lấy tin tức được đăng của trường cho phụ huynh xem
	 * vùng hiển thị: 1 : Giáo viên được xem + Phụ huynh được xem (APP phụ huynh)
	 *
	 * @param $ps_customer_id
	 * @param $ps_workplace_id - Tin do KidsSchool đăng cho toàn hệ thống xem
	 * @return String SQL
	 **/
	public function getArticlesStudentOfSchool($ps_customer_id, $ps_workplace_id) {
		
		$articles = PsCmsArticlesModel::select ( 'id', 'title', 'note', 'file_name AS url_image', 'created_at AS create_date' );
		
		$articles->where ( 'is_publish', STATUS_ACTIVE );
		$articles->where ( 'is_access', STATUS_ACTIVE );
		$articles->where ( 'is_global', STATUS_NOT_ACTIVE );
		
		$articles->whereRaw ( '( (ps_customer_id = '.(int)$ps_customer_id.' AND ps_workplace_id IS NULL) OR  (ps_customer_id = '.(int)$ps_customer_id.' AND ps_workplace_id = '.(int)$ps_workplace_id.') )');
		
		return $articles;
	}
	
	/**
	 * @return string SQL
	**/
	public function getAllArticles($ps_customer_id, $ps_workplace_id = null, $is_access = null, $is_global = null) {

		$articles = PsCmsArticlesModel::select ( 'id', 'title', 'note', 'file_name AS url_image', 'created_at AS create_date' );
		
		$articles->where ( 'is_publish', STATUS_ACTIVE );
		
		if ($is_global > 0) {
			
			$articles->where ( 'is_global', STATUS_ACTIVE );
		
		} else {
			
			$articles->where ( 'ps_customer_id', $ps_customer_id );
			
			if ($ps_workplace_id > 0) {
				$articles->where ( 'ps_workplace_id', $ps_workplace_id );
			}
			
			if ($is_access >= 0 && $is_access != null) {
				$articles->where ( 'is_access', STATUS_ACTIVE );
			}
		}
		
		return $articles;
		
	}

	/**
	 * Chi tiet bai viet da duoc public - danh cho giáo viên
	 *
	 * @param $id - int, ID bai viet
	 * @param $ps_customer_id int, ID trường
	 * @param $ps_workplace_id int, ID cơ sở dào tạo
	 * 
	 * @return $obj
	 */
	public static function getArticle($id, $ps_customer_id, $ps_workplace_id = null) {

		$article = PsCmsArticlesModel::select ( 'id AS id', 'ps_customer_id AS ps_customer_id', 'title', 'description AS content', 'file_name AS url_image', 'created_at AS create_date' )
		->where ( 'id', '=', $id )
		->where ( 'is_publish', '=', STATUS_ACTIVE )
		->where ( 'is_global', STATUS_ACTIVE )
		->orWhere ( function ($q) use ($id, $ps_customer_id, $ps_workplace_id) {
			
			$q->where ( 'id', $id );
			
			$q->where ( 'is_publish', STATUS_ACTIVE );
			
			if ($ps_customer_id > 0) {
				$q->where ( 'ps_customer_id', $ps_customer_id );
			}
			
			if ($ps_workplace_id > 0) {
				$q->whereRaw ( '(ps_workplace_id IS NULL OR ps_workplace_id = ?)', $ps_workplace_id );
			}
			
			return $q;
		} );
		
		return $article->get ()->first ();
	}

	/**
	 * Chi tiet bai viet da duoc public danh cho app phụ huynh
	 *
	 * @param $ind -
	 *        	int
	 * @param $ps_customer_id -
	 *        	int
	 * @param $ps_workplace_id -
	 *        	int
	 * @param
	 *        	$is_access
	 *        	
	 * @return $obj
	 *
	 */
	public static function getArticleByWorkplaceId($id, $ps_customer_id, $ps_workplace_id = null, $is_access = null) {

		$article = PsCmsArticlesModel::select ( 'id AS id', 'ps_customer_id AS ps_customer_id', 'title', 'description AS content', 'file_name AS url_image', 'created_at AS create_date' )->where ( 'id', '=', $id )->where ( 'is_publish', '=', STATUS_ACTIVE )->

		where ( 'is_global', STATUS_ACTIVE )->

		orWhere ( function ($q) use ($id, $ps_customer_id, $ps_workplace_id, $is_access) {
			
			$q->where ( 'id', $id );
			
			$q->where ( 'is_publish', STATUS_ACTIVE );
			
			if ($ps_customer_id > 0) {
				$q->where ( 'ps_customer_id', $ps_customer_id );
			}
			
			if ($ps_workplace_id > 0) {
				$q->whereRaw ( '(ps_workplace_id IS NULL OR ps_workplace_id = ?)', $ps_workplace_id );
			}
			
			if ($is_access >= 0 && $is_access != null) {
				$q->where ( 'is_access', $is_access);
		    }
		    
		    return $q;
		});
		
		return $article->get ()->first ();
	}
}