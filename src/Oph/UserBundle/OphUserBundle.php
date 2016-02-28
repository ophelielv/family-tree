<?php

namespace Oph\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OphUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
