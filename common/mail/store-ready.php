<?php

use yii\helpers\Html;
use common\models\Subscription;
use common\models\Restaurant;


/* @var $this yii\web\View */
/* @var $subscription common\models\Subscription */
/* @var $store common\models\Restaurant */

$store_domain = $store->restaurant_domain;
$customDomainUrl = Yii::$app->params['frontendUrl'] . '/site/connect-domain?id=' . $store->restaurant_uuid;

?>



    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
      <head>
        <title>
          Your store <?= $store->name ?> is now ready
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
                 style="direction:ltr;font-size:0px;padding:0px;padding-bottom:5px;padding-top:5px;text-align:center;vertical-align:top;"
              >
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

        <tr>

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
                 style="border:1px solid #d8e2e7;direction:ltr;font-size:0px;padding:20px 0;padding-top:0;text-align:center;vertical-align:top;"
              >
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

            <tr>
              <td
                 class="" width="600px"
              >

        <table
           align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:598px;" width="598"
        >
          <tr>
            <td  style="line-height:0;font-size:0;mso-line-height-rule:exactly;">
              <v:image
                 style="border:0;height:142px;mso-position-horizontal:center;position:absolute;top:0;width:631px;z-index:-3;" src="https://res.cloudinary.com/plugn/image/upload/v1618264268/plugn-bg_x4wd91.png" xmlns:v="urn:schemas-microsoft-com:vml"
              />
      <![endif]-->
      <div
         style="margin:0 auto;max-width:598px;"
      >
        <table
           border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"
        >
          <tr
             style="vertical-align:top;"
          >

          <td
             background="https://res.cloudinary.com/plugn/image/upload/v1618264268/plugn-bg_x4wd91.png" style="background:#2B546A url(https://res.cloudinary.com/plugn/image/upload/v1618264268/plugn-bg_x4wd91.png) no-repeat center center / cover;background-position:center center;background-repeat:no-repeat;padding:0px;vertical-align:top;" height="142"
          >

      <!--[if mso | IE]>
        <table
           border="0" cellpadding="0" cellspacing="0" style="width:598px;" width="598"
        >
          <tr>
            <td  style="">
      <![endif]-->
      <div
         class="mj-hero-content" style="margin:0px auto;"
      >
        <table
           border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;margin:0px;"
        >
          <tr>
            <td  style="" >
              <table
                 border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;margin:0px;"
              >

                    <tr>
                      <td
                         align="left" style="font-size:0px;padding:0;padding-top:30px;padding-left:30px;word-break:break-word;"
                      >

      <table
         border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;"
      >
        <tbody>
          <tr>
            <td  style="width:93px;">

      <img
         height="auto" src="https://res.cloudinary.com/plugn/image/upload/v1618264194/plugn-white_egzahs.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;" width="93"
      />

            </td>
          </tr>
        </tbody>
      </table>

                      </td>
                    </tr>

                    <tr>
                      <td
                         align="left" style="font-size:0px;padding:20px;padding-top:40px;padding-left:30px;word-break:break-word;"
                      >

      <div
         style="font-family:Helvetica;font-size:21px;font-weight:900;line-height:24px;text-align:left;color:#ffffff;"
      >
        Your Store is Ready
      </div>

                      </td>
                    </tr>

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
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:598px;" width="598"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->


      <div  style="Margin:0px auto;max-width:598px;">

        <table
           align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"
        >
          <tbody>
            <tr>
              <td
                 style="direction:ltr;font-size:0px;padding:20px;padding-top:25px;text-align:center;vertical-align:top;"
              >
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

        <tr>

            <td
               class="" style="vertical-align:top;width:558px;"
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
                 align="left" style="font-size:0px;padding:10px 25px;padding-bottom:10px;word-break:break-word;"
              >

      <div
         style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:24px;text-align:left;color:#000000;"
      >
        Hi <?= $store->owner_first_name ? $store->owner_first_name : $store->name ?>,
      </div>

              </td>
            </tr>

            <tr>
              <td
                 align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:10px;word-break:break-word;"
              >

      <div
         style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:24px;text-align:left;color:#000000;"
      >
        Your store <a href='<?= $store->restaurant_domain ?>' style='color:#2F80ED; text-decoration:none;'> <?= $store->name ?></a> is now ready. Check it out on
      </div>

              </td>
            </tr>

            <tr>
              <td
                 align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:6px;word-break:break-word;"
              >

      <div
         style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:18px;line-height:24px;text-align:left;color:#000000;"
      >
        <a href='<?= $store->restaurant_domain ?>' style='color:#2B546A; text-decoration: none;'>
                        <b><?= $store->restaurant_domain ?></b>
                        </a>
      </div>

              </td>
            </tr>

            <tr>
              <td
                 align="left" vertical-align="middle" style="font-size:0px;padding:10px 25px;padding-top:10px;word-break:break-word;"
              >

      <table
         border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;"
      >
        <tr>
          <td
             align="center" bgcolor="#2B546A" role="presentation" style="border:none;border-radius:5px;cursor:auto;padding:10px 25px;background:#2B546A;" valign="middle"
          >
            <a
               href="<?= $store->restaurant_domain ?>" style="background:#2B546A;color:#ffffff;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;font-weight:bold;line-height:120%;Margin:0;text-decoration:none;text-transform:none;" target="_blank"
            >
              Visit Website
            </a>
          </td>
        </tr>
      </table>

              </td>
            </tr>

            <tr>
              <td
                 align="left" style="font-size:0px;padding:10px 25px;padding-top:15px;padding-bottom:6px;word-break:break-word;"
              >

      <div
         style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:24px;text-align:left;color:#000000;"
      >
        You are also able to replace the above url with your own <a href='<?= $customDomainUrl ?>' style='color:#2F80ED; text-decoration:none;'>custom domain</a>
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
