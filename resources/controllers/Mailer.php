<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 6/10/17
 * Time: 01:15 PM
 */

namespace Com\Detalhe\Core\Controllers;

use Themosis\Route\BaseController;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Mailer. Handler controller for the mailing functionality.
 *
 * @since       1.0.0
 * @author      Yamil Elías <yamil@wtf.style>
 * @package     Com\Detalhe\Core\Controllers
 */
class Mailer extends BaseController
{
    /**
     * PHPMailer object
     * @var null
     */
    var $mail = null;

    /**
     * Mailer constructor.
     */
    function __construct() {
        $this->mail = new PHPMailer();
    }

    /**
     * Receives information in order to send a mail to the selected subject.
     *
     * @since 1.0.0
     * @param string $recipient_address
     * @param string $recipient_name
     * @param string $message
     * @param string $reply_to
     */
    public function send_mail($recipient_address = '', $recipient_name = '', $message = '', $reply_to = ''){
        $this->mail;

        //From email address and name
        $this->mail->From = "from@yourdomain.com";
        $this->mail->FromName = "Full Name";

        //To address and name
        $this->mail->addAddress("yamileliassoto@gmail.com", "Recepient Name");
        $this->mail->addAddress("yamileliassoto@gmail.com"); //Recipient name is optional

        //Address to which recipient will reply
        $this->mail->addReplyTo("reply@yourdomain.com", "Reply");

        $this->mail->Subject = "Subject Text";
        $this->mail->Body = "<i>Mail body in HTML</i>";
        $this->mail->AltBody = "This is the plain text version of the email content";

        if(!$this->mail->send())
        {
            echo "Mailer Error: " . $this->mail->ErrorInfo;
        }
        else
        {
            echo "Message has been sent successfully";
        }
    }

    /**
     * Set mail configuration.
     *
     * @since 1.0.0
     * @param PHPMailer $mail
     * @param bool $html
     * @param bool $smtp
     */
    private function configure_mail($mail, $html = false, $smtp = false){
        //Send HTML or Plain Text email
        $mail->isHTML($html);

        if($smtp){
            $this->configure_smtp($mail);
        }
    }

    /**
     * Configure SMTP options for mailing.
     * TODO: Add the configuration by variables and not hardcoded
     *
     * @since 1.0.0
     * @param PHPMailer $mail
     */
    private function configure_smtp($mail){
        //Enable SMTP debugging.
        $mail->SMTPDebug = 3;

        //Set PHPMailer to use SMTP.
        $mail->isSMTP();

        //Set SMTP host name
        $mail->Host = "smtp.gmail.com";

        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;

        //Provide username and password
        $mail->Username = "name@gmail.com";
        $mail->Password = "super_secret_password";

        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";

        //Set TCP port to connect to
        $mail->Port = 587;
    }

    /**
     * Add all the needed attachments for the mail.
     *
     * @since 1.0.0
     * @param PHPMailer $mail
     * @param array $attachments
     */
    private function add_attachments($mail, $attachments = array()) {
        foreach($attachments as $attachment){
            // TODO: Add functionality to customize the names
            $mail->addAttachment($attachment);
        }
    }
}