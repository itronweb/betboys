<?php /**
            * Unreal Security - Namaket Check Class v2.5.0
            *
            * PHP version > 5
            *
            * LICENSE: This source file is subject to version 3.01 of the PHP license
            * that is available through the world-wide-web at the following URI:
            * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
            * the PHP License and are unable to obtain it through the web, please
            * send a note to license@php.net so we can mail you a copy immediately.
            *
            * @package    Namaket
            * @author     <info@namaket.com>
            * @copyright  1398 - 1399 Namaket.com
            * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
            * @version    Ultimate
            * @link       https://namaket.com */
            
            $domain=$_SERVER['SERVER_NAME'];
            $product="2";
            $licenseServer = "http://136.243.255.205/~admin/api/";

            $postvalue="domain=$domain&product=".urlencode($product);

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $licenseServer);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvalue);
            $result= json_decode(curl_exec($ch), true);
            curl_close($ch);

            if($result['status'] != 200) {
            $html = "<div align='center'>
    <table width='100%' border='0' style='padding:15px; border-color:#F00; border-style:solid; background-color:#FF6C70; font-family:Tahoma, Geneva, sans-serif; font-size:22px; color:white;'>

    <tr>

        <td><b>خطا خطا لایسنس برای این اطلاعات فعال نشده است<%returnmessage%> <br >tel:@pccon</b></td >

    </tr>

    </table>

</div>";
            $search = '<%returnmessage%>';
            $replace = $result['message'];
            $html = str_replace($search, $replace, $html);


            die( $html );

            }
            ?>