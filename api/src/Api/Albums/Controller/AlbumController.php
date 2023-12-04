<?php

namespace Api\Albums\Controller;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Respect\Validation\Validator as vali;
use App\Controller\BaseController;
use App\Authentication\PsAuthentication;
use Api\PsMembers\Model\PsMemberModel;
use App\Model\PsMobileAppAmountsModel;
use Api\Albums\Model\AlbumModel;
use Api\Albums\Model\AlbumCommentModel;
use Api\Albums\Model\AlbumLikeModel;
use Api\Albums\Model\AlbumItemModel;
use Api\Students\Model\StudentModel;
use Api\Users\Model\UserModel;
use App\PsUtil\PsString;
use App\PsUtil\PsI18n;
use App\PsUtil\PsNotification;
use App\PsUtil\PsEndCode;
use App\PsUtil\PsWebContent;
use App\PsUtil\PsDateTime;
use App\Model\PsWorkPlacesModel;



class AlbumController extends BaseController
{

	public $container;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app)
	{
		parent::__construct($logger, $container);

		$this->user_token = $app->user_token;
	}

	// Lay danh sach album - Da viet lai
	public function listAlbums(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array(
			'title' => "Albums",
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => '',
			'_data' => []
		);

		/**
		 * $_arr_img_activated = array(0,1);
		 *
		 * $album_detail = AlbumModel::where ( 'id', 1 )->where('ps_customer_id',1)->whereIn ( 'is_activated', $_arr_img_activated )->get ()->first ();
		 *
		 * echo 'count: '.count($album_detail);
		 * die;
		 * return $response->withJson ( $album_detail );
		 **/

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		// get device_id app
		$device_id = $request->getHeaderLine('deviceid');

		$data_list_albums = array();

		if ($user->user_type == USER_TYPE_TEACHER) {

			$list_albums = AlbumModel::getListAlbumsOfMember($user->member_id, $user->ps_customer_id, $user->id);

			foreach ($list_albums as $album) {

				$temp_album = new \stdClass();

				$temp_album->id 			= (int) $album->id;
				$temp_album->album_key 		= (string) $album->album_key;
				$temp_album->ps_class_id 	= $album->ps_class_id ? (int) $album->ps_class_id : null;
				$temp_album->is_activated 	= (int) $album->is_activated;
				$temp_album->title 			= (string) $album->title;
				$temp_album->note 			= (string) $album->note;
				$temp_album->number_like 	= (int) $album->number_like;
				$temp_album->number_dislike = (int) $album->number_dislike;

				// Duong dan den thu muc anh cua Album
				$temp_album->url_album = md5($user->ps_customer_id) . '/' . date("Ym", strtotime($album->created_at)) . '/' . date("d", strtotime($album->created_at)) . '/' . $album->album_key;

				// Lay so luong anh trong album
				$temp_album->count = (int) $album->number_img;

				$temp_album->url_file_represent = ($album->url_thumbnail != '') ? $album->url_thumbnail : PS_CONST_API_URL_ALBUM_IMAGE_NO;

				if ($temp_album->is_activated == STATUS_LOCK) { // Bị khóa
					$temp_album->role = 0;
				} else {
					$temp_album->role = ($album->user_created_id == $user->id) ? 1 : 0;
				}

				if ($temp_album->is_activated == STATUS_ACTIVE) {

					$temp_album->title_content = (string) $album->title . '<br><b style="font-size: 12px;">' . $album->class_name . '</b>, <span style="font-style: italic;color:#ff0000;font-size: 12px;">' . $album->number_img . '</span> <span style="font-style: italic;font-size: 12px">' . $psI18n->__('photos') . '</span>, <span style="font-style: italic;font-size: 12px">' . $psI18n->__('Views') . ': ' . $album->number_view . '</span>';
				} elseif ($temp_album->is_activated == STATUS_NOT_ACTIVE) {

					$temp_album->url_file_represent = PS_CONST_API_URL_ALBUM_NOT_PUBLIC;

					$temp_album->title_content = (string) $album->title . '<br><b style="font-size: 12px;">' . $album->class_name . '</b>, <span style="font-style: italic;color:#ff0000;font-size: 12px;">' . $album->number_img . '</span> <span style="font-style: italic;font-size: 12px">' . $psI18n->__('photos') . '</span>, <span style="font-style: italic;font-size: 12px">' . $psI18n->__('Views') . ': ' . $album->number_view . '</span>';
				} elseif ($temp_album->is_activated == STATUS_LOCK) {

					$temp_album->url_file_represent = PS_CONST_API_URL_ALBUM_LOCK;

					$temp_album->title_content = (string) $album->title . '<br><b style="font-size: 12px;">' . $album->class_name . '</b>, <span style="font-style: italic;color:#ff0000;font-size: 12px;">' . $album->number_img . '</span> <span style="font-style: italic;font-size: 12px">' . $psI18n->__('photos') . '</span>, <span style="font-style: italic;font-size: 12px">' . $psI18n->__('Views') . ': ' . $album->number_view . '</span>';
				}

				$temp_album->title_content = PsWebContent::styleContentTextListAlbum($temp_album->title_content);

				$temp_album->user_created_id = (int) $album->user_created_id;


				array_push($data_list_albums, $temp_album);
			}

			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => (count($data_list_albums) <= 0) ? $psI18n->__('No albums available.') : '',
				'title' => $psI18n->__('Albums'),
				'ps_customer_id' => md5($user->ps_customer_id),
				'_data' => $data_list_albums
			);
		} elseif ($user->user_type == USER_TYPE_RELATIVE) {

			if (!PsAuthentication::checkDevice($user, $device_id)) {
				return $response->withJson($return_data);
			}

			// Check tien trong tai khoan
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

			if (!$amount_info) {

				$return_data = array(
					'_msg_code' => MSG_CODE_PAYMENT,
					'_msg_text' => $psI18n->__('Your account has run out of money. Please recharge to continue using.'),
					'message' 	=> $psI18n->__('Your account has run out of money. Please recharge to continue using.'),
					'title' 	=> $psI18n->__('Albums')
				);

				return $response->withJson($return_data);
			}

			// Get id student
			$student_id = $args['student_id'];

			// Check thong tin nguoi than va hoc sinh
			$ps_student = StudentModel::getStudentForRelative($student_id, $user->member_id);

			if (!$ps_student) {
				$return_data = array(
					'title' => $psI18n->__('Albums'),
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('You do not have access to this data'),
					'_data' => []
				);
				return $response->withJson($return_data);
			}

			$list_albums = AlbumModel::getListAlbumsOfRelative($student_id, $user->ps_customer_id);

			foreach ($list_albums as $album) {

				$temp_album = new \stdClass();
				$temp_album->id = (int) $album->id;
				$temp_album->album_key = (string) $album->album_key;
				$temp_album->ps_class_id = $album->ps_class_id ? (int) $album->ps_class_id : null;
				$temp_album->is_activated = (int) $album->is_activated;
				$temp_album->title = (string) $album->title;
				$temp_album->note = (string) $album->note;
				$temp_album->number_like = (int) $album->number_like;
				$temp_album->number_dislike = (int) $album->number_dislike;

				// Duong dan den thu muc anh cua Album
				$temp_album->url_album = md5($user->ps_customer_id) . '/' . date("Ym", strtotime($album->created_at)) . '/' . date("d", strtotime($album->created_at)) . '/' . $album->album_key;

				$temp_album->url_file_represent = ($album->url_thumbnail != '') ? $album->url_thumbnail : PS_CONST_API_URL_ALBUM_IMAGE_NO;

				// Lay so luong anh trong album
				$temp_album->count = (int) $album->number_img;

				$temp_album->role = STATUS_NOT_ACTIVE; // Phụ huynh không có quyền thao tác: Sửa/Xóa

				$title_content = (string) $album->title . '<br><b style="font-size: 12px;">' . $album->class_name . '</b>, <span style="font-style: italic;color:#ff0000;font-size: 12px;">' . $album->number_img . '</span> <span style="font-style: italic;font-size: 12px">' . $psI18n->__('photos') . '</span>, <span style="font-style: italic;font-size: 12px">' . $psI18n->__('Views') . ': ' . $album->number_view . '</span>';

				$temp_album->title_content = PsWebContent::styleContentTextListAlbum($title_content);

				array_push($data_list_albums, $temp_album);
			}

			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => (count($data_list_albums) <= 0) ? $psI18n->__('No albums available.') : '',
				'title' => $psI18n->__('Albums'),
				'ps_customer_id' => md5($user->ps_customer_id),
				'_data' => $data_list_albums
			);
		}

		return $response->withJson($return_data);
	}

	// Hien thi chi tiet mot album - Da viet lai
	public function showAlbumDetail(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('This content is no longer available.'),
			'title' => $psI18n->__('Albums'),
			'data_info' => []
		);

		$ps_album_id = $args['album_id'];

		if ($ps_album_id <= 0) {

			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => $psI18n->__('This content is no longer available.'),
				'title' => $psI18n->__('Albums'),
				'data_info' => []
			);
		} else {

			if ($user->user_type == USER_TYPE_TEACHER) {

				$return_data = $this->getAlbumDetailForMember($psI18n, $user, $ps_album_id);
			} elseif ($user->user_type == USER_TYPE_RELATIVE) {

				$return_data = $this->getAlbumDetailForRelative($psI18n, $user, $ps_album_id);
			}
		}

		return $response->withJson($return_data);
	}

	// Tao moi 1 album - Da viet lai
	public function addAlbum(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Create albums errors.'),
			'_data' => []
		);

		if ($user->user_type == USER_TYPE_RELATIVE) {
			$return_data = array(
				'_msg_code' => MSG_CODE_500,
				'_msg_text' => $psI18n->__('This content is no longer available.'),
				'_data' => []
			);
		}

		$body = $request->getParsedBody();

		$album_title = isset($body['info']['title']) ? PsString::trimString($body['info']['title']) : '';

		// $album_note = isset($body['info']['note']) ? PsString::trimString($body['info']['note']) : '';

		$class_id = isset($body['info']['class_id']) ? PsString::trimString($body['info']['class_id']) : '';

		// $ps_class_id = isset ( $body ['info'] ['note'] ) ? PsString::trimString($body ['info'] ['ps_class_id']) : 0;

		// Validator
		$check_album_title = vali::notEmpty()->stringType()->length(1, 255)->validate($album_title);
		// $check_album_note = vali::stringType()->length(null, 500)->validate($album_note);

		if (!$check_album_title || !$check_album_note) {

			$_msg_text_error = $psI18n->__('Create albums errors.');

			$_msg_text_error .= PsString::newLine() . $psI18n->__('Album names cannot be empty and up to 255 characters.');

			$_msg_text_error .= PsString::newLine() . $psI18n->__('Album content up to 255 characters.');

			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $_msg_text_error,
				'_data' => []
			);
		} else {
			// Lay thong tin member
			$ps_member = PsMemberModel::getMember($user->member_id, null, $class_id);

			if (count($ps_member) <= 0) {
				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('You do not have access to this data.'),
					'_data' => []
				);
			} else {

				try {

					AlbumModel::beginTransaction();

					$ps_class_id = $ps_member->myclass_id;

					$current_time = date('Y-m-d H:i:s');

					$album_id = $this->db->table(TBL_PS_ALBUMS)->insertGetId([
						'ps_customer_id'  => $user->ps_customer_id,
						'title' 		  => $album_title,
						// 'note' 			  => $album_note,
						'ps_class_id' 	  => $ps_class_id,
						'user_created_id' => $user->id,
						'user_updated_id' => $user->id,
						'created_at' => $current_time,
						'updated_at' => $current_time
					]);

					if ($album_id <= 0) {

						$_msg_text_error = $psI18n->__('Create albums errors.');

						$return_data = array(
							'_msg_code' => MSG_CODE_FALSE,
							'_msg_text' => $psI18n->__('Create albums errors.'),
							'_data' => []
						);
					} else {

						$album_key = PsEndCode::psGenerateAlbumKey($user->ps_customer_id, $ps_class_id);
						$url_album = PsEndCode::psGeneratePathUrlAlbum($user->ps_customer_id, $album_key, $current_time);

						$sql = $this->db->table(TBL_PS_ALBUMS)->where('id', $album_id)->update([
							'album_key' => $album_key,
							'url_album' => $url_album
						]);

						if ($sql) {

							$return_data = array();

							$return_data['_msg_code'] = MSG_CODE_TRUE;
							$return_data['_msg_text'] = $psI18n->__('Create a album successful.');
							$return_data['_data']['album_id'] = $album_id;
							$return_data['_data']['album_key'] = $album_key;
							$return_data['_data']['url_album'] = $url_album;
						} else {

							$return_data = array(
								'_msg_code' => MSG_CODE_FALSE,
								'_msg_text' => $psI18n->__('Create albums errors.'),
								'_data' => []
							);
						}
					}

					AlbumModel::commit();
				} catch (Exception $e) {

					AlbumModel::rollback();

					$this->WriteLog('-- BEGIN ERROR--: TAO ALBUM LOI');

					$this->WriteLogError($e->getMessage(), $user);

					$this->WriteLog('-- END ERROR--: TAO ALBUM LOI');
				}
			}
		}

		return $response->withJson($return_data);
	}

	public function createAlbum(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Create albums errors.'),
			'_data' => []
		);

		if ($user->user_type == USER_TYPE_RELATIVE) {
			$return_data = array(
				'_msg_code' => MSG_CODE_500,
				'_msg_text' => $psI18n->__('This content is no longer available.'),
				'_data' => []
			);
		}

		$body = $request->getParsedBody();

		$album_title = isset($body['info']['title']) ? PsString::trimString($body['info']['title']) : '';

		$album_note = isset($body['info']['note']) ? PsString::trimString($body['info']['note']) : '';

		$class_id = isset($body['info']['class_id']) ? PsString::trimString($body['info']['class_id']) : '';

		// $ps_class_id = isset ( $body ['info'] ['note'] ) ? PsString::trimString($body ['info'] ['ps_class_id']) : 0;

		// Validator
		$check_album_title = vali::notEmpty()->stringType()->length(1, 255)->validate($album_title);
		$check_album_note = vali::stringType()->length(null, 500)->validate($album_note);

		if (!$check_album_title || !$check_album_note) {

			$_msg_text_error = $psI18n->__('Create albums errors.');

			$_msg_text_error .= PsString::newLine() . $psI18n->__('Album names cannot be empty and up to 255 characters.');

			$_msg_text_error .= PsString::newLine() . $psI18n->__('Album content up to 255 characters.');

			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $_msg_text_error,
				'_data' => []
			);
		} else {
			// Lay thong tin member
			$ps_member = PsMemberModel::getMember($user->member_id, null, $class_id);

			if (count($ps_member) <= 0) {
				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('You do not have access to this data.'),
					'_data' => []
				);
			} else {

				try {

					AlbumModel::beginTransaction();

					$ps_class_id = $ps_member->myclass_id;

					$current_time = date('Y-m-d H:i:s');

					$album_id = $this->db->table(TBL_PS_ALBUMS)->insertGetId([
						'ps_customer_id'  => $user->ps_customer_id,
						'title' 		  => $album_title,
						'note' 			  => $album_note,
						'ps_class_id' 	  => $ps_class_id,
						'is_activated'    => 1,
						'user_created_id' => $user->id,
						'user_updated_id' => $user->id,
						'created_at' => $current_time,
						'updated_at' => $current_time
					]);






					if ($album_id <= 0) {

						$_msg_text_error = $psI18n->__('Create albums errors.');

						$return_data = array(
							'_msg_code' => MSG_CODE_FALSE,
							'_msg_text' => $psI18n->__('Create albums errors.'),
							'_data' => []
						);
					} else {

						$album_key = PsEndCode::psGenerateAlbumKey($user->ps_customer_id, $ps_class_id);
						$url_album = PsEndCode::psGeneratePathUrlAlbum($user->ps_customer_id, $album_key, $current_time);

						$sql = $this->db->table(TBL_PS_ALBUMS)->where('id', $album_id)->update([
							'album_key' => $album_key,
							'url_album' => $url_album
						]);

						$url_files = isset($body['info']['url_file']) ? $body['info']['url_file'] : '';

						$url_thumbnails = isset($body['info']['url_thumbnail']) ? $body['info']['url_thumbnail'] : '';

						$album = AlbumModel::getAlbumById($album_id, $user->ps_customer_id);

						foreach ($url_files as $url_file) {




							$url_thumb = $this->getUrlFileThumbailOfAblumItemFile($url_file, $album->url_album);


							$albumItem = new AlbumItemModel();

							$albumItem->album_id 		= $album_id;
							$albumItem->url_file 		= $url_file;
							$albumItem->url_thumbnail 	= $url_thumb;
							$albumItem->user_created_id = $user->id;
							$albumItem->created_at 		= date('Y-m-d H:i:s');
							$albumItem->updated_at 		= date('Y-m-d H:i:s');

							$albumItem->save();
						}

						$album->number_img = AlbumItemModel::getTotalImageOfAlbum($album_id);
						$album->save();

						if ($sql) {

							$return_data = array();

							$return_data['_msg_code'] = MSG_CODE_TRUE;
							$return_data['_msg_text'] = $psI18n->__('Create a album successful.');
							$return_data['_data']['album_id'] = $album_id;
							$return_data['_data']['album_key'] = $album_key;
							$return_data['_data']['url_album'] = $url_album;
						} else {

							$return_data = array(
								'_msg_code' => MSG_CODE_FALSE,
								'_msg_text' => $psI18n->__('Create albums errors.'),
								'_data' => []
							);
						}
					}

					AlbumModel::commit();
				} catch (Exception $e) {

					AlbumModel::rollback();

					$this->WriteLog('-- BEGIN ERROR--: TAO ALBUM LOI');

					$this->WriteLogError($e->getMessage(), $user);

					$this->WriteLog('-- END ERROR--: TAO ALBUM LOI');
				}
			}
		}

		return $response->withJson($return_data);
	}


	// Xoa album - Da viet lai
	public function deleteAlbum(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Delete a album failure.'),
			'_data' => []
		);

		if ($user->user_type == USER_TYPE_RELATIVE) {
			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('You do not have access to this data'),
				'_data' => []
			);
		} elseif ($user->user_type == USER_TYPE_TEACHER) {

			$album_id = $args['album_id'];

			if ($album_id <= 0) {
				return $response->withJson($return_data);
			}

			// Cho phep xoa ca Album bị khóa nếu có quyền
			$album = AlbumModel::getAlbumById($album_id, $user->ps_customer_id);

			if (!$album) {
				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('This content is no longer available.')
				);
			} else {

				// Kiem tra xem User mới có quyền Thao tác: Sửa/Xóa/ Thêm ảnh Album do mình tạo
				if ($album->user_created_id != $user->id) {
					$return_data = array(
						'_msg_code' => MSG_CODE_FALSE,
						'_msg_text' => $psI18n->__('You do not have access to this data')
					);
				} else {

					try {

						AlbumModel::beginTransaction();

						// Xoa các ảnh của Album
						$delete_album_items = AlbumItemModel::where('album_id', $album_id)->delete();

						$delete_album = AlbumModel::where('id', $album_id)->delete();

						$return_data = array(
							'_msg_code' => MSG_CODE_TRUE,
							'_msg_text' => $psI18n->__('Delete a album successful.')
						);

						AlbumModel::commit();
					} catch (Exception $e) {

						AlbumModel::rollBack();

						$this->WriteLog('-- BEGIN ERROR--: XOA ALBUM LOI');

						$this->WriteLog('-- ID ALBUM:' . $album_id);

						//$this->WriteLogError ( $e->getMessage (), $user );

						$this->WriteLog('-- END ERROR--: XOA ALBUM LOI');

						$return_data = array(
							'_msg_code' => MSG_CODE_FALSE,
							'_msg_text' => $psI18n->__('You do not have access to this data')
						);
					}
				}
			}
		}

		return $response->withJson($return_data);
	}

	// Update mot album - Da viet lai
	public function updateAlbum(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Update album failure.')
		);

		if ($user->user_type == USER_TYPE_RELATIVE) {
			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('You do not have access to this data')
			);

			return $response->withJson($return_data);
		}

		try {
			$album_id = $args['album_id'];

			if ($album_id <= 0) {
				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('This content is no longer available.')
				);
				return $response->withJson($return_data);
			}

			/*
			 * $_arr_album_activated = array (
			 * STATUS_NOT_ACTIVE,
			 * STATUS_ACTIVE
			 * );
			 * // Chỉ cho sua Album không bị khóa ?
			 * $album = AlbumModel::getAlbumById ( $album_id, $_arr_album_activated );
			 *
			 */
			$album = AlbumModel::getAlbumById($album_id, $user->ps_customer_id);

			if (count($album) <= 0) {
				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('This content is no longer available.')
				);
				return $response->withJson($return_data);
			}

			if ($album->user_created_id != $user->id) {
				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('You do not have access to this data')
				);

				return $response->withJson($return_data);
			}

			$body = $request->getParsedBody();

			$album_title = isset($body['info']['title']) ? PsString::trimString($body['info']['title']) : '';

			// $album_note = isset($body['info']['note']) ? PsString::trimString($body['info']['note']) : '';

			$check_album_title = vali::notEmpty()->stringType()->length(1, 255)->validate($album_title);
			// $check_album_note = vali::stringType()->length(null, 500)->validate($album_note);

			if (!$check_album_title) {

				$_msg_text_error = $psI18n->__('Create albums errors.');

				$_msg_text_error .= PsString::newLine() . $psI18n->__('Album names cannot be empty and up to 255 characters.');

				$_msg_text_error .= PsString::newLine() . $psI18n->__('Album content up to 255 characters.');

				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $_msg_text_error
				);
			} else {

				$album->title = $album_title;
				// $album->note = $album_note;
				$album->user_updated_id = $user->id;
				$album->updated_at = date("Y-m-d H:i:s");

				if ($album->save()) {
					$return_data = array(
						'_msg_code' => MSG_CODE_TRUE,
						'_msg_text' => $psI18n->__('Update album successfully.')
					);
				}
			}
		} catch (Exception $e) {

			$this->WriteLog('-- BEGIN ERROR--: CAP NHAT ALBUM LOI');

			$this->WriteLog('-- ID ALBUM:' . $album_id);

			$this->WriteLogError($e->getMessage(), $user);

			$this->WriteLog('-- END ERROR--: CAP NHAT ALBUM LOI');
		}

		return $response->withJson($return_data);
	}

	// Cập nhật trạng thái: Public - Ko public => Đã viết lại
	public function updateStatusAlbum(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Update album failure.')
		);

		if ($user->user_type == USER_TYPE_RELATIVE) {
			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('You do not have access to this data')
			);

			return $response->withJson($return_data);
		}

		try {

			$album_id = $args['album_id'];

			// $this->WriteLog ( '-- END ERROR 1' );

			if ($album_id <= 0) {
				// $this->WriteLog ( '-- END ERROR 2' );
				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('This content is no longer available.')
				);
				return $response->withJson($return_data);
			}

			// $this->WriteLog ( '-- END ERROR 3' );

			// Chỉ Update trạng thái với Album chưa bị khóa
			$_arr_album_activated = array(
				STATUS_NOT_ACTIVE,
				STATUS_ACTIVE
			);

			$album = AlbumModel::getAlbumById($album_id, $user->ps_customer_id, $_arr_album_activated);

			// $this->WriteLog ( '-- END ERROR 4' );

			if (count($album) <= 0) {

				// $this->WriteLog ( '-- COUNT:'.count ( $album ) );

				// $this->WriteLog ( '-- END ERROR 5: album_id = '.$album_id .'; ps_customer_id: '.$user->ps_customer_id.'; _arr_album_activated:'.$response->withJson ( $_arr_album_activated ) );

				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('Update album failure.')
				);

				return $response->withJson($return_data);
			}

			if ($album->user_created_id != $user->id) {

				// $this->WriteLog ( '-- END ERROR 6' );

				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('You do not have access to this data')
				);

				return $response->withJson($return_data);
			}

			// $this->WriteLog ( '-- END ERROR 7' );

			$return_data = array();

			// Gửi notify báo có Album mới
			$push_notication = false;

			if ($album->is_activated == STATUS_NOT_ACTIVE) {

				$album->is_activated = STATUS_ACTIVE;

				$album->user_updated_id = $user->id;

				$album->updated_at = date("Y-m-d H:i:s");

				$return_data['_msg_text'] = $psI18n->__('Album is activated');

				if ($album->number_push_activated <= 0) {
					$album->number_push_activated = $album->number_push_activated + 1;
					//$push_notication = true;										
				}

				$push_notication = true;
			} elseif ($album->is_activated == STATUS_ACTIVE) {

				$album->is_activated = STATUS_NOT_ACTIVE;

				$album->user_updated_id = $user->id;

				$album->updated_at = date("Y-m-d H:i:s");

				$return_data['_msg_text'] = $psI18n->__('Album is not activated');
			}

			$album->number_img = AlbumItemModel::getTotalImageOfAlbum($album_id);

			$return_data['_msg_code'] = $album->save() ? MSG_CODE_TRUE : MSG_CODE_FALSE;

			if ($push_notication) { // Gui tin nhắn cho app phu huynh

				$a = $this->pushNotificationNewAlbum($psI18n, $user, $album);

				//$this->WriteLog ( 'ALBUM: ' . $response->withJson ( $a ) );
			}
		} catch (Exception $e) {

			$this->WriteLog('-- BEGIN ERROR--: CAP NHAT TRANG THAI ALBUM LOI');

			$this->WriteLog('-- ID ALBUM:' . $album_id);

			$this->WriteLogError($e->getMessage(), $user);

			$this->WriteLog('-- END ERROR--: CAP NHAT TRANG THAI ALBUM LOI');
		}

		return $response->withJson($return_data);
	}

	// Upload anh len album - Đã viết lại
	public function uploadItemToAlbum(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Upload photos failed.')
		);


		if ($user->user_type == USER_TYPE_RELATIVE) {
			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('You do not have access to this data')
			);

			return $response->withJson($return_data);
		}


		$body = $request->getParsedBody();



		$album_id = isset($body['info']['album_id']) ? $body['info']['album_id'] : '';


		if ($album_id <= 0) {
			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('This content is no longer available.')
			);
			return $response->withJson($return_data);
		}

		// Lấy Album có mọi trạng thái
		$album = AlbumModel::getAlbumById($album_id, $user->ps_customer_id);



		if (count($album) <= 0) {
			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('This content is no longer available.')
			);
			return $response->withJson($return_data);
		}

		// Nếu Album đã bị khóa hoặc User không có quyền thao tác với Album này
		// if ($album->is_activated == STATUS_LOCK || ($album->user_created_id != $user->id)) {
		if ($album->is_activated == STATUS_LOCK) {

			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('You do not have access to this data')
			);

			return $response->withJson($return_data);
		}

		// Neu khong phai la nguoi tao album
		if ($album->user_created_id != $user->id) {

			// Lay thong tin cơ sở của lớp học
			$ps_workplace = PsWorkPlacesModel::getColumnByClassId($album->ps_class_id);

			if ($ps_workplace && $ps_workplace->config_multiple_teacher_process_album == STATUS_ACTIVE) {
				// Kiem tra xem hien tai giao vien nay con ở trong lop hoc nay ko
				$checkCurrentMyClassOfMember = PsMemberModel::checkCurrentMyClassOfMember($user->id, $album->ps_class_id);

				if (!$checkCurrentMyClassOfMember) {
					$return_data = array(
						'_msg_code' => MSG_CODE_FALSE,
						'_msg_text' => $psI18n->__('You do not have access to this data')
					);

					return $response->withJson($return_data);
				}
			} else {
				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('You do not have access to this data')
				);

				return $response->withJson($return_data);
			}
		}

		// file
		$url_files = isset($body['info']['url_file']) ? $body['info']['url_file'] : '';



		// file thumbail
		$url_thumbnails = isset($body['info']['url_thumbnail']) ? $body['info']['url_thumbnail'] : '';



		//$this->WriteLog("URL FILE:".$response->withJson ( $url_files ));
		//$this->WriteLog("THUMBAIL:".$response->withJson ( $url_thumbnails ));

		// $ps_class_id = isset ( $body ['info'] ['ps_class_id'] ) ? $body ['info'] ['ps_class_id'] : ''; // => KO CẦN

		$url_file_check = vali::notEmpty()->arrayType()->validate($url_files);

		$url_file_thum_check = vali::notEmpty()->arrayType()->validate($url_thumbnails);

		if ($url_file_check && $url_file_thum_check) {


			try {

				AlbumModel::beginTransaction();

				foreach ($url_files as $url_file) {

					/*
					$file_name = $this->getFileNameByUrl ( $url_file );
					
					$url_thumb = $url_file;
					 
					foreach ( $url_thumbnails as $url_thumbnail ) {
						$file_name_thumb = $this->getFileNameByUrl ( $url_thumbnail );
						if ($file_name === $file_name_thumb) {
							$url_thumb = $url_thumbnail;
						 	break;
						}
					}
					*/

					$url_thumb = $this->getUrlFileThumbailOfAblumItemFile($url_file, $album->url_album);

					/*
					$img_id = $this->db->table ( TBL_PS_ALBUM_ITEMS )->insertGetId ( [
							'album_id' 			=> $album_id,
							'url_file' 			=> $url_file,
							'url_thumbnail' 	=> $url_thumb,
							'user_created_id' 	=> $user->id,
							'created_at' 		=> date ( 'Y-m-d H:i:s' ),
							'updated_at' 		=> date ( 'Y-m-d H:i:s' )
					] );
					*/
					$albumItem = new AlbumItemModel();

					$albumItem->album_id 		= $album_id;
					$albumItem->url_file 		= $url_file;
					$albumItem->url_thumbnail 	= $url_thumb;
					$albumItem->user_created_id = $user->id;
					$albumItem->created_at 		= date('Y-m-d H:i:s');
					$albumItem->updated_at 		= date('Y-m-d H:i:s');

					$albumItem->save();
				}

				//$number_img = AlbumItemModel::getImageByAlbumId ( $album_id )->count ();
				// $album = AlbumModel::find ( $album_id );
				$album->number_img = AlbumItemModel::getTotalImageOfAlbum($album_id);
				$album->save();

				$return_data = array(
					'_msg_code' => MSG_CODE_TRUE,
					'_msg_text' => $psI18n->__('Upload photos successful.')
				);

				AlbumModel::commit();
			} catch (Exception $e) {

				AlbumModel::rollBack();

				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('Upload photos failed.')
				);
			}
		}

		return $response->withJson($return_data);
	}

	// Cap nhat trang thai Album Item
	public function updateStatusAlbumItem(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['_msg_text'] = $psI18n->__('Update status failure.');

		if ($user->user_type == USER_TYPE_RELATIVE) {
			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('You do not have access to this data')
			);

			return $response->withJson($return_data);
		}

		$img_id = $args['img_id'];

		if ($img_id <= 0) {
			return $response->withJson($return_data);
		}

		try {

			$albumItem = AlbumItemModel::getAlbumInfoItemById($img_id, $user->ps_customer_id);

			if (!$albumItem) {

				$return_data['_msg_text'] = $psI18n->__('This content is no longer available.');

				return $response->withJson($return_data);
			} elseif ($albumItem->user_created_id != $user->id) {

				$return_data['_msg_text'] = $psI18n->__('You do not have access to this data.');

				return $response->withJson($return_data);
			}

			if ($albumItem->is_activated == STATUS_LOCK) {

				$return_data['_msg_text'] = $psI18n->__('This content is locked due to policy violations');

				return $response->withJson($return_data);
			} else {

				$new_is_activated = $albumItem->is_activated;

				if ($albumItem->is_activated == STATUS_NOT_ACTIVE) {

					$new_is_activated = STATUS_ACTIVE;

					$return_data['_msg_text'] = $psI18n->__('Image is activated');
				} elseif ($albumItem->is_activated == STATUS_ACTIVE) {

					$new_is_activated = STATUS_NOT_ACTIVE;

					$return_data['_msg_text'] = $psI18n->__('Image is not activated');
				}

				$update = $this->db->table(TBL_PS_ALBUM_ITEMS)->where('id', (int) $img_id)->update([
					'is_activated' => $new_is_activated,
					'user_updated_id' => $user->id,
					'updated_at' => date('Y-m-d H:i:s')
				]);

				if ($update) {

					$return_data['_msg_code'] = MSG_CODE_TRUE;
				} else {

					$return_data['_msg_code'] = MSG_CODE_FALSE;

					$return_data['_msg_text'] = $psI18n->__('Update status failure.');
				}
			}
		} catch (Exception $e) {

			$return_data['_msg_code'] = MSG_CODE_FALSE;

			$return_data['_msg_text'] = $psI18n->__('Update status failure.');
		}

		return $response->withJson($return_data);
	}

	// Xoa anh trong album - dang viet lai
	public function deleteAlbumItem(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['_msg_text'] = $psI18n->__('Delete a photos failure.');

		if ($user->user_type == USER_TYPE_RELATIVE) {

			$return_data['_msg_text'] = $psI18n->__('You do not have access to this data');

			return $response->withJson($return_data);
		}

		$img_id = $args['img_id'];

		if ($img_id <= 0)
			return $response->withJson($return_data);

		$albumItem = AlbumItemModel::getAlbumInfoItemById($img_id, $user->ps_customer_id);

		if (!$albumItem) {

			$return_data['_msg_text'] = $psI18n->__('This content is no longer available.');

			return $response->withJson($return_data);
		}
		/*
		elseif ($albumItem->user_created_id != $user->id) {

			$return_data ['_msg_text'] = $psI18n->__ ( 'You do not have access to this data.' );

			return $response->withJson ( $return_data );
		}
		*/

		if ($albumItem->is_activated == STATUS_LOCK) {

			$return_data['_msg_text'] = $psI18n->__('This content is locked due to policy violations');

			return $response->withJson($return_data);
		} else {

			try {

				AlbumItemModel::beginTransaction();

				$album_id = $albumItem->album_id;

				//AlbumItemModel::deleteAlbumItem ( $img_id );

				if (AlbumItemModel::where('id', '=', $img_id)->delete()) {

					$album = AlbumModel::find($album_id);

					if ($album) {

						$album->number_img = AlbumItemModel::getTotalImageOfAlbum($album_id);

						$album->save();

						$return_data['_msg_code'] = MSG_CODE_TRUE;

						$return_data['_msg_text'] = $psI18n->__('Delete a photos successful.');
					}
				}

				AlbumItemModel::commit();
			} catch (Exception $e) {

				AlbumItemModel::rollBack();

				$return_data['_msg_code'] = MSG_CODE_FALSE;

				$return_data['_msg_text'] = $psI18n->__('Delete a photos failure.');
			}
		}

		return $response->withJson($return_data);
	}

	// Hien thi danh sach album trong 1 lop - cua giao vien
	public function showAlbumsOfClass(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => '',
			'_data' => []
		);

		$user = $this->user_token;
		// var_dump($user);
		// return $user;
		$ps_class_id = $args['ps_class_id'];

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		if ($ps_class_id <= 0) {

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');

			return $response->withJson($return_data);
		}

		// Check $ps_class_id co thuoc ve giao vien hay ko
		$obj_check = PsMemberModel::checkMyClassOfMember($user->id, $ps_class_id);

		if (!$obj_check) {

			$return_data['_msg_code'] = MSG_CODE_500;
			$return_data['message'] = $psI18n->__('You have no access with the data of the class');
			$return_data['_msg_text'] = $psI18n->__('You have no access with the data of the class');

			return $response->withJson($return_data);
		} else {

			$list_albums = AlbumModel::getListAlbumsForHR($ps_class_id, $user->id);

			$data_list_albums = array();



			foreach ($list_albums as $album) {

				// Lay so luong anh trong album
				/**
				 * Lay tất cả Album đã public của lớp + Album chưa public của lớp và do GV tạo
				 */
				$sql = $this->db->table(CONST_TBL_USER)->where('id', $album->user_created_id)->get()->first();

				$ab_likes = $this->db->table(TBL_PS_ALBUMS_LIKE)->where('album_id', $album->id)->where('relative_id', $user->id)->get()->first();
				$status_like = $ab_likes->number_like;

				$temp_album = new \stdClass();
				$temp_album->id = (int) $album->id;
				$temp_album->album_key = (string) $album->album_key;
				$temp_album->ps_class_id = $album->ps_class_id ? (int) $album->ps_class_id : null;
				$temp_album->is_activated = (int) $album->is_activated;
				$temp_album->title = (string) $album->title;
				$temp_album->note = (string) $album->note;
				$temp_album->nguoi_tao = (string) $sql->first_name . " " . $sql->last_name;
				$temp_album->ngay_tao =  Date($album->updated_at);
				$temp_album->avatar = ($sql->avatar != '') ? PsString::getUrlMediaAvatar($sql->cache_data, $sql->year_data, $sql->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
				$temp_album->number_like = (int) $album->number_like;
				$temp_album->status_like = $status_like;
				$temp_album->count_comment = (int)$album->count_comment;
				//$temp_album->number_dislike = (int) $album->number_dislike;
				$temp_album->count = (int) $album->number_img;

				// Set ảnh đại diện cho album
				$temp_album->url_file_represent = (string) $album->url_thumbnail;

				$temp_album->data_items = $this->getAlbumDetailForMember($psI18n, $user, $album->id);

				/*
				 * $images = AlbumItemModel::getImageByAlbumKey ( $album->album_key );
				 * $temp_album->count = count ( $images );
				 *
				 * // Lay anh cuoi cung cua anbuml
				 * $images = AlbumItemModel::getImageByAlbumKey ( $album->album_key )->first ();
				 *
				 * $temp_album->url_file_represent = $images->url_thumbnail;
				 */

				array_push($data_list_albums, $temp_album);
			}

			$return_data['_msg_code'] = MSG_CODE_TRUE;
			$return_data['_msg_text'] = '';
			$return_data['_data'] = [];
			$return_data['ps_class_id'] = (int) $ps_class_id;
			$return_data['data_info'] = $data_list_albums;
		}

		return $response->withJson($return_data);
	}

	/**
	 * Lay chi tiet 1 Album cho Giáo viên
	 *
	 * @author thangnc
	 *        
	 * @param $psI18n -
	 *        	mixed
	 * @param $user -
	 *        	mixed
	 * @param $ps_album_id -
	 *        	int ID Album
	 * @return boolean
	 * @return mixed
	 *
	 */
	protected function getAlbumDetailForMember($psI18n, $user, $ps_album_id)
	{

		$album = AlbumModel::getAlbumById($ps_album_id, $user->ps_customer_id);

		if (count($album) <= 0) {
			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => $psI18n->__('This content is no longer available.'),
				'title' => $psI18n->__('Albums'),
				'data_info' => []
			);
		} else {

			$_data = array();

			$_data['_msg_code'] = MSG_CODE_TRUE;

			$_data['_msg_text'] = ($album->number_img <= 0) ? $psI18n->__('The album has no photos.') : '';

			$_data['title'] = $psI18n->__('Albums');

			$_data['ps_class_id'] = (int) $album->ps_class_id;

			$_data['title_ps_album'] 	= (string) $album->title;

			$_data['note_ps_album'] 	= (string) $album->note;

			$sql = $this->db->table(CONST_TBL_USER)->where('id', $album->user_created_id)->get()->first();
			$_data['nguoi_tao'] = (string) $sql->first_name . " " . $sql->last_name;
			// $temp_album->ngay_tao =  Date($album->updated_at);
			$_data['avatar'] = ($sql->avatar != '') ? PsString::getUrlMediaAvatar($sql->cache_data, $sql->year_data, $sql->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

			$_data['count'] 			= (int) $album->number_img;

			//$_data ['title_content'] = ( string ) $album->title . '<br>' . $album->number_img . ' ' . $psI18n->__ ( 'photos' ) . ', ' . $psI18n->__ ( 'Views' ) . ': ' . ($album->number_view);
			// $title_content = '<span style="color:#8bd139;font-size:15px;">' . $album->title . '</span><br/><div style="padding-top:5px;"><span style="font-style: italic;font-size: 12px;">' . $psI18n->__('Date post') . ': ' . PsDateTime::toDMY($album->created_at, "d/m/Y") . '</span> - <span style="font-style: italic;color:#ff0000;font-size: 12px;">' . $_data['count'] . '</span> <span style="font-style: italic;font-size: 12px">' . $psI18n->__('photos') . '</span>, <span style="font-style: italic;font-size: 12px">' . $psI18n->__('Views') . ': <span style="color:#ff0000;">' . $album->number_view . '</span></span></div>' . '<div style="padding-top: 8px;width:100%;float:left;">' . $album->note . '</div>';
			// $_data_title_content = PsWebContent::styleContentTextDetailAlbum($title_content);

			$max_item = 100;

			$_data['max_item'] = $max_item;

			$get_item_album 	= true;
			$data_images 		= array();
			$_arr_img_activated = array();

			$role_add_image = STATUS_NOT_ACTIVE;

			if ($album->is_activated == STATUS_LOCK) { // Bị khóa

				$role = 0;
				/*
				// Nếu bị khóa mà Album này do User khác tạo ra thì trả về Không được hiển thị
				if ($album->user_created_id != $user->id) {

					$_data ['_msg_text'] = $psI18n->__ ( 'Album is not activated' );

					$get_item_album = false;
				}*/

				$_data['_msg_text'] = $psI18n->__('Album is lock');

				$get_item_album = false;
			} else {

				//$role = ($album->user_created_id == $user->id) ? 1 : 0;

				if ($album->user_created_id == $user->id) {
					$role = STATUS_ACTIVE;
					$role_add_image = STATUS_ACTIVE;
					$_arr_img_activated = array(STATUS_NOT_ACTIVE, STATUS_ACTIVE);
				} else {

					// Lay thong tin cơ sở của lớp học
					$ps_workplace = PsWorkPlacesModel::getColumnByClassId($album->ps_class_id);

					if ($ps_workplace && $ps_workplace->config_multiple_teacher_process_album == STATUS_ACTIVE) {

						// Kiem tra xem hien tai giao vien nay con ở trong lop hoc nay ko
						$checkCurrentMyClassOfMember = PsMemberModel::checkCurrentMyClassOfMember($user->id, $album->ps_class_id);

						if ($checkCurrentMyClassOfMember) {
							$role = STATUS_ACTIVE;
							$_arr_img_activated = array(STATUS_NOT_ACTIVE, STATUS_ACTIVE);

							$role_add_image = STATUS_ACTIVE;
						}
					} else {
						$role = STATUS_NOT_ACTIVE;
						$_arr_img_activated = array(STATUS_ACTIVE);
						$role_add_image = STATUS_NOT_ACTIVE;
					}
				}
			}

			if ($_data['count'] >= $max_item) {
				$role_add_image = STATUS_NOT_ACTIVE;
			}

			if ($get_item_album) { // Lấy danh sách sách ảnh của Album

				/**
				 * Nếu có quyền chỉnh sửa => Lấy tất cả ảnh có is_activated <> 2 (Lấy is_activated = 0; 1)
				 * Nếu không có quyền => Lấy các ảnh được public
				 **/
				$ps_album_items = AlbumItemModel::getListImageByAlbumId($ps_album_id, $_arr_img_activated);

				$_data['count'] = count($ps_album_items);

				// $_data_title_content = '<span style="color:#8bd139;font-size:15px;">' . $album->title . '</span><br/><div style="padding-top:5px;"><span style="font-style: italic;font-size: 12px;">' . $psI18n->__('Date post') . ': ' . PsDateTime::toDMY($album->created_at, "d/m/Y") . '</span> - <span style="font-style: italic;color:#ff0000;font-size: 12px;">' . $_data['count'] . '</span> <span style="font-style: italic;font-size: 12px">' . $psI18n->__('photos') . '</span>, <span style="font-style: italic;font-size: 12px">' . $psI18n->__('Views') . ': <span style="color:#ff0000;">' . $album->number_view . '</span></span></div>' . '<div style="padding-top: 8px;width:100%;float:left;">' . $album->note . '</div>';

				if ($ps_album_id == 696) {
					foreach ($ps_album_items as $image) {

						$temp_image = new \stdClass();

						$temp_image->id = (int) $image->id;
						$temp_image->album_id = (int) $image->album_id;
						$temp_image->title = (string) $image->title;
						$temp_image->url_file = (string) $image->url_file;
						$temp_image->url_thumbnail = $temp_image->url_file;
						$temp_image->is_activated = (int) $image->is_activated;
						$temp_image->note = (string) $image->note;
						$temp_image->number_like = (int) $image->number_like;
						$temp_image->number_dislike = (int) $image->number_dislike;

						$temp_image->role = (int) $role;

						// $title_content = '<span style="font-style: italic;font-size: 12px;color:#fff;">' . $psI18n->__('Created by') . ': ' . $image->first_name . ' ' . $image->last_name . ', ' . $psI18n->__('Day') . ': ' . date("H:i d/m/Y", strtotime($image->created_at)) . '</span>'; //.'-ID: '.$image->id;

						// $temp_image->title_content = PsWebContent::styleContentTextDetailItem($title_content);

						array_push($data_images, $temp_image);
					}
				} else {
					foreach ($ps_album_items as $image) {

						$temp_image = new \stdClass();

						$temp_image->id = (int) $image->id;
						$temp_image->album_id = (int) $image->album_id;
						$temp_image->title = (string) $image->title;
						$temp_image->url_file = (string) $image->url_file;
						$temp_image->url_thumbnail = (string) $image->url_thumbnail;
						$temp_image->is_activated = (int) $image->is_activated;
						$temp_image->note = (string) $image->note;
						$temp_image->number_like = (int) $image->number_like;
						$temp_image->number_dislike = (int) $image->number_dislike;

						$temp_image->role = (int) $role;

						//$title_content = $psI18n->__ ( 'Photo in album' ).': '.( string ) $album->title;//.'-ID: '.$image->id;

						// $title_content = '<span style="font-style: italic;font-size: 12px;color:#fff;">' . $psI18n->__('Created by') . ': ' . $image->first_name . ' ' . $image->last_name . ', ' . $psI18n->__('Day') . ': ' . date("H:i d/m/Y", strtotime($image->created_at)) . '</span>'; //.'-ID: '.$image->id;

						// $temp_image->title_content = PsWebContent::styleContentTextDetailItem($title_content);

						array_push($data_images, $temp_image);
					}
				}

				if ($album->user_created_id != $user->id) {
					$album->number_view = $album->number_view + 1;
					$album->save();
				}
			}

			// $_data['title_content'] = PsWebContent::styleContentTextDetailAlbum($_data_title_content);

			$_data['role']      		   = $role;
			$_data['role_add_image']      = $role_add_image;
			$_data['data_info']		   = $data_images;


			// $list_items = array();
			// array_push($list_items, $_data);
			$return_data = $_data;
		}

		return $return_data;
	}

	/**
	 * Lay chi tiet 1 Album cho người thân của bé
	 *
	 * @author thangnc
	 *        
	 * @param $psI18n -
	 *        	mixed
	 * @param $user -
	 *        	mixed
	 * @param $album_id -
	 *        	int ID Album
	 * @return mixed
	 *
	 */
	protected function getAlbumDetailForRelative($psI18n, $user, $ps_album_id)
	{

		$album = AlbumModel::getAlbumById($ps_album_id, $user->ps_customer_id, array(
			STATUS_ACTIVE
		));

		if (!$album) {
			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => $psI18n->__('This content is no longer available.'),
				'data_info' => []
			);
		} else {

			// Kiem tra xem hoc sinh co phụ huynh này có nam trong lop $album->ps_class_id không
			$_data = $data_images = array();

			$_data['_msg_code'] = MSG_CODE_TRUE;

			$_data['title'] = $psI18n->__('Albums');

			$_data['_msg_text'] = ($album->number_img <= 0) ? $psI18n->__('The album has no photos.') : '';

			$_data['ps_class_id'] = (int) $album->ps_class_id;

			$_data['title_ps_album'] = (string) $album->title;

			$_data['note_ps_album']  = (string) $album->note;

			$sql = $this->db->table(CONST_TBL_USER)->where('id', $album->user_created_id)->get()->first();
			$_data['nguoi_tao'] = (string) $sql->first_name . " " . $sql->last_name;
			// $temp_album->ngay_tao =  Date($album->updated_at);
			$_data['avatar'] = ($sql->avatar != '') ? PsString::getUrlMediaAvatar($sql->cache_data, $sql->year_data, $sql->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

			$_data['count'] = (int) $album->number_img;

			$ps_album_items = AlbumItemModel::getListImageByAlbumId($ps_album_id, array(STATUS_ACTIVE));
			// /return $ps_album_items."AAA";

			$_data['count'] = count($ps_album_items);

			// $_data_title_content = '<span style="color:#8bd139;font-size:15px;">' . $album->title . '</span><br/><div style="padding-top:5px;"><span style="font-style: italic;font-size: 12px;">' . $psI18n->__('Date post') . ': ' . PsDateTime::toDMY($album->created_at, "d/m/Y") . '</span> - <span style="font-style: italic;color:#ff0000;font-size: 12px;">' . $_data['count'] . '</span> <span style="font-style: italic;font-size: 12px">' . $psI18n->__('photos') . '</span>, <span style="font-style: italic;font-size: 12px">' . $psI18n->__('Views') . ': <span style="color:#ff0000;">' . $album->number_view . '</span></span></div>' . '<div style="padding-top: 8px;width:100%;float:left;">' . $album->note . '</div>';

			if ($ps_album_id == 696) {
				foreach ($ps_album_items as $image) {

					$temp_image = new \stdClass();

					$temp_image->id = (int) $image->id;
					$temp_image->album_id = (int) $image->album_id;
					$temp_image->title = (string) $image->title;
					$temp_image->url_file = (string) $image->url_file;
					$temp_image->url_thumbnail = $temp_image->url_file;
					$temp_image->is_activated = (int) $image->is_activated;
					$temp_image->note = (string) $image->note;
					$temp_image->number_like = (int) $image->number_like;
					$temp_image->number_dislike = (int) $image->number_dislike;

					// $title_content = '<span style="font-style: italic;font-size: 12px;color:#fff;">' . $psI18n->__('Date post') . ': ' . date("H:i d/m/Y", strtotime($image->created_at)) . '</span>'; //.'-ID: '.$image->id;

					// $temp_image->title_content = PsWebContent::styleContentTextDetailItem($title_content);

					array_push($data_images, $temp_image);
				}
			} else {

				foreach ($ps_album_items as $image) {

					$temp_image = new \stdClass();

					$temp_image->id = (int) $image->id;
					$temp_image->album_id = (int) $image->album_id;
					$temp_image->title = (string) $image->title;
					$temp_image->url_file = (string) $image->url_file;
					$temp_image->url_thumbnail = (string) $image->url_thumbnail;
					$temp_image->is_activated = (int) $image->is_activated;
					$temp_image->note = (string) $image->note;
					$temp_image->number_like = (int) $image->number_like;
					$temp_image->number_dislike = (int) $image->number_dislike;
					//$title_content = $psI18n->__ ( 'Photo in album' ).': '.( string ) $album->title;//.'-ID: '.$image->id;

					//$title_content = $psI18n->__ ( 'Created' ).': '.date ( "H:i d-m-Y", strtotime ($image->created_at) );//.'-ID: '.$image->id;
					// $title_content = '<span style="font-style: italic;font-size: 12px;color:#fff;">' . $psI18n->__('Date post') . ': ' . date("H:i d/m/Y", strtotime($image->created_at)) . '</span>'; //.'-ID: '.$image->id;

					// $temp_image->title_content = PsWebContent::styleContentTextDetailItem($title_content);

					array_push($data_images, $temp_image);
				}
			}

			if ($_data['count'] > 0) {
				$album->number_view = $album->number_view + 1;
				$album->save();
			}

			// $_data['title_content'] = PsWebContent::styleContentTextDetailAlbum($_data_title_content);

			$_data['data_info'] = $data_images;

			$return_data = $_data;
		}

		return $return_data;
	}

	/**
	 * pushNotificationNewAlbum($psI18n, $user, $ps_album)
	 * Ham push notification khi giao vien public album
	 *
	 * @author thangnc
	 *        
	 * @param $psI18n - mixed
	 * @param $user - mixed
	 * @param $ps_album - mixed
	 * @return void
	 */
	protected function pushNotificationNewAlbum($psI18n, $user, $ps_album)
	{

		if ($ps_album->ps_class_id <= 0)
			return false;

		$result = array();

		// Lay danh sach nguoi than cua hoc sinh(nguoi bao tro chinh+nguoi dua don) trong lop hoc
		$notication_relatives = UserModel::getUserRelativeInfo($user->ps_customer_id, $ps_album->ps_class_id);

		$result['notication_relatives'] = $notication_relatives;

		$ps_member = PsMemberModel::getMember($user->member_id);

		// Lấy tên lớp của Album
		$class_name = '';
		if ($ps_album->ps_class_id > 0) {
			$my_class = $this->db->table(CONST_TBL_MYCLASS . ' as mc')->select('mc.name')->where('mc.id', $ps_album->ps_class_id)->get()->first();
			$class_name = $my_class ? $my_class->name : '';
		}

		$registrationIds_ios 		= array();

		$registrationIds_android 	= array();

		foreach ($notication_relatives as $relative) {
			if ($relative->notification_token != '') {
				if ($relative->osname == PS_CONST_PLATFORM_IOS)
					array_push($registrationIds_ios, $relative->notification_token);
				else
					array_push($registrationIds_android, $relative->notification_token);
			}
		}

		if (count($registrationIds_android) > 0 || count($registrationIds_ios) > 0) {

			$psI18n = new PsI18n($this->getUserLanguage($user));

			$notication_setting = new \stdClass();

			// Tieu de thong bao
			$notication_setting->title = $psI18n->__('Albums') . ': ' . $ps_album->title;

			if ($class_name != '')
				$notication_setting->subTitle = $psI18n->__('Of class') . ' ' . $class_name;
			else
				$notication_setting->subTitle = $psI18n->__('Teacher') . ' ' . $user->first_name . " " . $user->last_name;


			$notication_setting->tickerText = $psI18n->__('Albums') . ': ' . $ps_album->title;

			// Noi dung thong bao
			$notication_setting->message = $ps_album->note;

			$notication_setting->lights = '1';
			$notication_setting->vibrate = '1';
			$notication_setting->sound = '1';

			$notication_setting->smallIcon = IC_SMALL_NOTIFICATION;
			$notication_setting->smallIconOld = 'ic_small_notification_old';

			if ($ps_member->avatar != '') {
				$notication_setting->largeIcon = PsString::getUrlMediaAvatar($ps_member->cache_data, $ps_member->s_year_data, $ps_member->avatar, MEDIA_TYPE_TEACHER);
			} else
				$notication_setting->largeIcon  = PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;

			$notication_setting->screenCode 	= PS_CONST_SCREEN_ALBUMS;

			if ($user->username == 'demo03' || $user->username == 'nguyenphuong') {
				//$notication_setting->screenCode = PS_CONST_SCREEN_ALBUM_DETAIL;
			}

			$notication_setting->itemId 	= $ps_album->id;
			$notication_setting->clickUrl 	= '';

			// Deviceid registration firebase
			if ($registrationIds_ios > 0) {
				$notication_setting->registrationIds = $registrationIds_ios;
				$notification = new PsNotification($notication_setting);
				$result['IOS'] = $notification->pushNotification(PS_CONST_PLATFORM_IOS);
			}

			if ($registrationIds_android > 0) {
				$notication_setting->registrationIds = $registrationIds_android;
				$notification = new PsNotification($notication_setting);
				$result['ANDROID'] = $notification->pushNotification(PS_CONST_PLATFORM_ANDROID);
			}
		}

		return $result;
	}

	public function getFileNameByUrl($url_file)
	{

		$str = str_replace('%2F', '/', $url_file);

		return basename(parse_url($str)['path']);
	}

	/** Ham tra ve url file thumbail cua mot url file goc **/
	public function getUrlFileThumbailOfAblumItemFile($url_file, $path_album)
	{

		// Duong dan den thu muc anh
		$root_dirname 	= pathinfo($url_file, PATHINFO_DIRNAME);

		$url_format 	= rawurldecode($url_file);

		$parse_url 		= parse_url($url_format);

		$query_file 	= $parse_url['query'];

		// Ten file
		$name_file 		= basename($parse_url['path']);

		$url_thumbail 	= $root_dirname . '/' . rawurlencode($path_album . '/thumbail/') . $name_file . '?' . $query_file;

		return $url_thumbail;
	}

	public function likeToAlbum(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Like albums thành công'),
			'title' => $psI18n->__('Albums'),
			'data_info' => []
		);

		$body = $request->getParsedBody();

		$album_id = isset($body['info']['album_id']) ? PsString::trimString($body['info']['album_id']) : '';
		$likes = AlbumLikeModel::where('album_id', $album_id)->where('relative_id', $user->id)->get()->first();
		if ($album_id <= 0) {

			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => $psI18n->__('This content is no longer available.'),
				'title' => $psI18n->__('Likes'),
				'data_info' => []
			);
		} else {
			if (count($likes) == 0) {
				$likeToAlbum = new AlbumLikeModel();
				$likeToAlbum->album_id 		= $album_id;
				$likeToAlbum->number_like 		= '1';
				$likeToAlbum->relative_id = $user->id;
				$likeToAlbum->ps_customer_id = $user->ps_customer_id;
				$likeToAlbum->user_created_id = $user->id;
				$likeToAlbum->created_at 		= date('Y-m-d H:i:s');
				$likeToAlbum->updated_at 		= date('Y-m-d H:i:s');
				$likeToAlbum->save();
			} else {
				if ($likes->number_like == '1') {
					$likes->number_like = '0';
					$return_data = array(
						'_msg_code' => MSG_CODE_FALSE,
						'_msg_text' => $psI18n->__('Bỏ Like albums thành công'),
						'title' => $psI18n->__('Albums'),
						'data_info' => []
					);
				} else {
					$likes->number_like = '1';
				}
				$likes->save();
			}
		}

		$dem_like = AlbumLikeModel::where('album_id', $album_id)->where('number_like', '1')->get();

		$return_data['data_info']['Tong_like'] = count($dem_like);
		$sql = $this->db->table(TBL_PS_ALBUMS)->where('id', $album_id)->update([
			'number_like' => count($dem_like)
		]);

		$return_data['data_info']['Trang_thai'] = $likes->number_like;

		return $response->withJson($return_data);
	}

	public function showCommentToAlbum(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Comment albums'),
			'title' => $psI18n->__('Albums'),
			'data_info' => []
		);

		$queryParams = $request->getQueryParams();

		$album_id = isset($queryParams['album_id']) ? $queryParams['album_id'] : '';
		$comments = AlbumCommentModel::getCommentToAlbum($album_id);
		if ($album_id <= 0) {

			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => $psI18n->__('This content is no longer available.'),
				'title' => $psI18n->__('Comment'),
				'data_info' => []
			);
		} else {

			$data_list_comments = array();
			$dem_cmt = count($comments);
			foreach ($comments as $key => $comment) {
				$temp_album = new \stdClass();
				$temp_album->id = (int) $comment->id;
				$temp_album->relative_id = (int) $comment->relative_id;
				$temp_album->name = (string) $comment->first_name . ' ' . $comment->last_name;
				$temp_album->title = (string) $comment->title;

				array_push($data_list_comments, $temp_album);
			}
			$sql = $this->db->table(TBL_PS_ALBUMS)->where('id', $album_id)->update([
				'count_comment' => $dem_cmt
			]);
			$return_data['data_info'] = $data_list_comments;
			$return_data['tong_cmt'] = $dem_cmt;
		}


		return $response->withJson($return_data);
	}


	public function SaveComment(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Comment albums thành công'),
			'title' => $psI18n->__('Albums'),
			'data_info' => []
		);

		$body = $request->getParsedBody();

		$album_id = isset($body['info']['album_id']) ? PsString::trimString($body['info']['album_id']) : '';

		$title = isset($body['info']['title']) ? PsString::trimString($body['info']['title']) : '';
		$comment = AlbumCommentModel::where('album_id', $album_id)->get()->first();
		if ($album_id <= 0) {

			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => $psI18n->__('This content is no longer available.'),
				'title' => $psI18n->__('Comment'),
				'data_info' => []
			);
		} else {
			$commentToAlbum = new AlbumCommentModel();
			$commentToAlbum->album_id 		= $album_id;
			$commentToAlbum->title 		= $title;
			$commentToAlbum->relative_id = $user->id;
			$commentToAlbum->ps_customer_id = $user->ps_customer_id;
			$commentToAlbum->user_created_id = $user->id;
			$commentToAlbum->created_at 		= date('Y-m-d H:i:s');
			$commentToAlbum->updated_at 		= date('Y-m-d H:i:s');
			$commentToAlbum->save();
		}


		return $response->withJson($return_data);
	}



	public function deleteComment(RequestInterface $request, ResponseInterface $response, array $args)
	{
		//return "AAAA";
		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['_msg_text'] = $psI18n->__('Delete a photos failure.');

		if ($user->user_type == USER_TYPE_RELATIVE) {

			$return_data['_msg_text'] = $psI18n->__('You do not have access to this data');

			return $response->withJson($return_data);
		}



		$comment_id = $args['comment_id'];

		if ($comment_id <= 0)
			return $response->withJson($return_data);

		$comments = AlbumCommentModel::where('id', $comment_id)->get()->first();

		if (!$comments) {

			$return_data['_msg_text'] = $psI18n->__('This content is no longer available.');

			return $response->withJson($return_data);
		}


		if ($albumItem->is_activated == STATUS_LOCK) {

			$return_data['_msg_text'] = $psI18n->__('This content is locked due to policy violations');

			return $response->withJson($return_data);
		} else {

			try {

				AlbumCommentModel::beginTransaction();

				$album_id = $comments->album_id;

				//AlbumItemModel::deleteAlbumItem ( $img_id );

				if (AlbumCommentModel::where('id', '=', $comment_id)->delete()) {

					$album = AlbumModel::find($album_id);

					if ($album) {

						$return_data['_msg_code'] = MSG_CODE_TRUE;

						$return_data['_msg_text'] = $psI18n->__('Delete a photos successful.');
					}
				}

				AlbumItemModel::commit();
			} catch (Exception $e) {

				AlbumItemModel::rollBack();

				$return_data['_msg_code'] = MSG_CODE_FALSE;

				$return_data['_msg_text'] = $psI18n->__('Delete a photos failure.');
			}
		}

		return $response->withJson($return_data);
	}
}
