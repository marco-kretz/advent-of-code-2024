<?php

namespace MarcoKretz\AdventOfCode2024;

use RuntimeException;

/**
 * Advent of Code - Day 4: Ceres Search
 *
 * @url https://adventofcode.com/2024/day/4
 */
class Day4 extends AbstractTask
{
    public function solve(): string
    {
        $parsedInput = $this->parseInput();

        $resultPartOne = $this->solvePartOne($parsedInput);
        $resultPartTwo = $this->solvePartTwo($parsedInput);

        return '' .
            "The result for part one is: $resultPartOne\n" .
            "The result for part two is: $resultPartTwo" .
        '';
    }

    /**
     * --- Part One ---
     */
    public function solvePartOne(array $parsedInput): string
    {
        $needle = 'XMAS';
        $rows = count($parsedInput);
        $cols = count($parsedInput[0]);
        $matches = [];

        for ($y = 0; $y < $rows; $y++) {
            for ($x = 0; $x < $cols; $x++) {
                $currentChar = $parsedInput[$y][$x];

                // Skip any non 'X's
                if ($currentChar !== $needle[0]) {
                    continue;
                }

                $matches = array_merge($matches, $this->search2d($needle, $parsedInput, $x, $y));
            }
        }

        return (string) count($matches);
    }

    /**
     * --- Part Two ---
     */
    public function solvePartTwo(array $parsedInput): string
    {
        $needle = 'MAS';
        $rows = count($parsedInput);
        $cols = count($parsedInput[0]);
        $matches = [];

        for ($y = 0; $y < $rows; $y++) {
            for ($x = 0; $x < $cols; $x++) {
                $currentChar = $parsedInput[$y][$x];

                // Skip any non 'M's
                if ($currentChar !== $needle[0]) {
                    continue;
                }

                $matches = array_merge($matches, $this->search2d($needle, $parsedInput, $x, $y));
            }
        }

        // Find the pairs where the middle coords are equal.
        // We just just in/decrease by 1 because the needle is given with 3 chars.
        $matchCache = [];
        foreach ($matches as $match) {
            // Skip non-diagonal matches
            if (in_array($match['d'], ['r', 'd', 'l', 'u'])) {
                continue;
            }

            // Calc middle x coord
            $xMid = match($match['d']) {
                'ur', 'r', 'dr' => $match['x'] + 1,
                'ul', 'l', 'dl' => $match['x'] - 1,
                default => $match['x'],
            };

            // Calc middle y coord
            $yMid = match($match['d']) {
                'dr', 'd', 'dl' => $match['y'] + 1,
                'ur', 'u', 'ul' => $match['y'] - 1,
                default => $match['y'],
            };

            // Count how many pairs share the same middle coord
            if (!isset($matchCache["$xMid/$yMid"])) {
                $matchCache["$xMid/$yMid"] = 1;
            } else {
                $matchCache["$xMid/$yMid"]++;
            }
        }

        // All matches with at least to of the same middle coords are valid
        $validMatches = array_filter($matchCache, static function (int $value): bool {
            return $value >= 2;
        });

        return (string) count($validMatches);
    }

    private function parseInput(): array
    {
        // Read whole file as string
        $handle = fopen($this->input, 'r');
        if (!$handle) {
            throw new RuntimeException('File input not found!');
        }

        $result = [];
        while (($line = fgets($handle)) !== false) {
            // Remove all linebreaks
            $line = str_replace(["\r\n", "\n"], '', $line);
            // Split string into array of chars (not actually needed, but it makes it more clear)
            $result[] = str_split($line);
        }

        return $result;
    }

    /**
     * Check the occurrences of a needle from a given starting point within a 2d-array (matrix).
     * The starting point should be equal to the first character of the needle.
     *
     * @param string $needle The word to search for
     * @param array $haystack The matrix to search in
     * @param int $fromX Starting X-coord
     * @param int $fromY Starting y-coord
     *
     * @return array A list of all occurrences with start coords + direction
     */
    private function search2d(string $needle, array $haystack, int $fromX, int $fromY): array
    {
        $needleLength = strlen($needle);
        $directions = ['u', 'ur', 'r', 'dr', 'd', 'dl', 'l', 'ul'];
        $result = [];

        foreach ($directions as $direction) {
            $x = $fromX;
            $y = $fromY;
            $currentMatch = '';

            for ($i = 0; $i < $needleLength; $i++) {
                // Check if new coords are out of bounds
                if (!isset($haystack[$y][$x])) {
                    break;
                }

                // Check if the new character fits the needle
                $currentMatch .= $haystack[$y][$x];
                if (!str_starts_with($needle, $currentMatch)) {
                    break;
                }

                // Calculate new x/y coords
                $x = match ($direction) {
                    'ur', 'r', 'dr' => $x + 1,
                    'ul', 'l', 'dl' => $x - 1,
                    default => $x,
                };
                $y = match ($direction) {
                    'dr', 'd', 'dl' => $y + 1,
                    'ur', 'u', 'ul' => $y - 1,
                    default => $y,
                };
            }

            // If we found a full match, increase match counter
            if ($currentMatch === $needle) {
                $result[] = [
                    'x' => $fromX,
                    'y' => $fromY,
                    'd' => $direction,
                ];
            }
        }

        return $result;
    }
}
