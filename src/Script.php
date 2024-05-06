<?php

namespace Northrook\Symfony\Assets;

class Script extends Core\AbstractAsset
{
    protected ?string $directory = 'scripts';

    public readonly string $path;

    public function __toString() : string {
        if ( !isset( $this->path ) ) {
            $this->path = $this->publicAsset();
        }
        return $this->asUrl( $this->path ) . '?v=' . $this->version( $this->path );
    }
}