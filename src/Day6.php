<?php

namespace MarcoKretz\AdventOfCode2024;

use RuntimeException;

/**
 * Advent of Code - Day 6: Guard Gallivant
 *
 * @url https://adventofcode.com/2024/day/5
 */
class Day6 extends AbstractTask
{
    private const OBSTRUCTION = '#';
    private const GUARD_UP = '^';
    private const GUARD_DOWN = 'v';
    private const GUARD_RIGHT = '>';
    private const GUARD_LEFT = '<';

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
    public function solvePartOne(array $map): string
    {
        $guardPosition = $this->getGuardPosition($map);
        if ($guardPosition === null) {
            throw new RuntimeException('No guard found on map!');
        }

        // Initial state
        [$x, $y, $d] = $guardPosition;

        // Marker map for visited positions
        $visited = [
            "$x/$y" => true,
        ];

        while (true) {
            // Calculate deltas for x/y
            $dx = match($d) {
                self::GUARD_LEFT => -1,
                self::GUARD_RIGHT => 1,
                default => 0,
            };
            $dy = match($d) {
                self::GUARD_UP => -1,
                self::GUARD_DOWN => 1,
                default => 0,
            };

            $newX = $x + $dx;
            $newY = $y + $dy;

            // Out of map? Exit.
            if (!isset($map[$newY][$newX])) {
                break;
            }

            if ($map[$newY][$newX] === self::OBSTRUCTION) {
                // Turn right 90 degrees
                $d = match($d) {
                    self::GUARD_DOWN => self::GUARD_LEFT,
                    self::GUARD_LEFT => self::GUARD_UP,
                    self::GUARD_UP => self::GUARD_RIGHT,
                    self::GUARD_RIGHT => self::GUARD_DOWN,
                };

                // Don't change position
                continue;
            }

            // Go to the new position
            $x = $newX;
            $y = $newY;
            $visited["$x/$y"] = true;
        }

        return (string) count($visited);
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

        $map = [];
        while (($line = fgets($handle)) !== false) {
            if (empty($line)) {
                continue;
            }

            $map[] = str_split(trim($line));
        }

        return $map;
    }

    /**
     * Find the position of the guard within a given map.
     * Returns null, if no guard can be found.
     */
    private function getGuardPosition(array $map): ?array
    {
        $rows = count($map);
        $cols = count($map[0]);

        for ($y = 0; $y < $rows; $y++) {
            for ($x = 0; $x < $cols; $x++) {
                if (in_array($map[$y][$x], [self::GUARD_DOWN, self::GUARD_LEFT, self::GUARD_RIGHT, self::GUARD_UP])) {
                    return [$x, $y, $map[$y][$x]];
                }
            }
        }

        return null;
    }
}
