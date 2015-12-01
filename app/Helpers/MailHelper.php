<?php

namespace App\Helpers;
use Response;
use App\Models\NewsletterEmail;

use Mail;

class MailHelper{
	
	public $to;
	public $email_subject;

	function order($data, $template){
		$this->to = $data['order']['email'];
		$this->email_subject = "Order ".$data['order']['invoice_no'];
		$mail = Mail::send($template,$data, function($message)
		{
		  $message->to($this->to)->subject($this->email_subject);
		});

		if($mail){
			return true;
		}else{
			return false;
		}
	}
	
	function registerInvitation($data) {
      $this->to = $data['to'];
      $this->email_subject = $data['subject'];

      $mail = Mail::send('emails.register_invitation', $data, function($message) {
                  $message->to($this->to)->subject($this->email_subject);
              });

      if ($mail) {
          return true;
      } else {
          return false;
      }
  }

  public function newsletter($mail) {
        # get all email from email newsletter
        $mail_count = 0;
        
        $this->email_subject = $mail['subject'];
        $NewsletterEmail = new NewsletterEmail;
        $newsletter_data = $NewsletterEmail->query()->get();
        if ($newsletter_data) {
            foreach ($newsletter_data as $data) {
                $mail_body = $mail['body'];
                $unsubscribe_token = $data->unsubscribe_token;
                $mail_body = str_replace('%unsubscribe_token%', $unsubscribe_token, $mail_body); //insert unsubscribe_link
                
                $this->to = $data['email'];
                $mailQueue = Mail::queue(['html' => 'emails.newsletter'], ['email_content' => $mail_body], function($message) {
                    $message->to($this->to)->subject($this->email_subject);
                });
                
                $mail_count++;
            }
        }
        
        return $mail_count;
  }
}