<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CMS Canvas
 *
 * @package 		CMSCanvas
 * @author			Phil Sturgeon - PyroCMS Development Team
 * @modified        Mark Price
 *
 * Central library for Plugin logic
 */
abstract class Plugin {

    private $attributes = array();
    private $content = '';
    private $path = '';

    // ------------------------------------------------------------------------

    function __get($var) {
        return get_instance()->$var;
    }

    // ------------------------------------------------------------------------

    /**
     * content
     *
     * Returns content passed from the tag parser
     *
     * @return 	string
     */
    public function content() {
        return preg_replace('/\s+$/', ' ', $this->content);
    }

    // ------------------------------------------------------------------------

    /**
     * attributes
     *
     * Returns the array of attributes
     *
     * @return 	array
     */
    public function attributes() {
        return $this->attributes;
    }

    // ------------------------------------------------------------------------

    /**
     * attribute
     *
     * Returns a set attribute otherwise returns the passed default param
     *
     * @param	array - Params passed from view
     * @param	array - Array of default params
     * @return 	array
     */
    public function attribute($param, $default = NULL) {
        return isset($this->attributes[$param]) ? $this->attributes[$param] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Set plugin path
     *
     * Sets content and attributes passed from Simpletags
     *
     * @param	array - Params passed from Plugin library process
     * @return 	none
     */
    public function set_path($path) {
        $this->path = dirname($path);
    }

    // ------------------------------------------------------------------------

    /**
     * Set data
     *
     * Sets content and attributes passed from Simpletags
     *
     * @param	array - Params passed from Plugin library process
     * @return 	none
     */
    public function set_data($content, $attributes) {
        $content AND $this->content = $content;
        $attributes AND $this->attributes = $attributes;
    }
}

/**
 * Plugins repository
 */
class Plugins {

    private $exclude_methods = array(
        'install',
        'update',
        'uninstall'
    );
    private $loaded = array();

    function __construct() {
        $this->_ci = & get_instance();
    }

    public function get_plugin_class($module_name = null) {
        if (!$module_name) {
            $class = CI::$APP->router->fetch_module();
        } else {
            $class = $module_name;
        }
        // Maybe it's a module. Plugins used as a module would typically have views or an admin interface
        if (file_exists($path = APPPATH . 'modules/' . $class . '/plugin.php')) {
            $dirname = dirname($path) . '/';

            // Set the module as a package so I can load stuff
            $this->_ci->load->add_package_path($dirname);

            $class = strtolower($class);
            $class_name = ucfirst($class) . '_plugin';

            if (!isset($this->loaded[$class])) {
                include $path;
                $this->loaded[$class] = TRUE;
            }

            if (!class_exists($class_name)) {
                log_message('error', 'Plugin class "' . $class_name . '" does not exist.');

                return FALSE;
            }

            $class_init = new $class_name;
            $class_init->set_path($path);

            $this->_ci->load->remove_package_path($dirname);

            return $class_init;
        }
        return FALSE;
    }

    public function locate($plugin, $attributes, $content, $data) {
        if (strpos($plugin, ':') === FALSE) {
            return FALSE;
        }

        // Setup our paths from the data array
        list($class, $method) = explode(':', $plugin);

        if (in_array($method, $this->exclude_methods))
            return FALSE;
        // Maybe plugin is a single file under the plugins directory
        if (file_exists($path = APPPATH . 'plugins/' . $class . EXT)) {
            return $this->_process($path, $class, $method, $attributes, $content, $data);
        }

        // Maybe it's a module. Plugins used as a module would typically have views or an admin interface
        if (file_exists($path = APPPATH . 'modules/' . $class . '/plugin.php')) {
            $dirname = dirname($path) . '/';

            // Set the module as a package so I can load stuff
            $this->_ci->load->add_package_path($dirname);

            $response = $this->_process($path, $class, $method, $attributes, $content, $data);

            $this->_ci->load->remove_package_path($dirname);

            return $response;
        }

        log_message('error', 'Unable to load: ' . $class);

        return '';
    }

    // --------------------------------------------------------------------

    /**
     * Process
     *
     * Just process the class
     *
     * @access	private
     * @param	object
     * @param	string
     * @param	array
     * @return	mixed
     */
    private function _process($path, $class, $method, $attributes, $content, $data) {
        $class = strtolower($class);
        $class_name = ucfirst($class) . '_plugin';

        if (!isset($this->loaded[$class])) {
            include $path;
            $this->loaded[$class] = TRUE;
        }

        if (!class_exists($class_name)) {
            log_message('error', 'Plugin class "' . $class_name . '" does not exist.');

            return FALSE;
        }

        $class_init = new $class_name;
        $class_init->set_path($path);
        $class_init->set_data($content, $attributes);

        if (!is_callable(array($class_init, $method))) {
            // But does a property exist by that name?
            if (property_exists($class_init, $method)) {
                return TRUE;
            }

            log_message('error', 'Plugin method "' . $method . '" does not exist on class "' . $class_name . '".');

            return FALSE;
        }

        return call_user_func(array($class_init, $method), $data);
    }

}