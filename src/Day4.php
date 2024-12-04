<?php

namespace MarcoKretz\AdventOfCode2024;

use Exception;
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
        $matches = 0;

        for ($y = 0; $y < $rows; $y++) {
            for ($x = 0; $x < $cols; $x++) {
                $currentChar = $parsedInput[$y][$x];

                // Skip any non 'X's
                if ($currentChar !== $needle[0]) {
                    continue;
                }

                $matches += $this->search2d($needle, $parsedInput, $x, $y);
            }

        }

        return (string) $matches;
    }

    /**
     * --- Part Two ---
     */
    public function solvePartTwo(array $parsedInput): string
    {
        return '';
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
     * @return int The number of occurrences
     */
    private function search2d(string $needle, array $haystack, int $fromX, int $fromY): int
    {
        $needleLength = strlen($needle);
        $directions = ['u', 'ur', 'r', 'dr', 'd', 'dl', 'l', 'ul'];
        $matchCount = 0;

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

                // Set new coords based on direction
                switch ($direction) {
                    case 'u':
                        $y -= 1;
                        break;
                    case 'ur':
                        $x += 1;
                        $y -= 1;
                        break;
                    case 'r':
                        $x += 1;
                        break;
                    case 'dr':
                        $x += 1;
                        $y += 1;
                        break;
                    case 'd':
                        $y += 1;
                        break;
                    case 'dl':
                        $x -= 1;
                        $y += 1;
                        break;
                    case 'l':
                        $x -= 1;
                        break;
                    case 'ul':
                        $x -= 1;
                        $y -= 1;
                        break;
                }
            }

            // If we found a full match, increase match counter
            if ($currentMatch === $needle) {
                $matchCount++;
            }
        }

        return $matchCount;
    }
}
