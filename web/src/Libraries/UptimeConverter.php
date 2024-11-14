<?php

namespace PvPGNTracker\Libraries;

class UptimeConverter {
    protected $interval;

    public function __construct( $interval ) {
        $this->interval = $interval;
    }

    public function __tostring() {
        $years = 0;
        $months = 0;
        $days = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = $this->interval;

        $minutes  = intdiv( $seconds, 60 );
        $seconds %= 60;

        $hours    = intdiv( $minutes, 60 );
        $minutes %= 60;

        $days     = intdiv( $hours, 24 );
        $hours   %= 24;

        $months   = intdiv( $days, 30 );
        $days    %= 30;

        $years    = intdiv( $months, 12 );
        $months  %= 12;

        $fmt = '';

        if ( $years   !== 0 ) $fmt .= $years   . 'y ';
        if ( $months  !== 0 ) $fmt .= $months  . 'mo ';
        if ( $days    !== 0 ) $fmt .= $days    . 'd ';
        if ( $hours   !== 0 ) $fmt .= $hours   . 'h ';
        if ( $minutes !== 0 ) $fmt .= $minutes . 'm ';
        if ( $seconds !== 0 ) $fmt .= $seconds . 's ';

        return trim( $fmt );
    }
}
