<?php

namespace PvPGNTracker\Views;

class StatusPlain extends \PvPGNTracker\Views\Base\Plain
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\Status)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        echo \PvPGNTracker\Libraries\ArrayFlattener::flatten($model->status);
    }
}
