<?php

namespace MarcoKretz\AdventOfCode2024;

/**
 * Advent of Code - Day 9: Disk Fragmenter
 *
 * @url https://adventofcode.com/2024/day/9
 */
class Day9 extends AbstractTask
{
    /**
     * --- Part One ---
     */
    public function solvePartOne(array $memory): string
    {
        $memorySize = count($memory);
        $lastFreeSpaceFound = 0;

        // Iterate over memory from end to start
        for ($i = ($memorySize - 1); $i >= 0; $i--) {
            // Skip free space blocks
            if ($memory[$i] === null) {
                continue;
            }

            // Find next leftmost free space block
            for ($j = $lastFreeSpaceFound; $j < $i; $j++) {
                if ($memory[$j] === null) {
                    // Move file block to free space
                    $memory[$j] = $memory[$i];
                    $memory[$i] = null;

                    // Remeber the index for the next block
                    $lastFreeSpaceFound = $j + 1;
                    break;
                }
            }
        }

        return (string) $this->getChecksum($memory);
    }

    /**
     * --- Part Two ---
     */
    public function solvePartTwo(array $parsedInput): string
    {
        return '';
    }

    public function parseInput(): array
    {
        $result = [];
        $content = file_get_contents($this->input);

        // We just built the physical memory layout as an array
        foreach (str_split($content) as $index => $length) {
            if ($index % 2 === 0) { // It's a file
                for ($i = 0; $i < $length; $i++) {
                    $result[] = $index / 2;
                }
            } else {
                for ($i = 0; $i < $length; $i++) {
                    $result[] = null;
                }
            }
        }

        return $result;
    }

    private function getChecksum(array $memory): int
    {
        $checksum = 0;
        foreach ($memory as $index => $block) {
            $checksum += ($index * $block);
        }

        return $checksum;
    }
}
