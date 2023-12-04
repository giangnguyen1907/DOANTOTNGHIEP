<?php
/**
* @package          truongnet.com
* @subpackage       API app 
*
* @file PsMailer.php
* @author thangnc<thangnc@newwaytech.vn>
* @version 1.0 27-02-2017 -  00:51:34
*/
namespace App\PsMail;

class PsMailer {
    
    protected $renderer;
    
    protected $mailer;

    protected $setting;

    /**
     * The From email address and name for the message.
     * @var array
     */
    public $From = "array('a@domain.com' => 'A')";

    /**
     * The member  email address and name for the message.
     * 
     * array(
     *  'receiver@bad-domain.org'       => 'Receiver Name',
     *  'other@domain.org'              => 'A name',
     *  'other-receiver@bad-domain.org' => 'Other Name')
     *
     * @var array
     */
    public $To = "array('a@domain.com' => 'A')";

    /**
     * The Subject of the message.
     * @var string
     */
    public $Subject = '';

    /**
     * An HTML or plain text message body.
     * If HTML then call isHTML(true).
     * @var string
     */
    public $Body = '';

    
    public function __construct($view, $setting)
    {
        $this->view         = $view;
        $this->setting      = $setting;
    }

    
    public function setSubject($Subject) {
        $this->Subject = $Subject;        
    }


    public function setFrom($From) {
        $this->From = $From;        
    }

    public function setTo($To) {
        $this->To = $To;        
    }

    public function setBody($template, $data = []) {
        
       $this->Body = $this->view->fetch($this->setting->path_template . $template, $data);
         
    }
    
    public function sendMail() {
        
        require_once APP_ROOT . '/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

        // Create the Transport
        $transport = \Swift_SmtpTransport::newInstance($this->setting->Host, $this->setting->Port, $this->setting->SMTPSecure)
          ->setUsername($this->setting->Username)
          ->setPassword($this->setting->Password);

        $mailer = \Swift_Mailer::newInstance($transport);

        // Setting all needed info and passing in my email template.
        $message = \Swift_Message::newInstance($this->Subject)
                    ->setFrom($this->From)
                    ->setTo($this->To)
                    ->setBody($this->Body)
                    ->setContentType($this->setting->ContentType);

        return $mailer->send($message);
    }
}