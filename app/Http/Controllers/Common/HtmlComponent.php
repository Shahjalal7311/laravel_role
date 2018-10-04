<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

class HtmlComponent extends Controller
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

    public static function verifyMobile($data=NULL) {
        $body = '<table style="width:600px;margin:0;padding:0" align="center" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td colspan="2" style="font-size:15px;line-height:17px;color:#646262;font-family:Arial,Helvetica,sans-serif;text-align:justify" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                           <td style="font-size:30px;font-family:Arial">
                                            Logo Here
                                         </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr colspan="2" ><td></td><tr>
                        <tr>
                            <td colspan="2" style="font-size:15px;line-height:6px;">
                            <p style="color: #262626;font-size: 18px;font-weight: 200;"> Hi</p></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size:15px;line-height:6px;">
                            <p style="color: #262626;font-size: 14px;"> Please confirm your email address to view advertiser details. </p></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size:15px;line-height:6px;">
                            <p style="margin:0;color:#262626;font-weight:bold;font-size:16px;padding-bottom:15px;line-height:1.167">Verification Code: </p></td>
                        </tr>
                        <tr colspan="2" ><td></td><tr>
                        <tr style="display:none">
                            <td colspan="2" style="font-size:15px;line-height:6px;">
                            <p><a href="#">Click to. Active Code</a></p></td>
                        </tr>
                        <tr colspan="2" ><td> Thank you for being with us <a href="http://www.test.com/">test.com</a> </td><tr>
                        <tr colspan="2" ><td>Regards<td><tr>
                        <tr colspan="2" ><td>Reivo BD Team<td><tr>
                        <tr colspan="2" ><td> Helpline: 8801963636462 <td><tr>
                        <tr colspan="2" ><td>--------------------------------------------------</a><td><tr>
                        <tr colspan="2" ><td><a href="http://www.test.com/">test.com</a>Test</a><td><tr>
                    </tbody>
                </table>';
        //debug($body);
        return $body;
    }

}
