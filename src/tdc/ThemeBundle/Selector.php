<?php
namespace tdc\ThemeBundle;


class Selector 
{
    public function getOs()
    {
        switch (strtolower($PHP_OS)) {
            case 'WIN':
                return 'win';
        }
    }
}
