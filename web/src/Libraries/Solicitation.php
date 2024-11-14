<?php

namespace PvPGNTracker\Libraries;

class Solicitation implements \JsonSerializable
{
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

    public int $active_channels;
    public int $active_games;
    public int $active_users;
    public string $contact_email;
    public string $contact_name;
    public int $flags;
    public string $platform;
    public string $server_address;
    public string $server_description;
    public string $server_location;
    public int $server_port;
    public string $server_url;
    public string $software;
    public int $total_games;
    public int $total_logins;
    public int $uptime;
    public string $version;

    public function jsonSerialize(): mixed
    {
        return [
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
        ];
    }
}
