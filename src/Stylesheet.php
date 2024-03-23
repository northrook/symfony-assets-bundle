<?php

namespace Northrook\Symfony\Assets;

use Northrook\Elements\Link;
use Northrook\Logger\Log;
use Northrook\Support\Arr;
use Northrook\Support\Str;
use Northrook\Types\Path;
use Symfony\Component\Filesystem\Exception\IOException;

class Stylesheet extends Asset
{
    public readonly Path   $source;
    public readonly Path   $path;
    public readonly string $name;
    public readonly ?string $value;

    public function __construct(
        Path | string $source,
        ?string       $name = null,
    ) {
        $this->source = $source instanceof Path ? $source : new Path( $source );
        $this->path   = $this->pathfinder()->get( "dir.public.assets/stylesheets/{$this->source->filename}" );
        $this->name   = Str::key( $name ?? $this->source->filename );

        if ( $this->path->exists ) {
            $this->value = $this->path->value;
            return;
        }

        if ( ! $this->source->exists ) {
            Log::Error(
                message : "Stylesheet source does not exist: {source}.",
                context : [ 'source' => $this->source ],
            );
            $this->value = null;
            return;
        }

        if ( ! $this->path->exists || (
            filemtime( $this->path->value ) < filemtime( $this->source->value )
            )) {
            try {
            $this->filesystem()->copy( $this->source->value, $this->path->value );
            $this->value = $this->path->value;
            } catch ( IOException $e ) {
                Log::Error(
                    message : "Failed to copy stylesheet source to path: {path}.",
                    context : [ 'path' => $this->path ],
                );
                $this->value = null;
            }
        }
    }

    public function print() : string {
        return '';
    }
}