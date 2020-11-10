<?php



if ( !defined('BASEPATH') )

    exit('No direct script access allowed');

/*

  | -------------------------------------------------------------------------

  | URI ROUTING

  | -------------------------------------------------------------------------

  | This file lets you re-map URI requests to specific controller functions.

  |

  | Typically there is a one-to-one relationship between a URL string

  | and its corresponding controller class/method. The segments in a

  | URL normally follow this pattern:

  |

  |	example.com/class/method/id/

  |

  | In some instances, however, you may want to remap this relationship

  | so that a different class/function is called than the one

  | corresponding to the URL.

  |

  | Please see the user guide for complete details:

  |

  |	http://codeigniter.com/user_guide/general/routing.html

  |

  | -------------------------------------------------------------------------

  | RESERVED ROUTES

  | -------------------------------------------------------------------------

  |

  | There area two reserved routes:

  |

  |	$route['default_controller'] = 'welcome';

  |

  | This route indicates which controller class should be loaded if the

  | URI contains no data. In the above example, the "welcome" class

  | would be loaded.

  |

  |	$route['404_override'] = 'errors/page_missing';

  |

  | This route will tell the Router what URI segments to use if those provided

  | in the URL cannot be matched to a valid route.

  |

 */



$route['default_controller'] = "bets";

//$route['default_controller'] = "content";

$route['404_override'] = 'content';

$route['([a-zA-Z_-]+)/admincp/([a-zA-Z_-]+)'] = "";

$route['payment'] = 'payment/payments';

$route['payment/(.+)'] = 'payment/payments/$1';

$route['help'] = 'content';

$route['betGuide'] = 'content';

$route['mixForm'] = 'content';

$route['faq'] = 'content';

$route['title'] = 'content';

$route['leagueTables'] = 'content';
$route['leagueTables/(.+)'] = 'content/$1';

$route['register'] = 'users/register';

// front routing

// Special Case Routes

$route[ADMIN_PATH . '/users/login'] = "users/login";

$route[ADMIN_PATH . '/users/logout'] = "users/logout";

$route[ADMIN_PATH . '/users/forgot-password'] = "users/forgot-password";

$route[ADMIN_PATH] = "dashboard/admin/dashboard";

// $route['netmrm'] = "dashboard/admin/dashboard";

$route['admin'] = "admincp";

$route[ADMIN_PATH . '/([a-zA-Z_-]+)/(.+)'] = "$1/admincp/$2";

$route[ADMIN_PATH . '/([a-zA-Z_-]+)'] = "$1/admincp/$1";

/* End of file routes.php */

/* Location: ./application/config/routes.php */


$route['test456'] = 'content'; 