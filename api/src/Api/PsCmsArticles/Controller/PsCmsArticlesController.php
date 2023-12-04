<?php
namespace Api\PsCmsArticles\Controller;

use Exception;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Authentication\PsAuthentication;
use App\Controller\BaseController;
use Api\PsCmsArticles\Model\PsCmsArticlesModel;
use Api\PsMembers\Model\PsMemberModel;
use App\PsUtil\PsI18n;
use App\PsUtil\PsString;
use App\PsUtil\PsDateTime;
use Api\Students\Model\StudentModel;
use App\Model\PsMobileAppAmountsModel;
use App\PsUtil\PsWebContent;

class PsCmsArticlesController extends BaseController {

	public $container;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app) {

		parent::__construct ( $logger, $container );
		
		$this->user_token = $app->user_token;
		
	}
	
	// Lấy danh sách tin tức
	public function getArticles(Request $request, Response $response, array $args) {
		
		$user = $this->user_token;
		
		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );
		
		$return_data = array();
		
		$return_data['_msg_code']  = MSG_CODE_TRUE;
		
		$return_data ['_msg_text'] = $psI18n->__ ( 'There are no posts.' );
		
		$return_data ['title'] 	   = $psI18n->__ ( 'News' );
		
		$return_data['_data'] 	   = array();
		
		$tabs = array();
		
		array_push($tabs, array('name' => $psI18n->__ ('All'), 'type' => PS_ARTICLE_ALL));
		
		array_push($tabs, array('name' => $psI18n->__ ('School'), 'type' => PS_ARTICLE_SCHOOL));
		
		array_push($tabs, array('name' => 'KidsSchool', 'type' 	=> PS_ARTICLE_GLOBAL));// Tin tuc cua KidsSchool phat hanh ra toan he thong
		
		if ($user->ps_customer_id == 6) {
			
			for ($i= 1; $i <= 20; $i++)
			array_push($tabs, array('name' => $psI18n->__ ('Danh mục '.$i), 'type' => 'cat_id_'.$i));			
		}
		
		$return_data ['_data'] ['tabs'] 		= $tabs;
		
		$device_id = $request->getHeaderLine ( 'deviceid' );
		
		$type = (isset ( $args ['type'] ) && $args ['type'] != '') ? ( string ) $args ['type'] : PS_ARTICLE_ALL;
		
		if ($type == '-')
			$type = PS_ARTICLE_ALL;
		
		//$type = (isset ( $args ['type'] ) && $args ['type'] != '') ? ( string ) $args ['type'] : PS_ARTICLE_GLOBAL;	
		
		$this->WriteLog('HUHU type: '.$type);
		
		$page = isset ( $args ['page'] ) ? ( int ) $args ['page'] : 1;
		
		if ($page < 1)
			$page = 1;
		
		$queryParams = $request->getQueryParams ();
		
		$return_data ['_data'] ['tab_active'] 		= $type;
		
		$student_id = isset ( $queryParams ['student_id'] ) ? $queryParams ['student_id'] : null;
		//return $response->withJson ( $queryParams );
		if ($student_id > 0 && $user->user_type == USER_TYPE_RELATIVE) { // APP phụ huynh
			//return 'AAAAAAAAAAAAA';
			
			if (! PsAuthentication::checkDevice ( $user, $device_id )) {
				//return 'AAAAAAAAAAAAA';
				return $response->withJson ( $return_data );
			}
			
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );
			
			if (! $amount_info) {
				
				$return_data = array (
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' ),
						'_msg_text' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' ) 
				);
				
				return $response->withJson ( $return_data );
			}
			
			// Lay thong tin hoc sinh và quan he nguoi than
			//$student = StudentModel::getStudentUserByIdAndMemberId ( $student_id, $user->member_id );			
			$student = StudentModel::getStudentForRelative($student_id, $user->member_id);
			
			if ($student) {
				
				$ps_workplace_id = $student->ps_workplace_id;
				
				if ($type == PS_ARTICLE_ALL) {
					// Lay tin cua truong + co so + KidsSchool public toan he thong
					$articles = PsCmsArticlesModel::getArticlesOfKidsSchool($user->ps_customer_id, $ps_workplace_id, STATUS_ACTIVE);
				} else {
					
					if ($type == PS_ARTICLE_SCHOOL) {// Tin tức của nhà trường: Trường + Cơ sở
						// Lay tin tức của trường + cơ sở
						$articles = PsCmsArticlesModel::getArticlesStudentOfSchool($user->ps_customer_id, $ps_workplace_id);
						
					} elseif ($type == PS_ARTICLE_GLOBAL) { // Lay tin tuc của toàn hệ thống mà phụ huynh được xem
						$articles = PsCmsArticlesModel::getArticlesGlobal(1);
					}
				}			
			} else {
				$return_data ['_msg_text'] = $psI18n->__ ( 'You do not have access to this data' );
			}
		
		} elseif ($user->user_type == USER_TYPE_TEACHER) { // APP Giao vien
			
			// Các tin tức mà GV được xem trên toàn hệ thống
			if ($type == PS_ARTICLE_GLOBAL) {
				
				$articles = PsCmsArticlesModel::getArticlesGlobal();				
			
			} else {
				
				// Tìm cơ sở của giáo viên
				$ps_member = PsMemberModel::getMember ( $user->member_id );
				$ps_workplace_id = null;
				
				if ($ps_member) {
					// Uu tien co so dang thuộc, con khong lay co so ban dau vào trường
					$ps_workplace_id = ($ps_member->ps_workplace_id > 0) ? $ps_member->ps_workplace_id: $ps_member->member_workplace_id;
				}
				
				if ($type == PS_ARTICLE_SCHOOL) {// Tin tức của nhà trường: Trường + Cơ sở
					// Lay tin tức của trường + cơ sở
					$articles = PsCmsArticlesModel::getArticlesMemberOfSchool($user->ps_customer_id, $ps_workplace_id);
					
				} elseif ($type == PS_ARTICLE_ALL) { // Lay tin tuc cua truong + co so + toàn hẹ thong
					$articles = PsCmsArticlesModel::getAllArticlesForMember($user->ps_customer_id, $ps_workplace_id);
				}
			}
				
		} else {
			
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message']   = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
			$return_data ['_msg_text'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}
		
		if (isset ( $articles )) {
			
			$number_records = $articles->get()->count ();
			
			$limit = PS_CONST_LIMIT_ARTICLE;
			
			if ($number_records % $limit == 0) {
				$number_pages = $number_records / $limit;
			} else {
				$number_pages = ( int ) ($number_records / $limit) + 1;
			}
			
			if ($page > $number_pages) {
				$page = 1;
			}
			
			$next_page = ($page + 1);
			
			$pre_page  = ($page - 1);
			
			if (($number_pages == 1) || ($page == 1)) {
				$pre_page = 0;
			}
			
			if (($number_pages == 1) || ($page == $number_pages)) {
				$next_page = 0;
			}
			
			$articles = $articles->forPage ( $page, $limit )->orderBy ( 'created_at', 'desc' )->get ();
			
			$arr_article = array ();
			
			foreach ( $articles as $article ) {
				
				if ($article->url_image == '') {
					
					$article->url_image = PS_CONST_API_URL_IMAGE_NO;
				} else {
					
					$article->url_image = PsString::getUrlMediaThumbArticle ( $article->ps_customer_id, date ( "Y/m/d", strtotime ( $article->create_date ) ), $article->url_image );
				}
				
				$article->create_date = PsDateTime::toDMY ( $article->create_date, 'd-m-Y' );
				
				array_push ( $arr_article, $article );
			}
			
			$return_data ['_data'] ['next_page'] 	= $next_page;
			
			$return_data ['_data'] ['pre_page'] 	= $pre_page;
			
			$return_data ['_data'] ['data_info'] 	= $arr_article;
			
			$return_data ['_msg_code'] = MSG_CODE_TRUE;
			
			$return_data ['_msg_text'] = ($number_records > 0) ? 'OK' : $psI18n->__ ( 'There are no posts.' );
		}
		
		return $response->withJson ( $return_data );
	}
	
	// Chi tiet bai viet
	public function getArticle(Request $request, Response $response, array $args) {
		
		$user = $this->user_token;
		$device_id = $request->getHeaderLine ( 'deviceid' );
		
		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );
		
		$return_data = array();
		$return_data ['_msg_code']  = MSG_CODE_FALSE;
		$return_data ['_data'] 		= [];
		
		// Set style for view HTML
		$user_app_config = json_decode($user->app_config);
		$app_config_color = (isset($user_app_config->style) && $user_app_config->style != '') ? $user_app_config->style : 'green';
		
		$id = $args ['id'];
		
		try {
			
			$queryParams = $request->getQueryParams ();
			
			$student_id = isset ( $queryParams ['student_id'] ) ? $queryParams ['student_id'] : null;
			
			if ($student_id > 0 && $user->user_type == USER_TYPE_RELATIVE) {
				
				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}
				
				// Lay thong tin hoc sinh và quan he nguoi than
				//$student = StudentModel::getStudentUserByIdAndMemberId ( $student_id, $user->member_id );
				
				$student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
				
				if ($student) {					
					$article = PsCmsArticlesModel::getArticleByWorkplaceId ( $id, $user->ps_customer_id, $student->ps_workplace_id, STATUS_ACTIVE );
				} else {
					$return_data ['_msg_text'] = $psI18n->__ ( 'You do not have access to this data' );
				}
			} elseif ($user->user_type == USER_TYPE_TEACHER) {
				
				// Tìm cơ sở của giáo viên
				$ps_member = PsMemberModel::getMember ( $user->member_id );
				$ps_workplace_id = null;				
				if ($ps_member) {
					// Uu tien co so dang thuộc, con khong lay co so ban dau vào trường
					$ps_workplace_id = ($ps_member->ps_workplace_id > 0) ? $ps_member->ps_workplace_id: $ps_member->member_workplace_id;
				}
				
				$article = PsCmsArticlesModel::getArticle ( $id, $user->ps_customer_id, $ps_workplace_id );
			
			}
			
			if (isset ( $article ) && $article) {
				
				$web_view = PsWebContent::BeginHTMLPageBootstrap();				
				$web_view .= '<div class="container-fluid">';
				
					$web_view .= '<div class="article">';
					
					// if ($article->url_image != '') {
					// 	$web_view .= '<div class="ks-padding-5"><img alt="" style="width: 100%;padding-bottom:7px" src="'.PsString::getUrlMediaArticle ( $article->ps_customer_id, date ( "Y/m/d", strtotime ( $article->create_date ) ), $article->url_image ).'" /></div>';				
					// }
					
					$web_view .= '<div style="padding-top:7px;"><h4>'.PsString::htmlSpecialChars($article->title).'</h4></div>';
					$web_view .= '<div class="ks-text-date ks-small" >'. $psI18n->__ ('Date post').': <span style="text-decoration: none;color:#ccc;">'.PsDateTime::toDMY($article->create_date,"H:i d/m/Y").'</span></div>';
					$web_view .= '<div class="ks-padding-7">'.$article->content.'</div>';
					
					$web_view .= '</div>';
					
				$web_view .= '</div>';
				
				$web_view .= PsWebContent::EndHTMLPage();
				
				$_article = new \stdClass();
				
				$_article->content = $web_view;
				
				$return_data ['_data'] ['data_info'] = $_article;
				
				$return_data ['_msg_code'] 			 = MSG_CODE_TRUE;
				
			} else {
				
				//$return_data ['_msg_text'] = $psI18n->__ ( 'You do not have access to this data' );
				
				$web_view = PsWebContent::BeginHTMLPageBootstrap();
				$web_view .= '<div class="container-fluid">';
				
				$web_view .= '<div class="article">';
					
				$web_view .= '<div class="ks-padding-7">'.$psI18n->__ ( 'This content is no longer available.' ).'</div>';
					
				$web_view .= '</div>';
				
				$web_view .= PsWebContent::EndHTMLPage();
				
				$_article = new \stdClass();
				
				$_article->content = $web_view;
				
				$return_data ['_data'] ['data_info'] = $_article;
				
				$return_data ['_msg_code'] 			 = MSG_CODE_TRUE;
				
			}
			
		} catch ( Exception $e ) {
			
			$this->logger->err ( $e->getMessage () );
			
			$return_data ['_msg_code'] = MSG_CODE_500;
			
			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}
		
		return $response->withJson ( $return_data );
	}
}