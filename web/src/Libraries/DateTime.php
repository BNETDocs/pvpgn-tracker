<?php /* vim: set colorcolumn=: */

namespace PvPGNTracker\Libraries;

use \DateTimeInterface;

/**
 * This subclass of PHP's DateTime exists so that it can be serialized to custom formats.
 */
class DateTime extends \DateTime implements \JsonSerializable
{
    public function jsonSerialize() : mixed
    {
        return [
            'iso' => $this->format(DateTimeInterface::RFC2822),
            'tz' => $this->format('e'),
            'unix' => (int) $this->format('U'),
        ];
    }

    public function __toString() : string
    {
        return \sprintf('%d %s', $this->format('U'), $this->format(DateTimeInterface::RFC2822));
    }
}
