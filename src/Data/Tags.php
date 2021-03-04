<?php

namespace Rockbuzz\SpClient\Data;

class Tags extends Collection
{
    protected function itemType(): string
    {
        return Tag::class;
    }
}
