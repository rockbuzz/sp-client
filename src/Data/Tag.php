<?php

namespace Rockbuzz\SpClient\Data;

class Tag extends DataTransferObject
{
    protected function properties(): array
    {
        return [
            'id',
            'name',
            'created_at',
            'update_at'
        ];
    }
}
