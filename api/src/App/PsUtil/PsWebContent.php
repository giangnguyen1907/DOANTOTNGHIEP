<?php

namespace App\PsUtil;

class PsWebContent {
	public static $color_light_red = 'rgb(255, 125, 125)';
	public static $color_light_yellow = 'rgb(255, 248, 175)';
	public static $color_light_orange = 'rgb(248, 169, 128)';
	public static $color_light_grey = '#f1f1f1';
	public static $color_grey = '#9e9e9e';
	public static $color_pink = '#e91e63';

	/**
	 * Head for page HTML
	 */
	public static function BeginHTMLPage($background_color) {
		$top_html_page = '<!DOCTYPE html><html><head>';
		$top_html_page .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		$top_html_page .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/css/w3.css">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/css/v2_kidsschool.css">';
		//$top_html_page .= '<link rel="stylesheet" href="' . PS_CONST_URL_APIS . '/v2/layout/mobile/fontawesome/css/all.css">';
		if ($background_color != '')
			$top_html_page .= '</head><body style="background-color:' . $background_color . '!important;padding:0px;margin:0px;">';
		else
			$top_html_page .= '</head><body>';

		return $top_html_page;
	}

	/**
	 * Head for page HTML, sử dụng Chart.js
	 */
	public static function BeginHTMLPageChart() {
		$top_html_page = '<!DOCTYPE html><html><head>';
		$top_html_page .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		$top_html_page .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/css/w3.css">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/css/v2_kidsschool.css">';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/chart/Chart.min.js"></script>';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/chart/utils.js"></script>';
		$top_html_page .= '<style>canvas{-moz-user-select: none;-webkit-user-select: none;-ms-user-select: none;}</style>';
		$top_html_page .= '<style type="text/css">@keyframes chartjs-render-animation{from{opacity:.99}to{opacity:1}}.chartjs-render-monitor{animation:chartjs-render-animation 1ms}.chartjs-size-monitor,.chartjs-size-monitor-expand,.chartjs-size-monitor-shrink{position:absolute;direction:ltr;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1}.chartjs-size-monitor-expand>div{position:absolute;width:1000000px;height:1000000px;left:0;top:0}.chartjs-size-monitor-shrink>div{position:absolute;width:200%;height:200%;left:0;top:0}</style>';
		$top_html_page .= '</head><body>';

		return $top_html_page;
	}
	public static function EndHTMLPage() {
		$end_html_page = '</body></html>';

		return $end_html_page;
	}

	/**
	 * Khung biểu đồ
	 *
	 * @param $width: %
	 *        	độ lớn ngang của khung *
	 */
	public static function ChartCanvas($width = '85%') {
		$end_html_page = '<div style="' . $width . ';"><canvas id="canvas" style="width:100%;height: 270px;"></canvas></div>';
		return $end_html_page;
	}

	/** Khung biểu đồ BMI theo giới tính **/
	public static function ChartCanvasPsBMI($_bmi_data_chart, $_data_student_growths_chart, $top_lable, $_x_lable, $_y_lable, $start_x) {

		// Trục x thể hiện tháng tuổi
		$_x_month = "'" . implode ( "'" . "," . "'", $_bmi_data_chart ['index_x_month'] ) . "'";

		$_data_min_3SD = implode ( ",", $_bmi_data_chart ['index_min_3SD'] );

		$_data_max_3SD = implode ( ",", $_bmi_data_chart ['index_max_3SD'] );

		$_data_min_2SD = implode ( ",", $_bmi_data_chart ['index_min_2SD'] );

		$_data_max_2SD = implode ( ",", $_bmi_data_chart ['index_max_2SD'] );

		$_data_medium_height = implode ( ",", $_bmi_data_chart ['index_medium'] );

		$_data_height_student_growths_chart = implode ( ",", $_data_student_growths_chart );

		$end_html_page = "<script>		
		var scatterChartData = {
			labels: [" . $_x_month . "],
			backgroundColor: '#87CEEB',
			datasets: [
					{
						label: 'Đường của bé',
						backgroundColor: '#2196F3',
						borderColor: '#2196F3',
						borderWidth: 1,
						lineWidth:1,
						pointRadius: 3,
						pointBackgroundColor: '#2196F3',
						showLine: true,
						data: [
							" . $_data_height_student_growths_chart . "
						],
						fill: false,
					},
					{
						label: 'Thấp còi độ 2',
						backgroundColor: 'rgb(255, 125, 125)',
						borderColor: window.chartColors.grey,
						borderWidth: 1,
						lineWidth:1,
						type: 'line',
						showLine: true,
						data: [
							" . $_data_min_3SD . "
						],
						fill: true,
					},
					{
						label: 'Cao hơn so với tuổi',
						backgroundColor: 'rgb(255, 248, 175)',
						borderColor: window.chartColors.grey,
						borderWidth: 1,
						lineWidth:1,
						type: 'line',
						showLine: true,
						data: [
							" . $_data_max_3SD . "
						],
						fill: 'top',
					},
					{
						label: 'Thấp còi độ 1',
						backgroundColor: 'rgb(248, 169, 128)',
						borderColor: '#e91e63',
						borderWidth: 1,
						lineWidth:1,
						type: 'line',
						showLine: true,
						data: [
							" . $_data_min_2SD . "
						],
						fill: 'bottom',
					},
					{
						label: '',
						backgroundColor: 'rgb(255, 248, 175)',
						borderColor: '#e91e63',
						borderWidth: 1,
						lineWidth:1,
						type: 'line',
						showLine: true,
						data: [
							" . $_data_max_2SD . "
						],
						fill: 'top',
					},
					{
						label: 'Duong chuan',
						backgroundColor: '#C6F1FF',
						borderColor: '#4CAF50',
						borderWidth: 1,
						lineWidth:0,
						type: 'line',
						pointRadius:0,
						showLine: true,
						data: [
							" . $_data_medium_height . "
						],
						fill: 'top',
					},
					{
						label: 'Duong chuan',
						backgroundColor: '#C6F1FF',
						borderWidth: 1,
						lineWidth:0,
						type: 'line',
						pointRadius:0,
						showLine: true,
						data: [
							" . $_data_medium_height . "
						],
						fill: 'bottom',
					}
			   ]	
		};
		window.onload = function() {
				var ctx = document.getElementById('canvas').getContext('2d');
				window.myScatter = Chart.Scatter(ctx, {
					data: scatterChartData ,
					options: {
						legend: {
				            display: false
				        },
						title: {
							display: true,
							text: '.$top_lable.'
						},
						scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: '" . $_x_lable . "'
						},
						ticks: {
					        min: " . $start_x . "
					    },
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: '" . $_y_lable . "'
						}
					}]
				}
					}
				});
			};
	</script>";
		return $end_html_page;
	}

	/**
	 * Head for page HTML
	 */
	public static function BeginHTMLPageCalendar($background_color = '') {
		
		$top_html_page = '<!DOCTYPE html><html><head>';
		$top_html_page .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		$top_html_page .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/bootstrap/css/bootstrap.min.css">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/css/v2_kidsschool.css">';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/jquery/jquery-3.3.1.slim.min.js"></script>';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/bootstrap/js/bootstrap.min.js"></script>';		
		/*
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/jsCalendar/jsCalendar.css">';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/jsCalendar/jsCalendar.js"></script>';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/jsCalendar/jsCalendar.lang.vi.js"></script>';
		
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/event-calendar/jquery.simple-calendar.js"></script>';
		*/		
		if ($background_color != '')
			$top_html_page .= '</head><body style="background-color:' . $background_color . '!important;padding:0px;margin:0px;">';
			else
				$top_html_page .= '</head><body>';
				
				return $top_html_page;
	}
	
	/**
	 * Head for page HTML
	*/
	public static function BeginHTMLPageBootstrap($background_color = '') {
		
		$top_html_page = '<!DOCTYPE html><html><head>';
		$top_html_page .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		$top_html_page .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3.0, minimum-scale=1, user-scalable=yes">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/bootstrap/css/bootstrap.min.css">';
		//$top_html_page .= '<link rel="stylesheet" href="' . PS_CONST_URL_APIS . '/v2/layout/mobile/fontawesome/css/all.min.css">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/css/v2_kidsschool.css">';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/jquery/jquery-3.3.1.slim.min.js"></script>';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/bootstrap/js/bootstrap.min.js"></script>';
				
		if ($background_color != '')
			$top_html_page .= '</head><body style="background-color:' . $background_color . '!important;padding:0px;margin:0px;">';
		else
			$top_html_page .= '</head><body>';
		
		return $top_html_page;
	}
	
	/**
	 * Head for page HTML
	 */
	public static function BeginHTMLPageBootstrapForSchedule() {
		
		$top_html_page = '<!DOCTYPE html><html><head>';
		$top_html_page .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		$top_html_page .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">';
		$top_html_page .= '<link type="text/css" rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/bootstrap/css/bootstrap.min.css">';		
		$top_html_page .= '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">';		
		$top_html_page .= '<link rel="stylesheet" href="' . PS_URL_LIB_MEDIA . '/layout/mobile/css/v2_kidsschool.css">';		
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/jquery/jquery-3.3.1.slim.min.js"></script>';
		$top_html_page .= '<script src="' . PS_URL_LIB_MEDIA . '/layout/mobile/bootstrap/js/bootstrap.min.js"></script>';
		//$top_html_page .= '<style>body.no-scroll, html.no-scroll {overflow: hidden;height: 100%;touch-action: none;}body.modal-open {position: fixed;}</style></head><body>';
		$top_html_page .= '</head><body style="padding:0px;margin:0px;">';
		return $top_html_page;
	}
	
	/**
	 * Head for page HTML
	**/
	public static function modalPage($color ='green',$id, $text) {
		
		$tag_html = '<div id="'.$id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-scrollable " role="document">
					    <div class="modal-content">
							<div class="modal-header">
					       		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true" style="font-size: 32px" class="ks-text-'.$color.'">&times;</span>
					        	</button>
					      	</div>
							 <div class="modal-body">
						     '.$text.'
						    </div>
						    <div class="modal-footer">
						    </div>
					     </div>
					  </div>
					</div>';		
		return $tag_html;
	}

	/**
	 * format style title content album
	 */
	public static function styleContentTextListAlbum($text) {
		return '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1"></head><body style="margin:0 auto;"><div style="font-size:14px;padding-top:8px;">' . $text . '</div></body></html>';
	}
	
	/**
	 * format style title content album
	 */
	public static function styleContentTextDetailAlbum($text) {
		return '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1"></head><body style="margin:0 auto;background-color:transparent; margin-top:7px;margin-bottom:7px;"><div style="font-size:14px;padding:8px 5px 5px 5px;text-align:justify;">' . $text . '</div></body></html>';
	}
	
	/**
	 * format style title content album
	 */
	public static function styleContentTextDetailItem($text) {
		return '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1"></head><style type="text/css">a:link,a:visited,a:hover { color:#fff; text-decoration: none; }</style><body style="margin:0 auto;background-color:#000; color:#fff; margin-top:7px;margin-bottom:7px;"><div style="font-size:14px;padding-top:8px;padding:8px 5px 8px 5px;text-align: justify;"><small>' . $text . '</small></div></body></html>';
	}
}