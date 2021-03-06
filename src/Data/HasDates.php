<?php

namespace Rockbuzz\SpClient\Data;

use DateTime;
use Carbon\Carbon;

trait HasDates
{
    public function createdAt(): DateTime
    {
        return Carbon::createFromTimestamp($this->created_at);
    }

    public function updatedAt(): DateTime
    {
        return Carbon::createFromTimestamp($this->updated_at);
    }
}
