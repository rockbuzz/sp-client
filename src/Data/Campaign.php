<?php

namespace Rockbuzz\SpClient\Data;

class Campaign extends DataTransferObject
{
    protected function properties(): array
    {
        return [
            'id',
            'name',
            'subject',
            'content',
            'status_id',
            'template_id',
            'email_service_id',
            'from_name',
            'from_email',
            'is_open_tracking',
            'is_click_tracking',
            'sent_count',
            'open_count',
            'click_count',
            'send_to_all',
            'tags',
            'save_as_draft',
            'scheduled_at',
            'created_at',
            'updated_at'
        ];
    }
}
