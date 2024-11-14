<?php /* vim: set colorcolumn=: */

namespace PvPGNTracker\Controllers;

abstract class Controller implements \PvPGNTracker\Interfaces\Controller
{
  /**
   * The Model to be set by subclasses and used by a View.
   *
   * @var \PvPGNTracker\Interfaces\Model|null
   */
  public ?\PvPGNTracker\Interfaces\Model $model = null;
}
