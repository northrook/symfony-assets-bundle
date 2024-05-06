<?php

namespace Northrook\Symfony\Assets\Core;

interface Asset extends \Stringable
{
     public function __get( string $name );

     public function __toString() : string;
}