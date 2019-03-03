<?php

namespace PvPGNTracker\Views;

use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\Template;
use \CarlBennett\MVC\Libraries\View;
use \PvPGNTracker\Models\PageNotFound as PageNotFoundModel;

class PageNotFoundHtml extends View {

    public function getMimeType() {
        return 'text/html;charset=utf-8';
    }

    public function render( Model &$model ) {
        if ( !$model instanceof PageNotFoundModel ) {
            throw new IncorrectModelException();
        }
        ( new Template( $model, 'PageNotFound' ))->render();
    }

}
