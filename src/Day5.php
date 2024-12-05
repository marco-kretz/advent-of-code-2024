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
                $needToBeBefore = $rules[$page];
                foreach ($needToBeBefore as $pageNumber) {
                    $positionInUpdate = array_search($pageNumber, $update);
                    if ($positionInUpdate === false) {
                        continue;
                    }

                    if ($positionInUpdate < $i) {
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

        return [$this->buildRulesMap($rules), $updates];
    }

    /**
     * Here we build a rule map which holds all numbers for every page-number which needs to come after it;
     * This makes the rule lookup a lot faster!
     *
     * 17 => 12,67,56
     * 18 => 13,16,...
     *
     */
    private function buildRulesMap(array $rules): array
    {
        $rulesMap = [];
        foreach ($rules as $rule) {
            $first = $rule[0];
            $second = $rule[1];

            if (!isset($rulesMap[$first])) {
                $rulesMap[$first] = [$second];
                continue;
            }

            if (!in_array($second, $rulesMap[$first])) {
                $rulesMap[$first][] = $second;
            }
        }

        return $rulesMap;
    }
}
