<?php

use Carbon\Carbon;

require_once Soccerama_dir . 'SocceramaClient.php';

/**
 * @link https://soccerama.pro/docs/1.2/matches Documents for include types and methods
 */
class Match extends SocceramaClient {

    /**
     * Accepts dates as Carbon or 'YYYY-mm-dd' strings
     */
    public function byDate ( $fromDate , $toDate ) {
        if ( $fromDate instanceof Carbon ) {
            $fromDate = $fromDate->format('Y-m-d');
        }
        if ( $toDate instanceof Carbon ) {
            $toDate = $toDate->format('Y-m-d');
        }
        return $this->callData('fixtures/' . $fromDate . '/' . $toDate);
    }
	
	public function oneDate ( $date ) {
        if ( $date instanceof Carbon ) {
            $date = $date->format('Y-m-d');
        }
        
        return $this->callData('fixtures/date/' . $date );
    }

    public function byId ( $matchId ) {
        return $this->call('fixtures/' . $matchId);
    }

}
