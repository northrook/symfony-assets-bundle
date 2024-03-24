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
        $this->path = File::path( "dir.public.assets/stylesheets/{$this->source->filename}" );

        if ( !$this->path->exists || (
                filemtime( $this->path->value ) < filemtime( $this->source->value )
            ) ) {

            try {
                $this->filesystem->copy( $this->source->value, $this->path->value );
            }
            catch ( IOException $e ) {
                Log::Error(
                    message : "Failed to copy stylesheet source to path: {path}.",
                    context : [ 'path' => $this->path ],
                );
            }
        }
    }

    public function __toString() {
        $version = $this::$cacheBuster ? time() : filemtime( $this->path->value );
        return $this->asUrl( $this->path ) . '?v=' . $version;
    }
}