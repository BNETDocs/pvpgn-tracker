<?php

namespace PvPGNTracker\Views;

class ServersJson extends \PvPGNTracker\Views\Base\Json
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\Servers)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        echo json_encode($model->servers, self::jsonFlags());
    }
}
