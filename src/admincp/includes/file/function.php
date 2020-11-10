<?php 
//require_once('../../checklogin.php'); 

function checkstatus($input)
{
	switch($input)
	{
	case 0 : echo "غیرفعال";break;
	case 1 : echo "فعال";break;
	default : echo "نامشخص";break;
	}
}

function get_fa_team_name($search)
{
	
$hostname_cn = "localhost";
$database_cn = "admin_4ubets_bet";
$username_cn = "admin_4ubets";
$password_cn = "Ehk6314ia3537aX44V";

// Create connection
$cn = mysqli_connect($hostname_cn, $username_cn, $password_cn, $database_cn) or trigger_error(mysqli_error($database_cn),E_USER_ERROR);
// Check connection
if (!$cn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_query($cn,"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");  
	mysqli_select_db($cn,$database_cn);
	$query_Recordsettname = "SELECT teams_name_fa FROM `teams` WHERE `teams_name_en` = '".$search."'";
	$Recordtname = mysqli_query($cn,$query_Recordsettname) or die(mysqli_error($cn));
	$row_Recordtname = mysqli_fetch_assoc($Recordtname);
	if($row_Recordtname['teams_name_fa'] != ""){
		return $row_Recordtname['teams_name_fa'];
	}
	else {
		return $search;
	}
	
}
function pricef($val,$price_format=" تومان"){
	return number_format($val).$price_format;
}
//////////////////////////////////////////////////////////////////////////////
function convertenN($input)
{
    $unicode = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    $string = str_replace($unicode, $english , $input);

    return $string;
}



function checkkhstatus($input)
{
	switch($input)
	{
	case 0 : echo "فوت شده";break;
	case 1 : echo "زنده";break;
	default : echo "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function tfstatus($input)
{
	switch($input)
	{
	case 0 : echo "ندارد";break;
	case 1 : echo "دارد";break;
	default : echo "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function checkserialstatus($input)
{
	switch($input)
	{
	case 0 : echo "<span style='color:#990000;'>فروخته نشده</span>";break;
	case 1 : echo "<span style='color:#006600;'>فروخته شده</span>";break;
	default : echo "<span style='color:#990000;'>نامشخص</span>";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function checkgender($input)
{
	switch($input)
	{
	case 0 : return "نامشخص";break;
	case 1 : return "مرد";break;
	case 2 : return "زن";break;
	default : return "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function blocktype($input)
{
	switch($input)
	{
	case 0 : return "متنی";break;
	case 1 : return "مطالب";break;
	case 2 : return "تصاویر";break;
	case 3 : return "گروه مطالب";break;
	case 4 : return "گروه تصاویر";break;
	case 5 : return "لینک ها";break;
	case 6 : return "تبلیغات";break;
	case 7 : return "اسلایدشو";break;
	case 8 : return "ماژول";break;
	case 9 : return "منو";break;
	case 10 : return "نظرسنجی";break;
	default : return "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function blockalign($input)
{
	switch($input)
	{
	case 1 : return "راست";break;
	case 2 : return "چپ";break;
	case 3 : return "وسط";break;
	case 4 : return "بالا";break;
	case 5 : return "پایین";break;
	case 6 : return "راست فرعی";break;
	case 7 : return "چپ فرعی";break;
	default : return "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function blocknoetype($input)
{
	switch($input)
	{
	case 1 : echo "اصلی";break;
	case 2 : echo "فرعی";break;
	default : echo "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function checkdoretype($input)
{
	switch($input)
	{
	case 1 : return "خدمات";break;
	case 2 : return "معرفی نامه";break;
	default : return "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function komitetype($input)
{
	switch($input)
	{
	case 1 : echo "کمیته";break;
	case 2 : echo "سایر";break;
	default : echo "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function checkbedehistatus($input)
{
	switch($input)
	{
	case 0 : echo "پرداخت نشده";break;
	case 1 : echo "پرداخت شده";break;
	default : echo "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function paymentcheckstatus($input)
{
	switch($input)
	{
	case 0 : echo "پرداخت نشده";break;
	case 1 : echo "پرداخت موفق";break;
	case 2 : echo "پرداخت ناموفق";break;
	case 9 : echo "بررسی نشده";break;
	default : echo "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function smssendstatus($input)
{
	switch($input)
	{
	case 0 : echo "نام کاربری و رمز عبور صحیح نمی باشد .";break;
	case 1 : echo "ارسال موفق";break;
	case 2 : echo "اعتبار کافی نمی باشد";break;
	case 3 : echo "محدودیت ارسال روزانه";break;
	case 4 : echo "محدودیت در حجم ارسال";break;
	case 5 : echo "شماره فرستنده معتبر نیست";break;
	case 6 : echo "سامانه در حال به روز رسانی می باشد";break;
	case 7 : echo "متن پیامک شما شامل حروف فیلتر شده می باشد";break;
	case 8 : echo "عدم رسیدن متن به حداقل ارسال";break;
	case 9 : echo "ارسال از خطوط عمومی از طریق وب سرویس مقدور نمی باشد";break;
	case 10 : echo "کاربر مسدود شده است";break;
	case 11 : echo "ارسال نا موفق";break;
	default : echo "ارسال موفق";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function smsdeliverystatus($input)
{
	switch($input)
	{
	case 0 : echo "ارسال شده به مخابرات";break;
	case 1 : echo "رسیده به گوشی";break;
	case 2 : echo "نرسیده به گوشی";break;
	case 3 : echo "خطای مخابراتی";break;
	case 5 : echo "خطای نا مشخص";break;
	case 8 : echo "رسیده به مخابرات";break;
	case 16 : echo "نرسیده به مخابرات";break;
	case 100 : echo "نامشخص";break;
	default : echo "نامشخص";break;
	}
}



//////////////////////////////////////////////////////////////////////////////



function getCategoryTree($cn,$tbl,$code){

		global $categorytree;

		$query_rschecklev = "SELECT * FROM $tbl WHERE code = '$code'";
		$rschecklev = mysqli_query($cn,$query_rschecklev) or die(mysqli_error($cn));
		$row_rschecklev = mysqli_fetch_assoc($rschecklev);
		$totalRows_rschecklev = mysqli_num_rows($rschecklev);

		if($totalRows_rschecklev>0){

			$row_rschecklev['name'] = stripslashes($row_rschecklev['name']);

			$categorytree = $row_rschecklev['name']." : ".$categorytree;

			getCategoryTree($cn,$tbl,$row_rschecklev['level']);

		}else{
			$categorytree = substr($categorytree,0,strrpos($categorytree, ":"));
		}
	}






//////////////////////////////////////////////////////////////////////////////



function encrypt($data, $key){
   $finaldata=  base64_encode(
    mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        str_pad($key, 32, "\0"),
        $data,
        MCRYPT_MODE_ECB,
        "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
    )
	);
	
	$finaldata = str_replace("=","!!",$finaldata);
	$finaldata = str_replace("+","@@",$finaldata);
	$finaldata = str_replace("/","$$",$finaldata);
	$finaldata = strrev($finaldata);
	
	return $finaldata;
}
 


//////////////////////////////////////////////////////////////////////////////



function decrypt($data, $key){
 	$data = strrev($data);
	$data = str_replace("!!","=",$data);
	$data = str_replace("@@","+",$data);
	$data = str_replace("$$","/",$data);
    $decode = base64_decode($data);
	
    return mcrypt_decrypt(
                    MCRYPT_RIJNDAEL_128,
                    str_pad($key, 32, "\0"),
                    $decode,
                    MCRYPT_MODE_ECB,
                    "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
            );
}
 


//////////////////////////////////////////////////////////////////////////////



function checknewstatus($input)
{
	switch($input)
	{
	case 0 : echo "غیرفعال";break;
	case 1 : echo "فعال";break;
	case 2 : echo "در حال بررسی";break;
	case 3 : echo "پیش نویس";break;
	default : echo "نامشخص";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function checkgallerytype($input)
{
	switch($input)
	{
	case 1 : echo "عکس";break;
	case 2 : echo "فیلم";break;
	case 3 : echo "صوت";break;
	default : echo "";break;
	}
}

 


 

//////////////////////////////////////////////////////////////////////////////



function checkadvertisetype($input)
{
	switch($input)
	{
	case 1 : echo "فلش";break;
	case 2 : echo "عکس";break;
	default : echo "";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function checkselecttype($input)
{
	switch($input)
	{
	case 0 : echo "تک انتخابی";break;
	case 1 : echo "چند انتخابی";break;
	default : echo "";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function checkmemtype($input)
{
	switch($input)
	{
	case 0 : echo "همه";break;
	case 1 : echo "اعضاء";break;
	default : echo "";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function checktype($input)
{
	switch($input)
	{
	case 0 : echo "اصلی";break;
	case 1 : echo "فرعی";break;
	default : echo "نامشخص";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function checkexamtype($input)
{
	switch($input)
	{
	case 1 : echo "تصادفی";break;
	case 2 : echo "انتخابی";break;
	default : echo "نامشخص";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function checkread($input)
{
	switch($input)
	{
		case 0 : echo "خوانده نشده";break;
		case 1 : echo "خوانده شده";break;
		default : echo "نامشخص";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function checkstock($input)
{
	switch($input)
	{
		case 1 : echo "عدد";break;
		case 2 : echo "لیتر";break;
		case 3 : echo "بشکه";break;
		case 4 : echo "گرم";break;
		case 5 : echo "کیلوگرم";break;
		case 6 : echo "تن";break;
		default : echo "نامشخص";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function checkmenucat($input,$type)
{
	if($type=='n') {
		switch($input)
		{
			case 1 : echo "صفحات";break;
			case 2 : echo "مطالب";break;
			case 3 : echo "گالری";break;
			case 4 : echo "خدمات";break;
			case 5 : echo "محصولات";break;
			default : echo "نامشخص";break;
		}
	}
	else if($type=='t') {
		switch($input)
		{
			case 1 : echo "page";break;
			case 2 : echo "contents";break;
			case 3 : echo "gallery";break;
			case 4 : echo "services";break;
			case 5 : echo "products";break;
			default : echo "نامشخص";break;
		}
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function calendartype($input)
{
	switch($input)
	{
		case 1 : echo "شمسی";break;
		case 2 : echo "میلادی";break;
		case 3 : echo "قمری";break;
		default : echo "نامشخص";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function eventtype($input)
{
	switch($input)
	{
		case 1 : echo "ملی";break;
		case 2 : echo "مذهبی";break;
		case 3 : echo "تاریخی";break;
		case 4 : echo "فرهنگی";break;
		case 5 : echo "جهانی";break;
		case 6 : echo "عمومی";break;
		default : echo "نامشخص";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function hijrimonthname($input)
{
	switch($input)
	{
		case 1 : echo "محرم";break;
		case 2 : echo "صفر";break;
		case 3 : echo "ربيع الاول";break;
		case 4 : echo "ربيع الثاني";break;
		case 5 : echo "جمادي الاولي";break;
		case 6 : echo "جمادي الثانيه";break;
		case 7 : echo "رجب";break;
		case 8 : echo "شعبان";break;
		case 9 : echo "رمضان";break;
		case 10 : echo "شوال";break;
		case 11 : echo "ذوالقعده";break;
		case 12 : echo "ذوالحجه";break;
		default : echo "نامشخص";break;
	}
}

 

//////////////////////////////////////////////////////////////////////////////



function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}

 

//////////////////////////////////////////////////////////////////////////////



function sepratenumber($str)
{
	$str=round($str,0);
	if(substr($str , 0,1)=='-')
	{
		$str=abs($str);
		$number .="-";
	}
	
	$cc=strlen($str)%3;
	
	if($cc==2)
		$cc+=2;
	elseif($cc==1)
		$cc++;
	
	
	for($i=0;$i<strlen($str);$i++)
	{
		$cc++;
		$number .= substr($str , $i,1);
		if($str<0 and $i<3 and $cc%3==0)
		$cc--;
		if($cc%3==0 and $i<strlen($str)-1)
		$number .= ",";
	}
	
	return $number;
}

 

//////////////////////////////////////////////////////////////////////////////



function rrmdir($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") 
           rrmdir($dir."/".$object); 
        else unlink   ($dir."/".$object);
      }
    }
    reset($objects);
    rmdir($dir);
  }
}
//////////////////////////////

?>