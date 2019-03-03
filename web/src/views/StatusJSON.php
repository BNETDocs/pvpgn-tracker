<?php

namespace PvPGNTracker\Views;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;
use \PvPGNTracker\Models\Status as StatusModel;

class StatusJSON extends View {

    public function getMimeType() {
        return 'application/json;charset=utf-8';
    }

    public function render( Model &$model ) {
        if ( !$model instanceof StatusModel ) {
            throw new IncorrectModelException();
        }
        echo json_encode( $model->status, Common::prettyJSONIfBrowser() );
    }

}
