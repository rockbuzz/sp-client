<?php

namespace Rockbuzz\SpClient\Data;

use Carbon\Carbon;

trait HasDates
{
    public function createdAt(): Carbon
    {
        return Carbon::create($this->created_at);
    }

    public function updatedAt(): Carbon
    {
        return Carbon::create($this->updated_at);
    }
}
