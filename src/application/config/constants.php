<?php

if ( !defined('BASEPATH') )
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE' , 0644);
define('FILE_WRITE_MODE' , 0666);
define('DIR_READ_MODE' , 0755);
define('DIR_WRITE_MODE' , 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ' , 'rb');
define('FOPEN_READ_WRITE' , 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE' , 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE' , 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE' , 'ab');
define('FOPEN_READ_WRITE_CREATE' , 'a+b');
define('FOPEN_WRITE_CREATE_STRICT' , 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT' , 'x+b');

/*
  |--------------------------------------------------------------------------
  | CMS Version
  |--------------------------------------------------------------------------
  |
  | Defines the version number for cms
  |
 */
define('CC_VERSION' , '1.1.4');

/*
  |--------------------------------------------------------------------------
  | CMS Root Folder
  |--------------------------------------------------------------------------
  |
  | Defines the absolute path to the root folder of cms canvas
  |
 */
define('CMS_ROOT' , dirname(BASEPATH) . '/');

/**
 * 
 */
define('BOOKMAKER_ID' , 1);
define('API_DIR' , 'upload/API' . DIRECTORY_SEPARATOR);

/*
  |--------------------------------------------------------------------------
  | Admin Path
  |--------------------------------------------------------------------------
  |
 */
define('ADMIN_PATH' , 'kulamod');
define('NO_NEED_LOGIN_PRE' , 'netmrm');

/*
  |--------------------------------------------------------------------------
  | Group Types
  |--------------------------------------------------------------------------
  |
 */
define('USER' , 'user');
define('MANFI' ,59000);
define('ADMINISTRATOR' , 'administrator');
define('SUPER_ADMIN' , 'superadmin');

/*
  |--------------------------------------------------------------------------
  | CMS Image Cache Directory
  |--------------------------------------------------------------------------
  |
 */
define('IMAGE_CACHE' , '/assets/cms/image-cache');

/*
  |--------------------------------------------------------------------------
  | User Data Storage
  |--------------------------------------------------------------------------
  |
 */
define('USER_DATA' , '/assets/userdata/');

/*
  |--------------------------------------------------------------------------
  | Packages
  |--------------------------------------------------------------------------
  |
 */
$packages = array(
    'jquery' => array(
        'javascript' => array(
        ) ,
    ) ,
    'jquerytools' => array(
        'javascript' => array(
        ) ,
    ) ,
    'labelify' => array(
        'javascript' => array(
        ) ,
    ) ,
    'tablednd' => array(
        'javascript' => array(
        ) ,
    ) ,
    'superfish' => array(
        'javascript' => array(
        ) ,
    ) ,
    'zclip' => array(
        'javascript' => array(
        ) ,
    ) ,
    'jquerycycle' => array(
        'javascript' => array(
        ) ,
    ) ,
    'tinymce' => array(
        'javascript' => array(
        ) ,
    ) ,
    'ckeditor' => array(
        'javascript' => array(
        ) ,
    ) ,
    'ck_jq_adapter' => array(
        'javascript' => array(
        ) ,
    ) ,
    'fancybox' => array(
        'javascript' => array(
        ) ,
        'stylesheet' => array(
        ) ,
    ) ,
    'nestedSortable' => array(
        'javascript' => array(
        ) ,
        'stylesheet' => array(
        ) ,
    ) ,
    'codemirror' => array(
        'javascript' => array(
        ) ,
        'stylesheet' => array(
        ) ,
    ) ,
    'admin_jqueryui' => array(
        'javascript' => array(
        ) ,
        'stylesheet' => array(
        ) ,
    ) ,
);

define('PACKAGES' , serialize($packages));

/*
  |--------------------------------------------------------------------------
  | States
  |--------------------------------------------------------------------------
  |
 */
$states = array(
    '' => "" ,
    'AL' => "Alabama" ,
    'AK' => "Alaska" ,
    'AZ' => "Arizona" ,
    'AR' => "Arkansas" ,
    'CA' => "California" ,
    'CO' => "Colorado" ,
    'CT' => "Connecticut" ,
    'DE' => "Delaware" ,
    'DC' => "District Of Columbia" ,
    'FL' => "Florida" ,
    'GA' => "Georgia" ,
    'HI' => "Hawaii" ,
    'ID' => "Idaho" ,
    'IL' => "Illinois" ,
    'IN' => "Indiana" ,
    'IA' => "Iowa" ,
    'KS' => "Kansas" ,
    'KY' => "Kentucky" ,
    'LA' => "Louisiana" ,
    'ME' => "Maine" ,
    'MD' => "Maryland" ,
    'MA' => "Massachusetts" ,
    'MI' => "Michigan" ,
    'MN' => "Minnesota" ,
    'MS' => "Mississippi" ,
    'MO' => "Missouri" ,
    'MT' => "Montana" ,
    'NE' => "Nebraska" ,
    'NV' => "Nevada" ,
    'NH' => "New Hampshire" ,
    'NJ' => "New Jersey" ,
    'NM' => "New Mexico" ,
    'NY' => "New York" ,
    'NC' => "North Carolina" ,
    'ND' => "North Dakota" ,
    'OH' => "Ohio" ,
    'OK' => "Oklahoma" ,
    'OR' => "Oregon" ,
    'PA' => "Pennsylvania" ,
    'RI' => "Rhode Island" ,
    'SC' => "South Carolina" ,
    'SD' => "South Dakota" ,
    'TN' => "Tennessee" ,
    'TX' => "Texas" ,
    'UT' => "Utah" ,
    'VT' => "Vermont" ,
    'VA' => "Virginia" ,
    'WA' => "Washington" ,
    'WV' => "West Virginia" ,
    'WI' => "Wisconsin" ,
    'WY' => "Wyoming"
);
define('STATES' , serialize($states));


/*
  |--------------------------------------------------------------------------
  | Admin Missing Image
  |--------------------------------------------------------------------------
  |
 */
define('ADMIN_NO_IMAGE' , '/application/themes/admin/assets/images/no_image.jpg');

/* End of file constants.php */
/* Location: ./system/application/config/constants.php */

