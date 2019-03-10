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

        $fmt = sprintf(
            '%dy %dmo %dd %dh %dm %ds',
            $years, $months, $days, $hours, $minutes, $seconds
        );

        $fmt = str_replace( '0y ', '', $fmt );
        $fmt = str_replace( '0mo ', '', $fmt );
        $fmt = str_replace( '0d ', '', $fmt );
        $fmt = str_replace( '0h ', '', $fmt );
        $fmt = str_replace( '0m ', '', $fmt );

        return $fmt;
    }
}
