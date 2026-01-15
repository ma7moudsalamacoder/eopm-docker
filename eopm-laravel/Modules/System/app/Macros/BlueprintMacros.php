<?php

namespace Modules\System\Macros;

use Illuminate\Database\Schema\Blueprint;
use Modules\System\Contracts\MacroBase;

class BlueprintMacros extends MacroBase
{

    public static function register() : void
    {
        Blueprint::macro('auditions', function() {
            $this->unsignedBigInteger('created_by_id')->nullable();
            $this->unsignedBigInteger('updated_by_id')->nullable();
            $this->unsignedBigInteger('deleted_by_id')->nullable();
        });
    }
}
