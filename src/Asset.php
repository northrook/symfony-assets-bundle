<?php

namespace Northrook\Symfony\Assets;

use Northrook\Types\ID;
use Northrook\Types\Path;

final class Asset extends Core\AbstractAsset
{
    public readonly string $path;

    public static function get(
        Path | string $source,
        ?string       $name = null,
        ?id           $id = null,
        ?string       $directory = null,
    ) : self {
        return new self(
            $source,
            $name,
            $id,
            $directory
        );
    }

    public function __toString() : string {
        $this->path = $this->publicAsset();
        return $this->asUrl( $this->path ) . '?v=' . $this->version( $this->path );
    }
}