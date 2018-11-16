<?php

/**
 * Extract available moves from a flattened state array.
 *
 * @param array $state
 * @return array
 */
function extract_available_moves(array $state): array
{
    return array_keys(
        array_filter($state, function ($item) {
            return !is_string($item);
        })
    );
}
