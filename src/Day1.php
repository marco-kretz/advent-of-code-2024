<?php

namespace MarcoKretz\AdventOfCode2024;

use RuntimeException;

/**
 * Advent of Code - Day 1: Hystorian Hysteria
 *
 * @url https://adventofcode.com/2024/day/1
 */
class Day1 extends AbstractTask
{
    public function solve(): string {
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
        $arrayLength = count($parsedInput['left']);
        $distance = 0;

        // Interate over the lines and do the math.
        // Lists are already sorted, so no further handling needed.
        for ($i = 0; $i < $arrayLength; $i++) {
            $distance += abs($parsedInput['left'][$i] - $parsedInput['right'][$i]);
        }

        return (string) $distance;
    }

    /**
     * --- Part Two ---
     */
    public function solvePartTwo(array $parsedInput): string
    {
        $cache = [];
        $score = 0;
        foreach ($parsedInput['left'] as $leftNumber) {
            // Check if occurrences for the current number have already been count
            if (!isset($cache[$leftNumber])) {
                // Count occurrences of leftNumber in the right list
                $cache[$leftNumber] = 0;
                foreach ($parsedInput['right'] as $rightNumber) {
                    if ($leftNumber === $rightNumber) {
                        $cache[$leftNumber]++;
                    }
                }
            }

            // Increase score
            $score += ($leftNumber * $cache[$leftNumber]);
        }

        return (string) $score;
    }

    private function parseInput(): array
    {
        $handle = fopen($this->input, 'r');
        if (!$handle) {
            throw new RuntimeException('Can not open input file.');
        }

        $result = [
            'left' => [],
            'right' => [],
        ];

        while (($line = fgets($handle)) !== false) {
            $values = explode('   ', $line);
            $result['left'][] = (int) $values[0];
            $result['right'][] = (int) $values[1];
        }

        fclose($handle);

        // Sort both list ascending
        sort($result['left']);
        sort($result['right']);

        return $result;
    }
}
