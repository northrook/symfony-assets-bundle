<?php

namespace Northrook\Symfony\Assets;


class Stylesheet extends Core\AbstractAsset
{

    protected ?string $directory = 'styles';

    public readonly string $path;

    public function __toString() : string {
        $this->path = $this->publicAsset();
        return $this->asUrl( $this->path ) . '?v=' . $this->version( $this->path );
    }
}