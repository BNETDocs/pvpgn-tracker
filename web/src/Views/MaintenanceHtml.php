<?php

namespace PvPGNTracker\Views;

class MaintenanceHtml extends \PvPGNTracker\Views\Base\Html
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\Maintenance)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        (new \PvPGNTracker\Libraries\Template($model, 'Maintenance'))->invoke();
    }
}
