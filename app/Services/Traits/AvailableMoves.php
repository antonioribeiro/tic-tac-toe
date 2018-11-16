<?php

namespace App\Services\Traits;

trait AvailableMoves
{
    /**
     * Extract available moves from a flattened state array.
     *
     * @param array $state
     * @return array
     */
    public function filterAvailableMoves(array $state): array
    {
        return array_keys(
            array_filter($state, function ($item) {
                return !is_string($item);
            })
        );
    }
}
