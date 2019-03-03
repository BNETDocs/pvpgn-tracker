<?php

namespace PvPGNTracker\Libraries;

use \JsonSerializable;

class Solicitation implements JsonSerializable {

    // -- PvPGN Solicit UDP Datagram Format --
    // ## All ints are little-endian ##
    // (UINT16)      Packet Version (0x0002)
    // (UINT16)      Server Port
    // (UINT32)      Flags
    //  (UINT8) [32] Software
    //  (UINT8) [16] Version
    //  (UINT8) [32] Platform
    //  (UINT8) [64] Server Description
    //  (UINT8) [64] Server Location
    //  (UINT8) [96] Server URL
    //  (UINT8) [64] Contact Name
    //  (UINT8) [64] Contact Email
    // (UINT32)      Active Users
    // (UINT32)      Active Channels
    // (UINT32)      Active Games
    // (UINT32)      Uptime
    // (UINT32)      Total Games
    // (UINT32)      Total Logins
    // -- End --

    public $active_channels;
    public $active_games;
    public $active_users;
    public $contact_email;
    public $contact_name;
    public $flags;
    public $platform;
    public $server_address;
    public $server_description;
    public $server_location;
    public $server_port;
    public $server_url;
    public $software;
    public $total_games;
    public $total_logins;
    public $uptime;
    public $version;

    public function jsonSerialize() {
        return array(
            'server_address'     => $this->server_address,
            'server_port'        => $this->server_port,
            'software'           => $this->software,
            'version'            => $this->version,
            'platform'           => $this->platform,
            'server_description' => $this->server_description,
            'server_location'    => $this->server_location,
            'server_url'         => $this->server_url,
            'contact_name'       => $this->contact_name,
            'contact_email'      => $this->contact_email,
            'active_users'       => $this->active_users,
            'active_channels'    => $this->active_channels,
            'active_games'       => $this->active_games,
            'uptime'             => $this->uptime,
            'total_games'        => $this->total_games,
            'total_logins'       => $this->total_logins,
        );
    }

}
