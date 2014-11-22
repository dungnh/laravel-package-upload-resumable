<?php

namespace Evolpas\Resumable\Facade;

use Illuminate\Support\Facades\Facade;

class Resumable extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'resumable';
    }

}
