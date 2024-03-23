<?php

namespace Northrook\Symfony\Assets;

// abstract
use Northrook\Symfony\Core\File;
use Northrook\Symfony\Core\Services\PathfinderService;
use Northrook\Types\Interfaces\Printable;
use Stringable;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @property bool $printed
 */
abstract class Asset implements Printable, Stringable
{

    protected bool $printed = false;

    public function __get( string $name ) {
        return property_exists( $this, $name ) ? $this->$name : null;
    }

    public function __toString() : string {
        return $this->print();
    }

    protected function filesystem() : Filesystem {
        return new Filesystem();
    }

    protected function pathfinder() : PathfinderService {
        return File::pathfinder();
    }
}