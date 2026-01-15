<?php

namespace Modules\System\Traits;

use DateTimeZone;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait FormatAuditDate {
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
                Carbon::parse($value)
                    ->timezone('UTC')
                    ->format('d-M-Y H:i a')
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
                Carbon::parse($value)
                    ->timezone('UTC')
                    ->format('d-M-Y H:i a')
        );
    }
}
