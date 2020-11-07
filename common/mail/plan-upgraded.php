<?php

use yii\helpers\Html;
use common\models\Order;
use common\models\Voucher;
use common\models\BankDiscount;

/* @var $this yii\web\View */
/* @var $order common\models\Order */
?>


<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

    <head>
        <title>Payment Confirmation</title>
        <!--[if !mso]><!-- -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <!--<![endif]-->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <meta name="viewport" content="width=device-width,initial-scale=1">
                    <style type="text/css">
                        #outlook a {
                            padding: 0;
                        }

                        body {
                            margin: 0;
                            padding: 0;
                            -webkit-text-size-adjust: 100%;
                            -ms-text-size-adjust: 100%;
                        }

                        table,
                        td {
                            border-collapse: collapse;
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                        }

                        img {
                            border: 0;
                            height: auto;
                            line-height: 100%;
                            outline: none;
                            text-decoration: none;
                            -ms-interpolation-mode: bicubic;
                        }

                        p {
                            display: block;
                            margin: 13px 0;
                        }
                    </style>

                    <style type="text/css">
                        @media only screen and (min-width:480px) {
                            .mj-column-per-100 {
                                width: 100% !important;
                                max-width: 100%;
                            }
                            .mj-column-per-50 {
                                width: 50% !important;
                                max-width: 50%;
                            }
                        }
                    </style>
                    <style type="text/css">
                        @media only screen and (max-width:480px) {
                            table.mj-full-width-mobile {
                                width: 100% !important;
                            }
                            td.mj-full-width-mobile {
                                width: auto !important;
                            }
                        }
                    </style>
                    </head>

                    <body style="background-color:#ffffff;">
                        <div style="background-color:#ffffff;">
                            <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="margin:0px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0px;padding-bottom:10px;padding-top:10px;text-align:center;">
                                                <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
                                                <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="width:100px;"><img height="auto" src='https://res.cloudinary.com/plugn/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max/restaurants/rest_1d40a718-beac-11ea-808a-0673128d0c9c/logo/R1wBpdQU4GBF_eT0FL89A0bNUFr_ZOJJ.png' style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="100"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="margin:0px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="border:1px solid #d8e2e7;direction:ltr;font-size:0px;padding:5px;text-align:center;">
                                                <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:588px;" width="588" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                                <div style="margin:0px auto;max-width:588px;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="direction:ltr;font-size:0px;padding:0px;text-align:center;">
                                                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:588px;" ><![endif]-->
                                                                    <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">

                                                                            <tr>
                                                                                <td align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:6px;word-break:break-word;">
                                                                                    <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:21px;font-weight:bold;line-height:24px;text-align:center;color:#000000;">Your store was successfully upgraded</div>
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                    <p style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:100%;"></p>
                                                                                    <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:538px;" role="presentation" width="538px" ><tr><td style="height:0;line-height:0;"> &nbsp;
                                        </td></tr></table><![endif]-->
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:6px;word-break:break-word;">
                                                                                    <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:21px;font-weight:bold;line-height:24px;text-align:center;color:#000000;">This email is to confirm the recent upgrade of your store to  <?= $subscription->plan->name ?> </div>
                                                                                </td>
                                                                            </tr>


                                                                            <tr>
                                                                                <td style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                    <p style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:100%;"></p>
                                                                                    <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:538px;" role="presentation" width="538px" ><tr><td style="height:0;line-height:0;"> &nbsp;
                                        </td></tr></table><![endif]-->
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table></td></tr><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:588px;" width="588" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                                <div style="margin:0px auto;max-width:588px;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="direction:ltr;font-size:0px;padding:0px;text-align:center;">
                                                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:588px;" ><![endif]-->
                                                                    <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                                     
                                                                            <tr>
                                                                                <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                    <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;table-layout:auto;width:100%;border:none;">
                                                                                        <!-- #item -->

                    
                                                                                            <tr>
                                                                                            
                                                                                                <td style="width: 70%; padding: 0 15px; padding-top:10px; vertical-align:top;">
                                                                                                    <p style="margin:0;padding:0;">
                                                                                                        <?= $subscription->plan->name   ?>
                                                                                                    </p>
                                                                                                
                                                                                                </td>
                                                                                                <td style="color:#828585; padding-top:10px; text-align: right; vertical-align:top;width: 80px;">
                                                                                                    <?= \Yii::$app->formatter->asCurrency($subscription->plan->price); ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                 

                                                                                        <!-- #item -->
                                                                                        <!-- End items -->
                                                                                        <!-- Subtotal-->
                                                                                        <tr>
                                                                                            <td colspan="2" style="padding: 0 15px; padding-top:40px; vertical-align:top;">
                                                                                                <p style="margin:0;padding:0;">Subtotal</p>
                                                                                            </td>
                                                                                            <td style="color:#828585;padding-top:40px; text-align: right; vertical-align:top;">
                                                                                                <?= \Yii::$app->formatter->asCurrency($subscription->plan->price) ?>
                                                                                            </td>
                                                                                        </tr>


                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <!-- TOTAL -->
                                                                            <tr>
                                                                                <td align="left" style="font-size:0px;padding:10px 25px;padding-bottom:30px;word-break:break-word;">
                                                                                    <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;table-layout:auto;width:100%;border:none;">
                                                                                        <tr>
                                                                                            <td colspan="2" style="font-size:23px; padding: 0 15px; padding-top:30px; vertical-align:top;">
                                                                                                <p style="margin:0;padding:0;">Total</p>
                                                                                            </td>
                                                                                            <td style="font-size:23px; padding: 0 15px 0 0; padding-top:30px; text-align: right; vertical-align:top;">
                                                                                                <?= \Yii::$app->formatter->asCurrency($subscription->plan->price) ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                    <p style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:100%;"></p>
                                                                                    <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:538px;" role="presentation" width="538px" ><tr><td style="height:0;line-height:0;"> &nbsp;
                                        </td></tr></table><![endif]-->
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="margin:0px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0px;padding-bottom:24px;padding-top:10px;text-align:center;">
                                                <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
                                                <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:24px;text-align:center;color:grey;">This email is delivered by <a href="https://plugn.io">Plugn</a></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table><![endif]-->
                        </div>
                    </body>

                    </html>
