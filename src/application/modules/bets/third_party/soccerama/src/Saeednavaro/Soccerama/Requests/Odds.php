<?php

use Carbon\Carbon;

require_once Soccerama_dir . 'SocceramaClient.php';

/**
 * @link https://soccerama.pro/docs/1.2/odds Documents for include types and methods
 */
class Odds extends SocceramaClient {

    public function byMatchId ( $matchId ) {
        return $this->callData('odds/fixture/' . $matchId);
    }

    public function byMatchAndBookmakerId ( $matchId , $bookmakerId ) {
        return $this->callData('odds/fixture/' . $matchId . '/bookmaker/' . $bookmakerId);
    }

    public function byBookmakerAndDate ( $bookmakerId , $date ) {
        if ( $date instanceof Carbon ) {
            $date = $date->format('Y-m-d');
        }

        return $this->callData('odds/bookmaker/' . $bookmakerId . '/date/' . $date);
    }

}
