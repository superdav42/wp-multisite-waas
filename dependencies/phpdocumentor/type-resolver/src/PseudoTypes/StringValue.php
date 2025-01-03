<?php

/*
 * This file is part of phpDocumentor.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @link      http://phpdoc.org
 *
 */
declare (strict_types=1);
namespace WP_Ultimo\Dependencies\phpDocumentor\Reflection\PseudoTypes;

use WP_Ultimo\Dependencies\phpDocumentor\Reflection\PseudoType;
use WP_Ultimo\Dependencies\phpDocumentor\Reflection\Type;
use WP_Ultimo\Dependencies\phpDocumentor\Reflection\Types\String_;
use function sprintf;
/** @psalm-immutable */
class StringValue implements PseudoType
{
    private string $value;
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    public function getValue() : string
    {
        return $this->value;
    }
    public function underlyingType() : Type
    {
        return new String_();
    }
    public function __toString() : string
    {
        return sprintf('"%s"', $this->value);
    }
}
