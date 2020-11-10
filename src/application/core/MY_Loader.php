<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
/* load the MX_Loader class */
require APPPATH . "third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    protected $_ci_presenter;
    protected $_ci_repository;

    /**
     * list of loaded eloquents
     * @var type 
     */
    private $_ci_eloquents = [];

    /*
     * Theme
     *
     * Includes and eval a php file located in the themes directory
     * Works the same as the MVC view just different path
     *
     * @param string
     * @param array
     * @param bool
     * @param string
     * @return sring
     */

    public function theme($view, $vars = array(), $return = FALSE, $path = 'themes') {
        $absolute_path = CMS_ROOT . trim($path, '/') . '/';

        $this->_ci_view_paths = array($absolute_path => TRUE) + $this->_ci_view_paths;

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    // --------------------------------------------------------------------

    /*
     * Theme XML
     *
     * Reads, escapes, and evaluates XML with php located in the themes directory
     *
     * @param string
     * @param array
     * @param bool
     * @param string
     * @return string
     */
    public function theme_xml($view, $vars = array(), $return = FALSE, $path = 'themes') {
        $absolute_path = CMS_ROOT . trim($path, '/') . '/';

        $this->_ci_view_paths = array($absolute_path => TRUE) + $this->_ci_view_paths;

        return $this->_ci_xml(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    // --------------------------------------------------------------------

    /*
     * XML
     *
     * Reads, escapes, and evaluates XML with php
     *
     * @param string
     * @param array
     * @param bool
     * @param string
     * @return string
     */
    public function xml($view, $vars = array(), $return = FALSE) {
        return $this->_ci_xml(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    // --------------------------------------------------------------------

    /*
     * CI XML
     *
     * Reads, escapes, and evaluates XML with php
     *
     * @param array
     * @return string
     */
    protected function _ci_xml($_ci_data) {
        // Set the default data variables
        foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val) {
            $$_ci_val = (!isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
        }

        $file_exists = FALSE;

        // Set the path to the requested file
        if ($_ci_path != '') {
            $_ci_x = explode('/', $_ci_path);
            $_ci_file = end($_ci_x);
        } else {
            $_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
            $_ci_file = ($_ci_ext == '') ? $_ci_view . '.php' : $_ci_view;

            foreach ($this->_ci_view_paths as $view_file => $cascade) {
                if (file_exists($view_file . $_ci_file)) {
                    $_ci_path = $view_file . $_ci_file;
                    $file_exists = TRUE;
                    break;
                }

                if (!$cascade) {
                    break;
                }
            }
        }

        if (!$file_exists && !file_exists($_ci_path)) {
            show_error('Unable to load the requested file: ' . $_ci_file);
        }

        // This allows anything loaded using $this->load (views, files, etc.)
        // to become accessible from within the Controller and Model functions.

        $_ci_CI = & get_instance();
        foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var) {
            if (!isset($this->$_ci_key)) {
                //$this->$_ci_key =& $_ci_CI->$_ci_key;
                $this->$_ci_key = $_ci_CI->$_ci_key;
            }
        }

        /*
         * Extract and cache variables
         *
         * You can either set variables using the dedicated $this->load_vars()
         * function or via the second parameter of this function. We'll merge
         * the two types and cache them so that views that are embedded within
         * other views can have access to these variables.
         */
        if (is_array($_ci_vars)) {
            $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
        }
        extract($this->_ci_cached_vars);

        /*
         * Buffer the output
         *
         * We buffer the output for two reasons:
         * 1. Speed. You get a significant speed boost.
         * 2. So that the final rendered template can be
         * post-processed by the output class.  Why do we
         * need post processing?  For one thing, in order to
         * show the elapsed page load time.  Unless we
         * can intercept the content right before it's sent to
         * the browser and then stop the timer it won't be accurate.
         */
        // If the PHP installation does not support short tags we'll
        // do a little string replacement, changing the short tags
        // to standard PHP echo statements.

        $content = file_get_contents($_ci_path);

        if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE) {
            $content = str_replace('<?=', '<?php echo ', file_get_contents($_ci_path));
        }

        if (strpos($content, '<?xml') !== FALSE) {
            $content = preg_replace('/\<\?xml(.*?)\?\>/', '<XML\\1/XML>', $content);
        }

        $_ci_CI->output->set_content_type('text/xml');

        ob_start();
        eval('?>' . preg_replace("/;*\s*\?>/", "; ?>", $content));
        $content = ob_get_contents();
        @ob_end_clean();

        if (strpos($content, '<XML')) {
            preg_replace('/\<XML(.*?)\/XML\>/', '<?xml\1?>', $content);
        }

        ob_start();

        echo $content;

        log_message('debug', 'File loaded: ' . $_ci_path);

        // Return the file data if requested
        if ($_ci_return === TRUE) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }

        /*
         * Flush the buffer... or buff the flusher?
         *
         * In order to permit views to be nested within
         * other views, we need to flush the content back out whenever
         * we are beyond the first level of output buffering so that
         * it can be seen and included properly by the first included
         * template and any subsequent ones. Oy!
         *
         */
        if (ob_get_level() > $this->_ci_ob_level + 1) {
            ob_end_flush();
        } else {
            $_ci_CI->output->append_output(ob_get_contents());
            @ob_end_clean();
        }
    }

    /** Load a module view * */
    public function view($view, $vars = array(), $return = FALSE) {
        list($path, $_view) = $this->find_view_in_theme($view, $this->_module, 'views/');
        if ($path != FALSE) {
            $this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
            $view = $_view;
        } else {
            list($path, $_view) = Modules::find($view, $this->_module, 'views/');
            if ($path != FALSE) {
                $this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
                $view = $_view;
            }
        }

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    /**
     * Find a file
     * Scans for files located within modules directories.
     * Also scans application directories for models, plugins and views.
     * Generates fatal error if file not found.
     * */
    public function find_view_in_theme($file, $module, $base) {

        $segments = explode('/', $file);

        $file = array_pop($segments);
        $file_ext = (pathinfo($file, PATHINFO_EXTENSION)) ? $file : $file . EXT;

        $path = ltrim(implode('/', $segments) . '/', '/');
        $module ? $modules[$module] = $path : $modules = array();

        if (!empty($segments)) {
            $modules[array_shift($segments)] = ltrim(implode('/', $segments) . '/', '/');
        }
        foreach (Modules::$locations as $location => $offset) {
            foreach ($modules as $module => $subpath) {
                $fullpath = CI::$APP->template->theme_path . '/' . CI::$APP->template->theme . '/views/modules/' . $module . '/' . $subpath;

                if (is_file($fullpath . $file_ext))
                    return array($fullpath, $file);
            }
        }

        return array(FALSE, $file);
    }

    /**
     * Load a module eloquent model
     * @param type $model
     * @param type $object_name
     * @param type $connect
     * @return \MY_Loader
     */
    public function eloquent($model, $object_name = NULL, $connect = FALSE) {
        if (is_array($model))
            return $this->eloquents($model);

        ($_alias = $object_name) OR $_alias = basename($model);

        if (in_array($_alias, $this->_ci_eloquents, TRUE))
            return $this;

        /* check module */
        list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');
        if ($path == FALSE) {
            return $this;
        } else {
            Modules::load_file($_model, $path);

            $model = ucwords($_model);
            $this->_ci_eloquents[] = $_alias;
        }
        return $this;
    }

    /** Load an array of models * */
    public function eloquents($models) {
        foreach ($models as $_model)
            $this->eloquent($_model);
        return $this;
    }

    /**
     * 
     * @param type $repository
     * @param type $object_name
     * @param type $connect
     * @return \MY_Loader
     */
    public function repository($repository, $object_name = NULL, $connect = FALSE) {
        ($_alias = $object_name) OR $_alias = basename($repository);

        /* check module */
        list($path, $_repo) = Modules::find(strtolower($repository), $this->_module, 'repositories/');
        if ($path != FALSE) {
            Modules::load_file($_repo, $path);
            $repository = ucwords($_repo);
            $this->_ci_repository[] = $_alias;
        }
        return $this;
    }
    /**
     * 
     * @param type $repository
     * @param type $object_name
     * @param type $connect
     * @return \MY_Loader
     */
    public function presenter($presenter, $object_name = NULL, $connect = FALSE) {
        ($_alias = $object_name) OR $_alias = basename($presenter);

        /* check module */
        list($path, $_presneter) = Modules::find(strtolower($presenter), $this->_module, 'presenters/');
        if ($path != FALSE) {
            Modules::load_file($_presneter, $path);
            $presenter = ucwords($_presneter);
            $this->_ci_presenter[] = $_alias;
        }
        return $this;
    }

    /**
     * Load a module sentinel with illuminate model
     * @param type $module
     * @param type $path
     * @return \MY_Loader
     */
    public function sentinel($module = 'users', $path = 'third_party/') {
        if (isset(CI::$APP->sentinel))
            return $this;
        //load sentinel
        list($path, $_model) = Modules::find('Sentinel_loader', $module, $path);
        Modules::load_file($_model, $path);
        $loader = new Sentinel_loader();
        CI::$APP->sentinel = $loader->createSentinel();
        return $this;
    }

}
