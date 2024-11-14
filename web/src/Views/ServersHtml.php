<?php

namespace PvPGNTracker\Views;

class ServersHtml extends \PvPGNTracker\Views\Base\Html
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\Servers)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        (new \PvPGNTracker\Libraries\Template($model, 'Servers'))->invoke();
    }
}