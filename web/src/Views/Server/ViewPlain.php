<?php

namespace PvPGNTracker\Views\Server;

class ViewPlain extends \PvPGNTracker\Views\Base\Plain
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\Server\View)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        echo $model->solicitation ? \PvPGNTracker\Libraries\ArrayFlattener::flatten($model->solicitation) : 'null';
    }
}
