<?php
namespace Api\PsProducts\Controller;

use Exception;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Authentication\PsAuthentication;
use App\Controller\BaseController;
use Api\PsProducts\Model\PsCmsProductsModel;
use Api\PsMembers\Model\PsMemberModel;
use App\PsUtil\PsI18n;
use App\PsUtil\PsString;
use App\PsUtil\PsDateTime;
use Api\Students\Model\StudentModel;
use App\Model\PsMobileAppAmountsModel;
use App\PsUtil\PsWebContent;

class PsCmsProductsController extends BaseController {

	public $container;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app)
	{

		parent::__construct($logger, $container);

		$this->user_token = $app->user_token;
	}

	// Lấy danh sách sản phẩm cho bé
	public function getProducts(Request $request, Response $response, array $args) {
		
		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);
		
		$return_data = array();
		
		$return_data['_msg_code']  = MSG_CODE_TRUE;
		
		$return_data ['_msg_text'] = $psI18n->__ ( 'There are no PsProducts.' );
		
		$return_data ['title'] 	   = $psI18n->__ ( 'News' );
		
		$return_data['_data'] 	   = array();
		
		
		$ds_sanpham = PsCmsProductsModel::where('status','1')->get();
        
        $products = array(); 
		foreach ($ds_sanpham as $key => $ds) {
		    $temp_product = new \stdClass(); 
		    $temp_product->id = $ds->id;
		    $temp_product->title = $ds->title;
		   // $temp_product->mota = $ds->brief;
		    $temp_product->image = PS_CONST_URL_SERVER.$ds->image;
		    array_push($products, $temp_product);
		}

		$return_data['_data']['ds_sanpham'] = $products;

		
		return $response->withJson ( $return_data );
	}


	
	// Chi tiet san pham
	public function getProduct(Request $request, Response $response, array $args) {
		
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
			
			$queryParams = $request->getQueryParams();
			
			$product_id = isset ( $queryParams ['id'] ) ? $queryParams ['id'] : null;
			
			$product= PsCmsProductsModel::where('id',$product_id)->first();

			//return $product."aaa";
			
			if (isset ( $product ) && $product) {
				
				$web_view = PsWebContent::BeginHTMLPageBootstrap();				
				$web_view .= '<div class="container-fluid">';
				
					$web_view .= '<div class="article">';
					
					
					
					$web_view .= '<div style="padding-top:7px;"><h4>'.PsString::htmlSpecialChars($product->title).'</h4></div>';
					$web_view .= '<div class="ks-text-date ks-small" >'. $psI18n->__ ('Date post').': <span style="text-decoration: none;color:#ccc;">'.PsDateTime::toDMY($product->create_date,"H:i d/m/Y").'</span></div>';
					$web_view .= '<div class="ks-padding-7">'.$product->content.'</div>';
					
					$web_view .= '</div>';
					
				$web_view .= '</div>';
				
				$web_view .= PsWebContent::EndHTMLPage();
				
				$_article = new \stdClass();
				
				$_article->content = $web_view;
				 
				$return_data ['_data'] ['title'] =  $product->title; 

				$return_data ['_data'] ['image'] =  PS_CONST_URL_SERVER.$product->image; 

				$return_data ['_data'] ['date_created'] =  PsDateTime::toDMY($product->created_at,"H:i d/m/Y"); 

				$return_data ['_data'] ['link'] =  $product->link; 

				//$return_data ['_data'] ['content'] =  $product->content;

				$return_data ['_data'] ['content'] = $_article; 
				
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