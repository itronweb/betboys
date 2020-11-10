<?php

use Carbon\Carbon;

require_once Soccerama_dir . 'SocceramaClient.php';

class LiveScore extends SocceramaClient {

    public function byDate ( $date ) {
        if ( $date instanceof Carbon ) {
            $date = $date->format('Y-m-d');
        }

        return $this->callData('livescores/date/' . $date);
    }

    public function byMatchId ( $matchId ) {
        return $this->call('livescores/match/' . $matchId);
    }

    public function today () {
        return $this->callData('livescores');
    }

    public function now () {
        return $this->callData('livescores/now');
    }

}
