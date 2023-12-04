<?php

/**

 * @project Kidschool.vn

 *

 * @package APIs

 * @subpackage PsNotification

 *             @description

 * @author thangnc

 *        

 */

// Notification for firebase

class PsNotification
{



	protected $firebase_access_key = 'AAAANVlmJ1I:APA91bFz00evRVVKsGkc5mnljjfePNheunZ7OgRvfG1aMPScWZYQlCmRqplhty-WueKGry-rd75PPYGAcxgyfFBrBgms741ondUTocUx4ys5l-T2OAaK72OzJg06PftcPN1B3R8HiGSW';



	protected $firebase_project_id = 'kidkun-3a8a4';



	protected $firebase_url = 'https://fcm.googleapis.com/fcm/send';



	protected $googleapis_url = 'https://iid.googleapis.com/iid';



	public function __construct($setting)
	{



		$this->setHeader();



		$this->setTitle($setting->title);



		$this->setSubTitle($setting->subTitle);



		$this->setMessage($setting->message);



		$this->setTickerText($setting->tickerText);



		$this->setLights($setting->lights);



		$this->setVibrate($setting->vibrate);



		$this->setSound($setting->sound);



		$this->setLargeIcon($setting->largeIcon);



		$this->setSmallIcon($setting->smallIcon);



		$this->setScreenCode($setting->screenCode);



		$this->setItemId($setting->itemId);



		$this->setStudentId(isset($setting->studentId) ? $setting->studentId : 0);



		$this->setClickUrl($setting->clickUrl);



		$this->setRegistrationIds($setting->registrationIds);



		$this->setTopics(isset($setting->topics) ? $setting->topics : null);



		$this->setContent();
	}



	public function setHeader()
	{



		$this->headers = array(

			'Authorization: key=' . $this->firebase_access_key,

			'Content-Type: application/json'

		);
	}



	public function setTitle($title)
	{



		$this->title = $title;
	}



	public function setSubTitle($subTitle)
	{



		$this->subTitle = $subTitle;
	}



	public function setMessage($message)
	{



		$this->message = $message;
	}



	public function setTickerText($tickerText)
	{



		$this->tickerText = $tickerText;
	}



	public function setLights($lights)
	{



		$this->lights = $lights;
	}



	public function setVibrate($vibrate)
	{



		$this->vibrate = $vibrate;
	}



	public function setSound($sound)
	{



		$this->sound = $sound;
	}



	public function setLargeIcon($largeIcon)
	{



		$this->largeIcon = $largeIcon;
	}



	public function setSmallIcon($smallIcon)
	{



		$this->smallIcon = $smallIcon;
	}



	public function setScreenCode($screenCode)
	{



		$this->screenCode = $screenCode;
	}



	public function setItemId($itemId)
	{



		$this->itemId = $itemId;
	}



	public function setStudentId($studentId)
	{



		$this->studentId = $studentId;
	}



	public function setContent()
	{



		$this->content = array(

			'body' => $this->getMessage(),

			'title' => $this->getTitle(),

			'subtitle' => $this->getSubTitle(),

			'tickerText' => $this->getTickerText(),

			'lights' => $this->getLights(),

			'vibrate' => $this->getVibrate(),

			'sound' => $this->getSound(),

			'largeIcon' => $this->getLargeIcon(),

			'smallIcon' => $this->getSmallIcon(),

			// 'smallIconOld' => $this->getSmallIconOld(),

			'screenCode' => $this->getScreenCode(),

			'itemId' => $this->getItemId(),

			'studentId' => (int) $this->getStudentId(),

			'clickUrl' => $this->getClickUrl()

		);
	}



	public function setRegistrationIds($registrationIds)
	{



		$this->registrationIds = $registrationIds;
	}



	public function setTopics($topics)
	{



		$this->topics = $topics;
	}



	public function setClickUrl($clickUrl)
	{



		$this->clickUrl = $clickUrl;
	}



	public function getHeader()
	{



		return $this->headers;
	}



	public function getTitle()
	{



		return $this->title;
	}



	public function getSubTitle()
	{



		return $this->subTitle;
	}



	public function getMessage()
	{



		return $this->message;
	}



	public function getTickerText()
	{



		return $this->tickerText;
	}



	public function getLights()
	{



		return $this->lights;
	}



	public function getVibrate()
	{



		return $this->vibrate;
	}



	public function getSound()
	{



		return $this->sound;
	}



	public function getSmallIcon()
	{



		return $this->smallIcon;
	}



	public function getLargeIcon()
	{



		return $this->largeIcon;
	}



	public function getScreenCode()
	{



		return $this->screenCode;
	}



	public function getItemId()
	{



		return $this->itemId;
	}



	public function getStudentId()
	{



		return $this->studentId;
	}



	public function getClickUrl()
	{



		return $this->clickUrl;
	}



	public function getContent()
	{



		return $this->content;
	}



	/**

	 *

	 * @return array()

	 */

	public function getRegistrationIds()
	{



		return $this->registrationIds;
	}



	/**

	 */

	public function getTopics()
	{



		return $this->topics;
	}



	/**

	 * For Android OS

	 */

	public function setAndroidNotification()
	{



		$fields = array();



		$data = array(

			'body' => $this->getMessage(),

			'title' => $this->getTitle(),

			'subtitle' => $this->getSubTitle(),

			'tickerText' => $this->getTickerText(),

			'lights' => $this->getLights(),

			'vibrate' => $this->getVibrate(),

			'sound' => $this->getSound(),

			'largeIcon' => $this->getLargeIcon(),

			'' => $this->getSmallIcon(),

			// 'smallIconOld' => $this->getSmallIconOld(),

			'screenCode' => $this->getScreenCode(),

			'itemId' => (int) $this->getItemId(),

			'studentId' => (int) $this->getStudentId(),

			'clickUrl' => $this->getClickUrl()

		);

		$fields['notification'] = $data;

		return $fields;
	}



	/**

	 * For iOS OS

	 */

	public function setIosNotification()
	{



		$fields = array();



		$fields['notification'] = array(

			'body' => $this->getMessage(),

			'title' => $this->getTitle(),

			'subtitle' => $this->getSubTitle(),

			'sound' => 'default',

			'badge' => '1',

			'priority' => 'high'

		);



		$data = array(

			// 'subtitle' => $this->getSubTitle(),

			'largeIcon' => $this->getLargeIcon(),

			'smallIcon' => $this->getSmallIcon(),

			'screenCode' => $this->getScreenCode(),

			'itemId' => (int) $this->getItemId(),

			'studentId' => (int) $this->getStudentId(),

			'clickUrl' => $this->getClickUrl()

		);



		$fields['data'] = array(

			'data' => $data

		);



		$fields['priority'] = 'high';

		$fields['badge'] = 1;

		$fields['sound'] = $this->getSound();

		$fields['mutable_content'] = true;



		$fields['apns'] = array(

			'headers' => 'apns-priority',

			'payload' => array(

				'badge' => '1',

				'sound' => 'default',

				'aps' => array(

					'badge' => '1',

					'sound' => 'default'

				)

			)

		);



		return $fields;
	}



	/**

	 * Dang ky mot topics 

	 **/

	public function addTopic()
	{



		$fields = array();



		$url_add_topics = $this->googleapis_url . '/v1:batchAdd';



		if (is_array($this->registrationIds)) {



			$fields['registration_ids'] = $this->registrationIds;
		} elseif ($this->registrationIds != '') {



			$fields['registration_ids'] = $this->registrationIds;
		}



		if ($this->topics != '') {

			$fields['to'] = '/topics/' . $this->topics;
		}



		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url_add_topics);

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		$result = curl_exec($ch);



		curl_close($ch);



		return $result;
	}



	/**

	 * Xoa mot topics

	 **/

	public function removeTopic()
	{



		$fields = array();



		$url_add_topics = $this->googleapis_url . '/v1:batchRemove';



		if (is_array($this->registrationIds)) {



			$fields['registration_ids'] = $this->registrationIds;
		} elseif ($this->registrationIds != '') {



			$fields['registration_ids'] = $this->registrationIds;
		}



		if ($this->topics != '') {

			$fields['to'] = '/topics/' . $this->topics;
		}



		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url_add_topics);

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		$result = curl_exec($ch);



		curl_close($ch);



		return $result;
	}



	// push notification theo he dieu hanh

	public function pushNotification($os = null)
	{

		//$firebaseToken = 'cZvn34KlRgWeF7I61Gy_sm:APA91bFmKV_h9-NA6LvgPGtCQW8nO4AmOXs4Z7SlPPxY64hCKoagLix7-ffIOYidHT3R6yATvm71EzJ5qhlENNpdu5WXAexvXHGEuerp75kmBkNATNXYudFamlQ1IAv6o5oNH5MvI2sg';

		//return  $firebaseToken;
		//$Token[] = $firebaseToken;
	     //return  $Token;
		// $SERVER_API_KEY = 'AAAABofxjHw:APA91bE0JCPt37TD9Gvt5Kg9Ugx5A8ikOJWAmoSZ4GR_BU-qBg9IYBljD3e8JIPkaHiHvdySlpQwTet6qrJF_rINJkdP_5HxjfHU4WNiLnvQNRV0LrzBwj3Uym8SpNvOGC02yjH-WJU9';

		// $data = [
		// 	"registration_ids" => $Token,
		// 	"notification" => [
		// 		"title" => "thông báo",
		// 		"body" => "giang dep trai",
		// 	]
		// ]; 



		// $dataString = json_encode($data);

		// // print_r($dataString);die;

		// $headers = [
		// 	'Authorization: key=' . $SERVER_API_KEY,
		// 	'Content-Type: application/json',
		// ];

		// $ch = curl_init();

		// curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		// curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        
  //       //print_r($headers);die;
		// $response = curl_exec($ch);
  //        print_r($response); die;
		// return $response;


		
		$fields = array();

		if ($os == 'IOS') {

			$fields = $this->setIosNotification();
		} else {

			$fields = $this->setAndroidNotification();
		}



		if ($this->topics != '') {

			$fields['to'] = '/topics/' . $this->topics;
		} else {

			if (is_array($this->registrationIds)) {

				$fields['registration_ids'] = $this->registrationIds;
			} elseif ($this->registrationIds != '') {



				$fields['registration_ids'] = $this->registrationIds;
			}
		}
        
        $dataStrings = json_encode($fields);
       
        //print_r($dataStrings); die;
         
		$ch = curl_init();



		curl_setopt($ch, CURLOPT_URL, $this->firebase_url);

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStrings);

		$result = curl_exec($ch);

		curl_close($ch);

		//print_r($result); die;

		return $result;
		
	}
}
