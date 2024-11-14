<?php /* vim: set colorcolumn=: */

namespace PvPGNTracker\Views\Base;

abstract class Json implements \PvPGNTracker\Interfaces\View
{
  public const MIMETYPE_JSON = 'application/json';

  /**
   * Identifies if the client is a web browser by checking the HTTP User-Agent.
   *
   * @return bool Whether the client is a web browser.
   */
  public static function isBrowser(): bool
  {
    $ua = \getenv('HTTP_USER_AGENT');
    if (!is_string($ua) || empty($ua)) return false;
    return preg_match('/[Chrome|Edge|Firefox|Mozilla|MSIE|Opera|OPR|Safari]/', $ua) === 1;
  }

  /**
   * Gets the standard flags to call with json_encode() in subclasses.
   *
   * @return integer The flags to pass to json_encode().
   */
  public static function jsonFlags(): int
  {
    return \JSON_PRESERVE_ZERO_FRACTION | \JSON_THROW_ON_ERROR | (self::isBrowser() ? \JSON_PRETTY_PRINT : 0);
  }

  /**
   * Provides the MIME-type that this View prints.
   *
   * @return string The MIME-type for this View class.
   */
  public static function mimeType(): string
  {
    return self::MIMETYPE_JSON;
  }
}
