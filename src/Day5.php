<?php

namespace MarcoKretz\AdventOfCode2024;

use RuntimeException;

/**
 * Advent of Code - Day 5: Print Queue
 *
 * @url https://adventofcode.com/2024/day/5
 */
class Day5 extends AbstractTask
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
        [$rules, $updates] = $parsedInput;
        $middleNumberSum = 0;

        // Iterate over all updates
        foreach ($updates as $update) {
            $isValid = true;
            $updateLength = count($update);

            // Create positioning array for all pages in the update
            $pagePositions = array_flip($update);

            // Iterate over every page in the update
            for ($i = 0; $i < $updateLength; $i++) {
                $page = $update[$i];

                // Filter all rules which can be applied to the current page/update
                $rulesForPage = array_filter($rules, function (array $currentRule) use ($page, $update): bool {
                    // If the current page is the first number of the rule and the second number exists in the update
                    if ($currentRule[0] === $page && in_array($currentRule[1], $update)) {
                        return true;
                    }

                    // If the current page is the second number of the rule and the first number exists in the update
                    if ($currentRule[1] === $page && in_array($currentRule[0], $update)) {
                        return true;
                    }

                    return false;
                });

                foreach ($rulesForPage as $ruleToCheck) {
                    if ($page === $ruleToCheck[0]) {
                        $positionA = $i;
                        $positionB = $pagePositions[$ruleToCheck[1]] ?? null;
                    } else {
                        $positionA = $pagePositions[$ruleToCheck[0]] ?? null;
                        $positionB = $i;
                    }

                    if ($positionA !== null && $positionB !== null && $positionA > $positionB) {
                        $isValid = false;
                        break;
                    }
                }

                if (!$isValid) {
                    break;
                }
            }

            if ($isValid) {
                // Add middle update value
                $middleNumberSum += $update[floor((count($update) - 1) / 2)];
            }
        }

        return (string) $middleNumberSum;
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

        $rules = [];
        $updates = [];
        $isReadingRules = true;
        while (($line = fgets($handle)) !== false) {
            // When hitting the blank line, switch mode
            if (empty(trim($line))) {
                $isReadingRules = false;
                continue;
            }

            if ($isReadingRules) {
                $rules[] = array_map('intval', explode('|', $line));
            } else {
                $updates[] = array_map('intval', explode(',', $line));
            }
        }

        return [$rules, $updates];
    }
}
