<?php

namespace Doggo\Admin;

use Doggo\Model\Park;

class ParkAdmin extends \SilverStripe\Admin\ModelAdmin
{
    private static $managed_models = [
        Park::class,
    ];

    private static $menu_title = 'Parks';

    private static $url_segment = 'parks';
}
