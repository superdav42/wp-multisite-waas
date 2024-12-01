<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */
namespace WP_Ultimo\Dependencies\ScssPhp\ScssPhp\Block;

use WP_Ultimo\Dependencies\ScssPhp\ScssPhp\Block;
use WP_Ultimo\Dependencies\ScssPhp\ScssPhp\Type;
/**
 * @internal
 */
class MediaBlock extends Block
{
    /**
     * @var string|array|null
     */
    public $value;
    /**
     * @var array|null
     */
    public $queryList;
    public function __construct()
    {
        $this->type = Type::T_MEDIA;
    }
}