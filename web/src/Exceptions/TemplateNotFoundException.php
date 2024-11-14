<?php

namespace PvPGNTracker\Exceptions;

class TemplateNotFoundException extends \InvalidArgumentException
{
  public function __construct(\PvPGNTracker\Libraries\Template|string $value, \Throwable $previous = null)
  {
    $v = is_string($value) ? $value : $value->getTemplateFile();
    parent::__construct(\sprintf('Template not found: %s', $v), 0, $previous);
  }
}
