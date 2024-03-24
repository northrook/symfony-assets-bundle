<?php

namespace Northrook\Symfony\Assets\Core;

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;
use Northrook\Elements\Element;
use Northrook\Logger\Log;
use Northrook\Support\Get;
use Northrook\Support\Str;
use Northrook\Symfony\Core\File;
use Northrook\Types\ID;
use Northrook\Types\Path;
use Stringable;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;

/**
 * We only really need the source {@see Path}.
 *
 * The public directory will always be the same, eg "public/assets/...".
 *
 */
abstract class Asset implements Stringable
{

    public static bool $cacheBuster = false;

    protected readonly ID     $id;
    protected readonly Path   $source;
    protected readonly string $name;

    public function __construct(
        Path | string $source,
        ?string       $name = null,
        ?id           $id = null,
    ) {
        $this->source = $source instanceof Path ? $source : File::path( $source );
        $this->name   = Str::key( $name ?? $this->source->filename );
        $this->id     = new ID( $id ?? Uuid::v4() );

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

    abstract function build() : void;

    protected function asUrl( Path | string $path ) : string {
        $path = str_replace( '\\', '/', $path );
        return substr( $path, strrpos( $path, 'public/assets/' ) + 6 );
    }


    // protected function version(
    //     #[ExpectedValues( 'lastModified', 'contentHash', 'timestamp' )]
    //     string $by = 'lastModified',
    // ) : string {
    //     return match ( $by ) {
    //         'lastModified' => filemtime( $this->source ),
    //         'contentHash'  => crc32( file_get_contents( $this->source ) ),
    //         default        => time()
    //     };
    // }
}