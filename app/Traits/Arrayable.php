<?php

namespace App\Traits;

trait Arrayable
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
