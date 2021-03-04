<?php

namespace Rockbuzz\SpClient\Data;

class Subscriber extends DataTransferObject
{
    protected function properties(): array
    {
        return [
            'id',
            'first_name',
            'last_name',
            'email',
            'unsubscribed_at',
            'created_at',
            'updated_at'
        ];
    }
}
