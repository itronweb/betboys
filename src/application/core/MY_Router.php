<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH . "third_party/MX/Router.php";

class MY_Router extends MX_Router {

    public $module;

    public function fetch_module() {
        return $this->module;
    }

    public function _validate_request($segments) {

        if (count($segments) == 0)
            return $segments;

        /* locate module controller */
        if ($located = $this->locate($segments))
            return $located;

        /* use a default 404_override controller */
        if (isset($this->routes['404_override']) AND $this->routes['404_override']) {
            $segments = explode('/', $this->routes['404_override']);
            if ($located = $this->locate($segments))
                return $located;
        }

        /* no controller found */
        show_404();
    }

    /** Locate the controller * */
    public function locate($segments) {
        $this->located = 0;
        $ext = $this->config->item('controller_suffix') . EXT;

        /* use module route if available */
        if (isset($segments[0]) && $routes = Modules::parse_routes($segments[0], implode('/', $segments))) {
            $segments = $routes;
        }
        /* get the segments array elements */
        list($module, $directory, $controller) = array_pad($segments, 3, NULL);

        // =========================================================================
        // Replace underscores with dashes 
        // -- added by Mark Price

        $module = str_replace('_', ' ', $module);
        $module = str_replace('-', '_', $module);

        $controller = str_replace('_', ' ', $controller);
        $controller = str_replace('-', '_', $controller);

        $directory = str_replace('_', ' ', $directory);
        $directory = str_replace('-', '_', $directory);

        /* check modules */
        foreach (Modules::$locations as $location => $offset) {
            /* module exists? */
            if (is_dir($source = $location . $module . '/controllers/')) {
                $this->module = $module;
                $this->directory = $offset . $module . '/controllers/';

                /* module sub-controller exists? */
                if ($directory) {
                    /* module sub-directory exists? */
                    if (is_dir($source . $directory . '/')) {
                        $source .= $directory . '/';
                        $this->directory .= $directory . '/';

                        /* module sub-directory controller exists? */
                        if ($controller) {
                            if (is_file($source . ucfirst($controller) . $ext)) {
                                $this->located = 3;
                                return array_slice($segments, 2);
                            } else
                                $this->located = -1;
                        }
                    }
                    else
                    if (is_file($source . ucfirst($directory) . $ext)) {
                        $this->located = 2;
                        return array_slice($segments, 1);
                    } else
                        $this->located = -1;
                }

                /* module controller exists? */
                if (is_file($source . ucfirst($module) . $ext)) {
                    $this->located = 1;
                    return $segments;
                }

                // =========================================================================
                // If a controller name matches the module name inside the admin directory dont force controller name in URI 
                // Ex: admin/module/method 
                // -- added by Mark Price

                if ($directory == 'admin') {
                    if ($controller AND is_file($source . $module . $ext)) {
                        $segments = array_slice($segments, 2);
                        array_unshift($segments, $module);
                        return $segments;
                    }
                }
            }
        }

        if (!empty($this->directory))
            return;

        /* application sub-directory controller exists? */
        if ($directory) {
            if (is_file(APPPATH . 'controllers/' . $module . '/' . ucfirst($directory) . $ext)) {
                $this->directory = $module . '/';
                return array_slice($segments, 1);
            }

            /* application sub-sub-directory controller exists? */
            if ($controller) {
                if (is_file(APPPATH . 'controllers/' . $module . '/' . $directory . '/' . ucfirst($controller) . $ext)) {
                    $this->directory = $module . '/' . $directory . '/';
                    return array_slice($segments, 2);
                }
            }
        }

        /* application controllers sub-directory exists? */
        if (is_dir(APPPATH . 'controllers/' . $module . '/')) {
            $this->directory = $module . '/';
            return array_slice($segments, 1);
        }

        /* application controller exists? */
        if (is_file(APPPATH . 'controllers/' . ucfirst($module) . $ext)) {
            return $segments;
        }

        $this->located = -1;
    }

    public function set_class($class) {
        // =========================================================================
        // Replace underscores with dashes 
        // -- added by Mark Price

        $this->class = str_replace('_', ' ', $class) . $this->config->item('controller_suffix');
        $this->class = str_replace('-', '_', $this->class) . $this->config->item('controller_suffix');

        // =========================================================================
    }

    public function set_method($method) {
        // =========================================================================
        // Replace underscores with dashes 
        // -- added by Mark Price

        $this->method = str_replace('_', ' ', $method);
        $this->method = str_replace('-', '_', $this->method);

        // =========================================================================
    }

}
