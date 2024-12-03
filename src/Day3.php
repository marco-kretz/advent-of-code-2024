<?php

namespace MarcoKretz\AdventOfCode2024;

/**
 * Advent of Code - Day 3: Mull It Over
 *
 * @url https://adventofcode.com/2024/day/3
 */
class Day3 extends AbstractTask
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
        $sum = 0;
        foreach ($parsedInput as $op) {
            // This is only needed for Part Two
            if (str_starts_with($op, 'do')) {
                continue;
            }

            // Extract both numbers from the op string
            $factors = explode(',', trim(substr($op, 3), "()"));

            // Add the mutltiplication to the final sum
            $sum += $factors[0] * $factors[1];
        }

        return (string) $sum;
    }

    /**
     * --- Part Two ---
     */
    public function solvePartTwo(array $parsedInput): string
    {
        $sum = 0;
        $do = true;
        foreach ($parsedInput as $op) {
            // Switch to "do"-mode
            if ($op === 'do()') {
                $do = true;
                continue;
            }

            // Switch to "don't"-mode
            if ($op === "don't()") {
                $do = false;
                continue;
            }

            // Skip if we are currently in don't-mode
            if (!$do) {
                continue;
            }

            // Extract both numbers from the op string
            $factors = explode(',', trim(substr($op, 3), "()"));

            // Add the mutltiplication to the final sum
            $sum += $factors[0] * $factors[1];
        }

        return (string) $sum;
    }

    private function parseInput(): array
    {
        // Read whole file as string
        $content = file_get_contents($this->input);

        // Extract all "mul(int,int)", "do()" and "don't()" patterns
        preg_match_all("/mul\(\d+,\d+\)|do\(\)|don't\(\)/", $content, $matches);

        return $matches[0];
    }
}
