<?php

namespace Northrook\Symfony\Assets;

use Northrook\Logger\Log;
use Northrook\Symfony\Assets\Core\SingleFileTrait;
use Northrook\Symfony\Core\File;
use Northrook\Types\Path;
use Symfony\Component\Filesystem\Exception\IOException;

class Script extends Core\Asset
{
    public readonly Path $path;

    function build() : void {

        // add in vendor name if it exists
        $asset = $this->source->filename;

        $rootDir = File::pathfinder()->getParameter('dir.root');


        $this->path = File::path(
            "dir.public.assets/scripts/$asset.js",
        );

        File::copy( $this->source->value, $this->path->value, static::$cacheBuster );

        dd(
            $this,
            $this->source->value,
            $rootDir,
            $this->path
        );
    }

    public function __toString() {
        $version = $this::$cacheBuster ? time() : filemtime( $this->path->value );
        return $this->asUrl( $this->path ) . '?v=' . $version;
    }
}