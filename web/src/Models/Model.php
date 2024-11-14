<?php

namespace PvPGNTracker\Models;

class Model implements \PvPGNTracker\Interfaces\Model, \JsonSerializable
{
    public int $_responseCode = \PvPGNTracker\Libraries\HttpCode::HTTP_INTERNAL_SERVER_ERROR;
    public array $_responseHeaders = [
        'Cache-Control' => 'max-age=0,no-cache,no-store', // disables cache in the browser for all PHP pages by default.
        'X-Frame-Options' => 'DENY' // DENY tells the browser to prevent archaic frame/iframe embeds of all pages including from ourselves (see also: SAMEORIGIN).
    ];

    public function jsonSerialize(): mixed
    {
        return []; // our two properties do not need to be included when serializing, yield an empty array.
    }
}
