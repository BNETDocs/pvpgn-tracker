<?php

namespace PvPGNTracker\Views\Server;

class ViewJson extends \PvPGNTracker\Views\Base\Json
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\Server\View)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        echo json_encode($model->solicitation, self::jsonFlags());
    }
}
