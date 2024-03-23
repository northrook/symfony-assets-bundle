<?php

namespace Northrook\Symfony\Assets;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SymfonyAssetsBundle extends AbstractBundle
{
    public function getPath() : string {
        return dirname( __DIR__ );
    }
}