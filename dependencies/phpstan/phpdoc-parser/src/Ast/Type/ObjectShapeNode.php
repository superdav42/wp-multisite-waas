<?php

declare (strict_types=1);
namespace WP_Ultimo\Dependencies\PHPStan\PhpDocParser\Ast\Type;

use WP_Ultimo\Dependencies\PHPStan\PhpDocParser\Ast\NodeAttributes;
use function implode;
class ObjectShapeNode implements TypeNode
{
    use NodeAttributes;
    /** @var ObjectShapeItemNode[] */
    public $items;
    /**
     * @param ObjectShapeItemNode[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }
    public function __toString() : string
    {
        $items = $this->items;
        return 'object{' . implode(', ', $items) . '}';
    }
}