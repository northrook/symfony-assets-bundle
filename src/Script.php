<?php

namespace Northrook\Symfony\Assets;

class Script extends Core\AbstractAsset
{
    protected ?string $directory = 'scripts';


    public function __toString() : string {
        return $this->asUrl() . '?v=' . $this->version();
    }
}