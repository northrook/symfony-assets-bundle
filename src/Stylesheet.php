<?php

namespace Northrook\Symfony\Assets;


class Stylesheet extends Core\AbstractAsset
{

    protected ?string $directory = 'styles';


    public function __toString() : string {
        return $this->asUrl() . '?v=' . $this->version();
    }
}