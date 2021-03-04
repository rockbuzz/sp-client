<?php

namespace Rockbuzz\SpClient\Data;

class Meta extends DataTransferObject
{
    protected function properties(): array
    {
        return [
            'current_page',
            'from',
            'last_page',
            'path',
            'per_page',
            'to',
            'total'
        ];
    }
}
