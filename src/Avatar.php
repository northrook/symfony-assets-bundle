<?php

namespace Northrook\Symfony\Assets;

use Northrook\Elements\Element;

class Avatar extends Core\AbstractAsset
{
    // protected Element $value;

    function build() : void {
        // TODO: Implement build() method.
    }

    public function __toString() : string {
        return $this->asUrl() . '?v=' . $this->version();
    }
}