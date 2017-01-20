<?php

namespace App\Helpers;

class Mailer 
{
	protected $mailer;
	protected $view;
	protected static $instance = null;

	public function __construct($mailer,$view){
		$this->mailer = $mailer;
		$this->view = $view;
	}

	public static function getInstance($mailer,$view){
		if(!self::$instance){
			self::$instance = new Mailer($mailer,$view);
		}
		return self::$instance;
	}

	public function send($mailHeader,$template,$data){

		$this->mailer->from = '***REMOVED***'; 
		$this->mailer->addAddress($mailHeader['to']);
		$this->mailer->Subject = $mailHeader['Subject'];
		$this->mailer->Body = $this->view->fetch($template,$data);

		$this->mailer->send();
	}
}