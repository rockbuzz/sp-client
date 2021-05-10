<?php

namespace Rockbuzz\SpClient\Data;

use Carbon\Carbon;

trait HasDates
{
    public function createdAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->created_at);
    }

    public function updatedAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->updated_at);
    }
}
