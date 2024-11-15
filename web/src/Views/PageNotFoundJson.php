<?php

namespace PvPGNTracker\Views;

class PageNotFoundJson extends \PvPGNTracker\Views\Base\Json
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\PageNotFound)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        echo json_encode($model, self::jsonFlags());
    }
}
