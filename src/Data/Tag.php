<?php

namespace Rockbuzz\SpClient\Data;

use Carbon\Carbon;
use DateTime;

class Tag extends Base
{
    use HasDates;

    public function updatedAt(): DateTime
    {
        return Carbon::createFromTimestamp($this->update_at);
    }
}
