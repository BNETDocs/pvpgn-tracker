<?php /* vim: set colorcolumn=: */

namespace PvPGNTracker\Interfaces;

interface View
{
  /**
   * Invoked by the Controller class to print the result of a request.
   *
   * @param \PvPGNTracker\Interfaces\Model $model The object that implements the Model interface.
   * @return void
   */
  public static function invoke(\PvPGNTracker\Interfaces\Model $model): void;

  /**
   * Provides the MIME-type that this View prints.
   *
   * @return string The MIME-type for this View class.
   */
  public static function mimeType(): string;
}
