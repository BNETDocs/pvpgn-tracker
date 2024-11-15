<?php

namespace PvPGNTracker\Models\Server;

class View extends \PvPGNTracker\Models\Model implements \JsonSerializable
{
    public ?\PvPGNTracker\Libraries\Solicitation $solicitation = null;

    public function jsonSerialize(): mixed
    {
        return array_merge(parent::jsonSerialize(), ['solicitation' => $this->solicitation]);
    }
}
