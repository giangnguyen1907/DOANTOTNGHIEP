<?php
/**
* @project_name
* @subpackage     interpreter 
*
* @file PsI18n.php
* @filecomment filecomment
* @package_declaration package_declaration
* 
* @author thangnc@newwaytech.vn
* @version 1.0 29-04-2017 -  17:18:37
*/

namespace App\PsUtil;

// Use the ridiculously long Symfony namespaces
//use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

class PsI18n extends Translator {
	
	public $translator;
	
	public function __construct($user_lang, MessageSelector $selector = null, $cacheDir = null, $debug = false) {
		
		$config_langs = array('vi' => 'vi_VN', 'en' => 'en_GB');
		
		if ($user_lang == 'VN')
			$user_lang = 'vi';
		
		$user_lang   = mb_strtolower($user_lang);
		
		// Set a fallback language incase you don't have a translation in the default language		
		$fallbackLocales = isset($config_langs[$user_lang]) ? $config_langs[$user_lang] : $config_langs['vi'];
		
		// First param is the "default language" to use.
		$this->translator = new Translator($fallbackLocales, new MessageSelector());		
		
		$this->translator->setFallbackLocales([$fallbackLocales]);
		
		// Add a loader that will get the php files we are going to store our translations in
		$this->translator->addLoader('php', new PhpFileLoader());
		
		// Add language files
		$this->translator->addResource('php', APP_ROOT.'/lang/vi_VN.php', 'vi_VN'); // Viet Nam
		$this->translator->addResource('php', APP_ROOT.'/lang/en_GB.php', 'en_GB'); // English
	}
	
	/**
	 * @author thangnc@newwaytech.vn 
	 **/
	public function __($id, array $parameters = array(), $domain = null, $locale = null) {
		return $this->translator->trans($id, $parameters, $domain, $locale);
	}
}
