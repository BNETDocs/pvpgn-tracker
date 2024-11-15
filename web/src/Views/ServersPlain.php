<?php

namespace PvPGNTracker\Views;

class ServersPlain extends \PvPGNTracker\Views\Base\Plain
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\Servers)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        echo $model->servers ? \PvPGNTracker\Libraries\ArrayFlattener::flatten($model->servers) : 'null';
    }
}
