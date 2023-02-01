<?php

namespace Jamali\Testback;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'jamali';
    protected $packageName = 'testback';
    protected $commands = [];
    public function boot()
    {
    }
}
