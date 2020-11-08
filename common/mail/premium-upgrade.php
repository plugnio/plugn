<?php

use yii\helpers\Html;
use common\models\Subscription;
use common\models\Restaurant;
use common\models\TapPayments;


/* @var $this yii\web\View */
/* @var $subscription common\models\Subscription */
/* @var $store common\models\Restaurant */
?>


<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <title>
            Your store <?= $store->name ?> has been upgraded to our <?= $subscription->plan->name ?>
        </title>
        <!--[if !mso]><!-- -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <!--<![endif]-->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                    <style type="text/css">
                        #outlook a { padding:0; }
                        .ReadMsgBody { width:100%; }
                        .ExternalClass { width:100%; }
                        .ExternalClass * { line-height:100%; }
                        body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
                        table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
                        img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
                        p { display:block;margin:13px 0; }
                    </style>
                    <!--[if !mso]><!-->
                    <style type="text/css">
                        @media only screen and (max-width:480px) {
                            @-ms-viewport { width:320px; }
                            @viewport { width:320px; }
                        }
                    </style>
                    <!--<![endif]-->
                    <!--[if mso]>
                    <xml>
                    <o:OfficeDocumentSettings>
                      <o:AllowPNG/>
                      <o:PixelsPerInch>96</o:PixelsPerInch>
                    </o:OfficeDocumentSettings>
                    </xml>
                    <![endif]-->
                    <!--[if lte mso 11]>
                    <style type="text/css">
                      .outlook-group-fix { width:100% !important; }
                    </style>
                    <![endif]-->


                    <style type="text/css">
                        @media only screen and (min-width:480px) {
                            .mj-column-per-100 { width:100% !important; max-width: 100%; }
                        }
                    </style>


                    <style type="text/css">



                        @media only screen and (max-width:480px) {
                            table.full-width-mobile { width: 100% !important; }
                            td.full-width-mobile { width: auto !important; }
                        }

                    </style>


                    </head>
                    <body style="background-color:#ffffff;">


                        <div
                            style="background-color:#ffffff;"
                            >


                            <!--[if mso | IE]>
                            <table
                               align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
                            >
                              <tr>
                                <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                            <![endif]-->


                            <div  style="Margin:0px auto;max-width:600px;">

                                <table
                                    align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"
                                    >
                                    <tbody>
                                        <tr>
                                            <td
                                                style="direction:ltr;font-size:0px;padding:0px;padding-bottom:10px;padding-top:10px;text-align:center;vertical-align:top;"
                                                >
                                                <!--[if mso | IE]>
                                                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

                                        <tr>

                                            <td
                                               class="" style="vertical-align:top;width:600px;"
                                            >
                                          <![endif]-->

                                                <div
                                                    class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"
                                                    >

                                                    <table
                                                        border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"
                                                        >

                                                        <tr>
                                                            <td
                                                                align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;"
                                                                >

                                                                <table
                                                                    border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;"
                                                                    >
                                                                    <tbody>
                                                                        <tr>
                                                                            <td  style="width:100px;">

                                                                                <img
                                                                                    height="auto" src='https://res.cloudinary.com/plugn/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max/restaurants/rest_1d40a718-beac-11ea-808a-0673128d0c9c/logo/R1wBpdQU4GBF_eT0FL89A0bNUFr_ZOJJ.png' style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;" width="100"
                                                                                    />

                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                            </td>
                                                        </tr>

                                                    </table>

                                                </div>

                                                <!--[if mso | IE]>
                                                  </td>

                                              </tr>

                                                        </table>
                                                      <![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>


                            <!--[if mso | IE]>
                                </td>
                              </tr>
                            </table>

                            <table
                               align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
                            >
                              <tr>
                                <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                            <![endif]-->


                            <div  style="Margin:0px auto;max-width:600px;">

                                <table
                                    align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"
                                    >
                                    <tbody>
                                        <tr>
                                            <td
                                                style="border:1px solid #d8e2e7;direction:ltr;font-size:0px;padding:5px;text-align:center;vertical-align:top;"
                                                >
                                                <!--[if mso | IE]>
                                                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

                                            <tr>
                                              <td
                                                 class="" width="600px"
                                              >

                                      <table
                                         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:588px;" width="588"
                                      >
                                        <tr>
                                          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                                      <![endif]-->


                                                <div  style="Margin:0px auto;max-width:588px;">

                                                    <table
                                                        align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"
                                                        >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="direction:ltr;font-size:0px;padding:0px;text-align:center;vertical-align:top;"
                                                                    >
                                                                    <!--[if mso | IE]>
                                                                      <table role="presentation" border="0" cellpadding="0" cellspacing="0">

                                                            <tr>

                                                                <td
                                                                   class="" style="vertical-align:top;width:588px;"
                                                                >
                                                              <![endif]-->

                                                                    <div
                                                                        class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"
                                                                        >

                                                                        <table
                                                                            border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"
                                                                            >

                                                                            <tr>
                                                                                <td
                                                                                    align="center" style="font-size:0px;padding:10px 25px;padding-top:25px;padding-bottom:10px;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:center;color:#000000;"
                                                                                        >
                                                                                        Hello <?= $store->owner_first_name ? $store->owner_first_name : $store->name ?>,
                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td
                                                                                    align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:6px;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:21px;font-weight:bold;line-height:24px;text-align:center;color:#000000;"
                                                                                        >
                                                                                        Your store <?= $store->name ?> has been upgraded to our <?= $subscription->plan->name ?>
                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td
                                                                                    align="center" style="font-size:0px;padding:10px 25px;padding-top:15px;padding-bottom:6px;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:center;color:#000000;"
                                                                                        >
                                                                                        Plan expires on <?= date('F d, Y', strtotime($subscription->subscription_end_at)) ?>
                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td
                                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;"
                                                                                    >

                                                                                    <p
                                                                                        style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:100%;"
                                                                                        >
                                                                                    </p>

                                                                                    <!--[if mso | IE]>
                                                                                      <table
                                                                                         align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:538px;" role="presentation" width="538px"
                                                                                      >
                                                                                        <tr>
                                                                                          <td style="height:0;line-height:0;">
                                                                                            &nbsp;
                                                                                          </td>
                                                                                        </tr>
                                                                                      </table>
                                                                                    <![endif]-->


                                                                                </td>
                                                                            </tr>

                                                                        </table>

                                                                    </div>

                                                                    <!--[if mso | IE]>
                                                                      </td>

                                                                  </tr>

                                                                            </table>
                                                                          <![endif]-->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>


                                                <!--[if mso | IE]>
                                                    </td>
                                                  </tr>
                                                </table>

                                                        </td>
                                                      </tr>

                                                      <tr>
                                                        <td
                                                           class="" width="600px"
                                                        >

                                                <table
                                                   align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:588px;" width="588"
                                                >
                                                  <tr>
                                                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                                                <![endif]-->


                                                <div  style="Margin:0px auto;max-width:588px;">

                                                    <table
                                                        align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"
                                                        >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="direction:ltr;font-size:0px;padding:0px;padding-bottom:25px;text-align:center;vertical-align:top;"
                                                                    >
                                                                    <!--[if mso | IE]>
                                                                      <table role="presentation" border="0" cellpadding="0" cellspacing="0">

                                                            <tr>

                                                                <td
                                                                   class="" style="vertical-align:top;width:588px;"
                                                                >
                                                              <![endif]-->

                                                                    <div
                                                                        class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"
                                                                        >

                                                                        <table
                                                                            border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"
                                                                            >

                                                                            <tr>
                                                                                <td
                                                                                    align="left" style="font-size:0px;padding:10px 25px;padding-bottom:0;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;"
                                                                                        >
                                                                                        Payment of <?= \Yii::$app->formatter->asCurrency($subscription->plan->price); ?>

                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td
                                                                                    align="left" style="font-size:0px;padding:10px 25px;padding-bottom:0;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;"
                                                                                        >
                                                                                        Date:  <?= date('F d, Y', strtotime($subscription->subscription_start_at)) ?>
                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td
                                                                                    align="left" style="font-size:0px;padding:10px 25px;padding-top:0;padding-bottom:0;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;"
                                                                                        >
                                                                                        Paid by:  <?= $subscription->subscriptionPayment->payment_mode == TapPayments::GATEWAY_VISA_MASTERCARD ? 'Credit Card' : 'Knet' ?>
                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td
                                                                                    align="left" style="font-size:0px;padding:10px 25px;padding-top:0;padding-bottom:0;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;"
                                                                                        >
                                                                                        Result: <?= $subscription->subscriptionPayment->payment_current_status ?>
                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td
                                                                                    align="left" style="font-size:0px;padding:10px 25px;padding-top:0;padding-bottom:0;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;"
                                                                                        >
                                                                                        Ref: <?= $subscription->subscriptionPayment->payment_gateway_order_id ?>
                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td
                                                                                    align="left" style="font-size:0px;padding:10px 25px;padding-top:0;padding-bottom:0;word-break:break-word;"
                                                                                    >

                                                                                    <div
                                                                                        style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;"
                                                                                        >
                                                                                        Charge: <?= $subscription->subscriptionPayment->payment_gateway_transaction_id ?>
                                                                                    </div>

                                                                                </td>
                                                                            </tr>

                                                                        </table>

                                                                    </div>

                                                                    <!--[if mso | IE]>
                                                                      </td>

                                                                  </tr>

                                                                            </table>
                                                                          <![endif]-->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>


                                                <!--[if mso | IE]>
                                                    </td>
                                                  </tr>
                                                </table>

                                                        </td>
                                                      </tr>

                                                            </table>
                                                          <![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>


                            <!--[if mso | IE]>
                                </td>
                              </tr>
                            </table>
                            <![endif]-->


                        </div>

                    </body>
                    </html>
