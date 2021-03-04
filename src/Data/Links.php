<?php

namespace Rockbuzz\SpClient\Data;

class Links extends DataTransferObject
{
    protected function properties(): array
    {
        return [
            'first',
            'last',
            'prev',
            'next'
        ];
    }
}
