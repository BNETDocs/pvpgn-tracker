<?php

namespace PvPGNTracker\Views;

use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\Template;
use \CarlBennett\MVC\Libraries\View;
use \PvPGNTracker\Models\Maintenance as MaintenanceModel;

class MaintenanceHtml extends View {

    public function getMimeType() {
        return 'text/html;charset=utf-8';
    }

    public function render( Model &$model ) {
        if ( !$model instanceof MaintenanceModel ) {
            throw new IncorrectModelException();
        }
        ( new Template( $model, 'Maintenance' ))->render();
    }

}
