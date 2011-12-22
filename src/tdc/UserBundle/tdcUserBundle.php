<?php

namespace tdc\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class tdcUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
