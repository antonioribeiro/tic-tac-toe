<?php

/**
 * Infer opponent to a player.
 *
 * @param string $player
 * @return string
 */
function infer_opponent(string $player): string
{
    return $player === 'X' ? 'O' : 'X';
}
