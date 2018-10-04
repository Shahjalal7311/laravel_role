<?php

namespace App\Http\Controllers\Common;

use PHPMailer\PHPMailer;
use App\Http\Controllers\Controller;

class MailSendComponent extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | File Uploads Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling file Uploads and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public static function sendMail($test_body, $subject, $receiver, $receiver_name)
    {
        $text             = $test_body;
        $mail             = new PHPMailer\PHPMailer(); // create a n
        $mail->SMTPDebug  = 2; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth   = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host       = "smtp.gmail.com";
        $mail->Port       = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "test@gmail.com";
        $mail->Password = "username";
        $mail->SetFrom("test@gmail.com", 'SHAHJALAL');
        $mail->Subject = $subject;
        $mail->Body    = $text;
        $mail->AddAddress($receiver, $receiver_name);
        if ($mail->Send()) {
            return 1;
        } else {
            return 0;
        }
    }

 }   