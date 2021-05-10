<?php

namespace Rockbuzz\SpClient\Data;

/**
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 */
class Subscriber extends Base
{
    use HasDates;
}
