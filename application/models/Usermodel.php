<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// use Dotenv\Dotenv;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions

require 'application/third_party/PHPMailer/src/Exception.php';
require 'application/third_party/PHPMailer/src/PHPMailer.php';
require 'application/third_party/PHPMailer/src/SMTP.php';

require_once "vendor/autoload.php";
// Dotenv::load(__DIR__);

class Usermodel extends CI_Model {



    
    function __construct(){
        // Call the Model constructor
        parent::__construct();        
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
    }    
    
    

    //funtion to get email of user to send password
    public function ForgotPassword($email)
    {
        $this->db->select('email');
        $this->db->from('users'); 
        $this->db->where('email', $email); 
        $query=$this->db->get();
        return $query->row_array();
    }

    public function sendpassword($data)
    {
        $email = $data['email'];
        $query1=$this->db->query("SELECT *  from users where email = '".$email."' ");
        $row=$query1->result_array();
        if ($query1->num_rows()>0)
          
        {
            $passwordplain = "";
            $passwordplain  = rand(999999999,9999999999);
            $newpass['password'] = md5($passwordplain);
            $this->db->where('email', $email);
            $this->db->update('users', $newpass); 
            $mail_message='Dear '.$row[0]['first_name'].','. "\r\n";
            $mail_message.='Thanks for contacting regarding to forgot password,<br> Your <b>Password</b> is <b>'.$passwordplain.'</b>'."\r\n";
            $mail_message.='<br>Please Update your password.';
            $mail_message.='<br>Thanks & Regards';
            $mail_message.='<br>Your company name';        
            date_default_timezone_set('Etc/UTC');
            // require FCPATH.'assets/PHPMailer/PHPMailerAutoload.php';
            // $mail = new PHPMailer;

            $mail = new PHPMailer(true);
            $mail->isSMTP();

            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);

            $mail->SMTPSecure = "tls"; 
            $mail->Debugoutput = 'html';
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->SMTPAuth = true;   
            $mail->Username = $this->config->item('SMTP_USERNAME');   
            $mail->Password = $this->config->item('SMTP_PASSWORD');
            $mail->setFrom('admin@mail.com', 'admin');
            $mail->IsHTML(true);
            $mail->addAddress($email);
            $mail->Subject = 'RESET PASSWORD';
            $mail->Body    = $mail_message;
            $mail->AltBody = $mail_message;


            // if (!$mail->send()) {
            //      $this->session->set_flashdata('msg','Failed to send password, please try again!');
            // } else {
            //    $this->session->set_flashdata('msg','Password sent to your email!');
            // }
            // redirect(base_url().'main','refresh'); 
            
            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message sent!';
                //Section 2: IMAP
                //Uncomment these to save your message in the 'Sent Mail' folder.
                #if (save_mail($mail)) {
                #    echo "Message saved!";
                #}
            }
        }
        else
        {  
         $this->session->set_flashdata('msg','Email not found try again!');
         redirect(base_url().'user/Login','refresh');
        }
    }
    
}
