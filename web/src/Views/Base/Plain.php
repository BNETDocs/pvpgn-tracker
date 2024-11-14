<?php /* vim: set colorcolumn=: */

namespace PvPGNTracker\Views\Base;

abstract class Plain implements \PvPGNTracker\Interfaces\View
{
  public const MIMETYPE_PLAIN = 'text/plain';

  /**
   * Provides the MIME-type that this View prints.
   *
   * @return string The MIME-type for this View class.
   */
  public static function mimeType(): string
  {
    return self::MIMETYPE_PLAIN;
  }
}
