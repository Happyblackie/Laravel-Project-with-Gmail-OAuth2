<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\Exception;




class MailController extends Controller
{
    //
    private $email;
    private $email_name;
    private $client_id;
    private $client_secret;
    private $token;
    private $provider;

    /**
     * Default Constructor
     */
    public function __construct()
    {
        $this->email            = 'happyblackie01@gmail.com'; // ex. example@gmail.com
        $this->email_name       = 'Happy Blackie';     // ex. Abidhusain
        $this->client_id        = env('GMAIL_API_CLIENT_ID');
        $this->client_secret    = env('GMAIL_API_CLIENT_SECRET');
        $this->provider         = new Google(
            [
                'clientId'      => $this->client_id,
                'clientSecret'  => $this->client_secret
            ]
        );

    }

    /**
     * Send Email via PHPMailer Library
     */
    public function doSendEmail(Request $request)
    {
        $this->token = $request->get('oauth_token');

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;
            $mail->AuthType = 'XOAUTH2';
            $mail->setOAuth(
                new OAuth(
                    [
                        'provider'          => $this->provider,
                        'clientId'          => $this->client_id,
                        'clientSecret'      => $this->client_secret,
                        'refreshToken'      => $this->token,
                        'userName'          => $this->email
                    ]
                )
            );

            $mail->setFrom($this->email, $this->email_name);
            $mail->addAddress('happyblackie01@gmail.com', 'Happy Blackie');
            $mail->Subject = 'Laravel PHPMailer OAuth2 Integration';
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $body = 'Hello <b>Everyone</b>,<br><br>We successfully completed our PHPMailer Integration in Laravel Project with Gmail OAuth2.<br><br>Thank you,<br><b>Happy Blackie</b>';
            $mail->msgHTML($body);
            $mail->AltBody = 'This is a plain text message body';
            if( $mail->send() ) {
                return redirect()->back()->with('success', 'Successfully send email!');
            } else {
                return redirect()->back()->with('error', 'Unable to send email.');
            }
        } catch(Exception $e) {
            return redirect()->back()->with('error', 'Exception: ' . $e->getMessage());
        }
    }
}
