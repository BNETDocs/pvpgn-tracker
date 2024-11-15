<?php

namespace PvPGNTracker\Views;

class PageNotFoundPlain extends \PvPGNTracker\Views\Base\Plain
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\PageNotFound)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        echo \PvPGNTracker\Libraries\ArrayFlattener::flatten($model);
    }
}
