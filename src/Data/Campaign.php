<?php

namespace Rockbuzz\SpClient\Data;

use Carbon\Carbon;

/**
 * @property integer $id
 * @property string $name
 * @property string $subject
 * @property string $content
 * @property integer $status_id
 * @property string $from_name
 * @property string $from_email
 * @property bool $is_open_tracking
 * @property bool $is_click_tracking
 * @property integer $sent_count
 * @property integer $open_count
 * @property integer $click_count
 * @property array $tags
 * @property bool $save_as_draft
 * @property string|null $scheduled_at
 * @property string $created_at
 * @property string $updated_at
 */
class Campaign extends Base
{
    use HasDates;

    public function scheduledAt(): ?string
    {
        return $this->scheduled_at ? Carbon::createFromTimestamp($this->scheduled_at) : null;
    }
}
