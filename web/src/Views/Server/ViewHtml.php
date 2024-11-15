<?php

namespace PvPGNTracker\Views\Server;

class ViewHtml extends \PvPGNTracker\Views\Base\Html
{
    public static function invoke(\PvPGNTracker\Interfaces\Model $model): void
    {
        if (!$model instanceof \PvPGNTracker\Models\Server\View)
        {
            throw new \PvPGNTracker\Exceptions\InvalidModelException($model);
        }

        \header(\sprintf('Content-Type: %s;charset=utf-8', self::mimeType()));
        (new \PvPGNTracker\Libraries\Template($model, 'Server/View'))->invoke();
    }
}