<?php
namespace igblog\mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use igblog\fonctions;
use igblog\storage;

class mail{
	private $config;
	private $mailInfo;
	private $mailA;
	private $mailS;
	private $template;

	public function __construct($mailInfo,$template='contact'){
		$this->mailInfo=$mailInfo;
		$this->template=$template;
		require fonctions::getConfFile("mail");
		$this->config=$config;

		$this->mailA=new PHPMailer(true);
		$this->mailS=new PHPMailer(true);
		if($this->config['debug']){
			$this->mailA->SMTPDebug = 2;
			$this->mailS->SMTPDebug = 2;
		}

		if($this->config['smtp']['enable'])
			$this->smtpConnect();

		$this->mailA->isHTML(true);
		$this->mailS->isHTML(true); 
		$this->toAdmin();
		$this->toSender();
	}

	public function sendNews(){
		$this->mailS->send();
	}

	public function sendAdmin(){
		$this->mailA->send();
	}

	
	public function send(){
		$uuid=fonctions::genUUID();
		$date=date("Y-m-d H:i:s",time());
		$this->mailInfo['sendDate']=$date;
		$this->mailInfo['uuid']=$uuid;

		$storage= new storage\storage();
		$storage->call("setMail",$this->mailInfo,$uuid);
		$this->mailA->send();
		$this->mailS->send();
	}

	private function toAdmin(){
		require fonctions::getConfFile("config");
		$this->mailA->setFrom($this->config['from'],utf8_decode($this->config['fromName']));
		$this->mailA->addReplyTo($this->mailInfo['mail'],utf8_decode($this->mailInfo['givenname'].' '.$this->mailInfo['sn']));
		foreach($config['contact'] as $mail){
			$this->mailA->addAddress($mail);
		}
		$subject = $this->config['subjectAdmin'];
		if(isset($this->mailInfo['subject']))
			$subject .= ' '.$this->mailInfo['subject'];
		$this->mailA->Subject=utf8_decode($subject);

		$t=new template();
		try{
			$mailText=$t->show($this->template.'Admin',array("mailInfo"=>$this->mailInfo));
		}catch(\Exception $e){
			$mailText = "Erreur";
		}
		$this->mailA->Body=$mailText;
	}

	private function toSender(){
		$this->mailS->setFrom($this->config['from'],utf8_decode($this->config['fromName']));
		$this->mailS->addAddress($this->mailInfo['mail'], utf8_decode($this->mailInfo['givenname'].' '.$this->mailInfo['sn']));
		$this->mailS->Subject=utf8_decode($this->config['subjectSender']);
		$t=new template();
		$mailText=$t->show($this->template.'Sender',array("mailInfo"=>$this->mailInfo));
		$this->mailS->Body=$mailText;
	}

	private function smtpConnect(){
		$this->mailA=$this->_smtpConnect($this->mailA);
		$this->mailS=$this->_smtpConnect($this->mailS);
	}
	private function _smtpConnect($mail){
		$mail->isSMTP();
		$mail->Host = $this->config['smtp']['host'];
		if($this->config['smtp']['username']){
			$mail->SMTPAuth = true;
			$mail->Username = $this->config['smtp']['username'];
			if($this->config['smtp']['password'])
				$mail->Password = $this->config['smtp']['password'];
		}
		$mail->SMTPAutoTLS=false;

		if($this->config['smtp']['tls']){
			$mail->SMTPAutoTLS=true;
			$mail->SMTPSecure = 'tls';
		}
		if($this->config['smtp']['ssl'])
			$mail->SMTPSecure = 'ssl';

		if($this->config['smtp']['port'])
			$mail->Port = $this->config['smtp']['port'];

		return $mail;
	}
}

?>
