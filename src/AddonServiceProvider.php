<?php

namespace Rainbow1997\Testback;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'rainbow1997';
    protected $packageName = 'testback';
    protected $commands = [];

}
