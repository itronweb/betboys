<?php

require_once Soccerama_dir . 'SocceramaClient.php';

class Bookmaker extends SocceramaClient {

    public function all () {
        return $this->callData('bookmakers');
    }

}
