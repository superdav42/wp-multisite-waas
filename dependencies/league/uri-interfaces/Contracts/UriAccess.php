<?php

/**
 * League.Uri (https://uri.thephpleague.com)
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace WP_Ultimo\Dependencies\League\Uri\Contracts;

use WP_Ultimo\Dependencies\Psr\Http\Message\UriInterface as Psr7UriInterface;
interface UriAccess
{
    public function getUri() : UriInterface|Psr7UriInterface;
    public function getUriString() : string;
}
