<?php

namespace Northrook\Symfony\Assets;

use Northrook\Support\Str;
use Northrook\Symfony\Core\File;

class Stylesheet extends Core\Asset
{
    public readonly string $path;

    function build() : void {

        $rootDir = File::pathfinder()->getParameter( 'dir.root' );
        $asset   = 'dir.public.assets/styles/';

        if ( Str::startsWith( $this->source->value, $rootDir ) ) {
            $bundle = substr( $this->source->value, strlen( $rootDir ) );

            $bundle = Str::between( $bundle, '\\', 3, 2 );
            $bundle = trim( str_replace( [ 'symfony', 'bundle' ], '', $bundle ), '-' );

            $asset .= "$bundle/";
        }

        $this->path = File::path( "$asset{$this->source->filename}.css" );

        File::copy( $this->source->value, $this->path, static::$cacheBuster );

    }

    public function __toString() {
        $version = $this::$cacheBuster ? time() : filemtime( $this->path );
        return $this->asUrl( $this->path ) . '?v=' . $version;
    }
}