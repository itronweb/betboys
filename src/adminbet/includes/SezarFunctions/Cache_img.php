<?php

    /** Simple Image Cacher for Instagram Filter in Iran */
    /** Code by David@Refoua.me */

    define( 'IMG_CACHE_DIR', './upload/sezar/sezar-cache/' );
    define( 'IMG_CACHE_URL', '/upload/sezar/sezar-cache/' );

    if ( !is_dir(IMG_CACHE_DIR) && !mkdir(IMG_CACHE_DIR) ) {
        trigger_error("Can not create Sezar cache directory!!!");
    }

    function getCachedImg( $url ) {

        // return only if this is true after we're done
        $success = false;

        // first check if we have a valid url
        if ( !preg_match( '@^https?:\/\/.+(?P<extension>.\w+)?(?:\?.*)?$@iU', $url, $matches ) ) {
            // not a valid one, just return the regular path
            $success = false;
        }

        else {

            // make a footprint
            $hash = md5( $url ) . $matches['extension'];

            $result = IMG_CACHE_URL . $hash;

            // check if we have the cached in our directory
            if ( is_file( IMG_CACHE_DIR . $hash ) ) {
                $success = true;
            }

            else {
                $image = file_get_contents( $url );
                if ( !empty($image) ) $success = file_put_contents( IMG_CACHE_DIR . $hash, $image );
                if ( $success == true && is_file( IMG_CACHE_DIR . $hash ) ) {
                    $success = false;
                }

            }

            if ( empty($hash) ) $success = false;
            

        }

        return $success ? $result : $url;

    }
