<?php

namespace Northrook\Symfony\Assets\Core;

interface Asset
{
     public function __get( string $name );

     public function __toString() : string;
}