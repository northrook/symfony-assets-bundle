<?php

namespace Northrook\Symfony\Assets\Core;

use Northrook\Logger\Log;
use Northrook\Support\Get;
use Northrook\Support\Minify;
use Northrook\Support\Str;
use Northrook\Symfony\Core\App;
use Northrook\Symfony\Core\File;
use Northrook\Types\ID;
use Northrook\Types\Path;
use Stringable;
use Symfony\Component\Uid\Uuid;

/**
 * We only really need the source {@see Path}.
 *
 * The public directory will always be the same, eg "public/assets/...".
 *
 */
abstract class AbstractAsset implements Stringable, Asset
{

    public static bool $cacheBuster = false;

    public readonly ID      $id;
    protected readonly Path $source;
    public readonly string  $name;
    protected ?string       $directory = null;

    public function __construct(
        Path | string $source,
        ?string       $name = null,
        ?id           $id = null,
        ?string       $directory = null,
    ) {
        $this->source    = $source instanceof Path ? $source : File::path( $source );
        $this->name      = Str::key( $name ?? $this->source->filename );
        $this->id        = new ID( $id ?? Uuid::v4() );
        $this->directory ??= $directory;


        if ( !$this->source->exists ) {
            Log::Error(
                message : "{type} Asset {name} source does not exist: {source}.",
                context : [
                              'source' => $source,
                              'name'   => $this->name,
                              'path'   => $this->source,
                              'type'   => Get::className( $this ),
                          ],
            );
            return;
        }

        $this->build();
    }

    public function __get( string $name ) {
        $method = 'get' . ucfirst( $name );
        if ( method_exists( $this, $method ) ) {
            return $this->$method();
        }
        return property_exists( $this, $name ) ? $this->$name : null;
    }

    protected function build() : void {}

    protected function publicAsset() : ?string {

        $rootDir = File::pathfinder()->getParameter( 'dir.root' );

        $asset = [ 'dir.public.assets', $this->directory ];

        if ( Str::startsWith( $this->source->value, $rootDir ) ) {
            $bundle = substr( $this->source->value, strlen( $rootDir ) );

            $bundle = Str::between( $bundle, '\\', 3, 2 );
            $bundle = trim( str_replace( [ 'symfony', 'bundle' ], '', $bundle ), '-' );

            if ( $bundle !== $this->directory ) {
                $asset[] = $bundle;
            }
        }

        $asset[] = $this->source->filename . '.' . $this->source->extension;

        $path = File::path( implode( DIRECTORY_SEPARATOR, $asset ) );

        File::copy( $this->source->value, $path, static::$cacheBuster );

        return $path;
    }


    protected function asUrl( null | Path | string $path = null, bool $absolute = false ) : string {
        $path ??= $this->getPath();
        $path = substr( (string) $path, strlen( File::pathfinder()->getParameter( 'dir.public' ) ) );
        $path = '/' . ltrim( str_replace( '\\', '/', $path ), '/' );
        return $absolute ? App::baseUrl( $path ) : $path;
    }

    protected function version( ?string $path = null ) : string {
        return $this::$cacheBuster ? time() : filemtime( $path ?? $this->getPath() );
    }

    private function getPath() : string {
        return $this->path ??= $this->publicAsset();
    }
}