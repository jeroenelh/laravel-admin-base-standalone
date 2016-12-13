<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    {{--http://emailframe.work/--}}
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!--[if !mso]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<![endif]-->

    <style type="text/css">

        /* CLIENT-SPECIFIC STYLES */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }

        /* RESET STYLES */
        img { border: 0; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MEDIA QUERIES */
        @media only screen and (max-width: 639px){
            .wrapper{ width:320px!important; padding: 0 !important; }
            .container{ width:300px!important;  padding: 0 !important; }
            .mobile{ width:300px!important; display:block!important; padding: 0 !important; }
            .mobile50{ width:150px!important; padding: 0 !important; }
            .center{ margin:0 auto !important; text-align:center !important; }
            .img{ width:100% !important; height:auto; }
            .header{ width:320px; padding: 0 !important; background-image: url(http://placehold.it/320x400) !important; }
            *[class="mobileOff"] { width: 0px !important; display: none !important; }
            *[class*="mobileOn"] { display: block !important; max-height:none !important; }
        }
    </style>

    <!--[if gte mso 15]>
    <style type="text/css">
        table { font-size:1px; line-height:0; mso-margin-top-alt:1px;mso-line-height-rule: exactly; }
        * { mso-line-height-rule: exactly; }
    </style>
    <![endif]-->

</head>
<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" style="background-color:#F6F6F6;  font-family:Arial,serif; margin:0; padding:0; min-width: 100%; -webkit-text-size-adjust:none; -ms-text-size-adjust:none;">

<!--[if !mso]><!-- -->
<img style="min-width:640px; display:block; margin:0; padding:0" class="mobileOff" width="640" height="1" src="images/spacer.gif">
<!--<![endif]-->

<!-- Start Background -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#F6F6F6">
    <tr>
        <td width="100%" valign="top" align="center">

            @yield('content')

        </td>
    </tr>
</table>
<!-- End Background -->
</body>
</html>