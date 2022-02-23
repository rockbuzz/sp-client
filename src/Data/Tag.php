<?php

namespace Rockbuzz\SpClient\Data;

/**
 * @property integer $id
 * @property string $name
 * @property integer $subscribers_count
 * @property string $created_at
 * @property string $updated_at
 */
class Tag extends Base
{
    use HasDates;
}
