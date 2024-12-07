<?php

namespace MarcoKretz\AdventOfCode2024;

use RuntimeException;

/**
 * Advent of Code - Day 7: Bridge Repair
 *
 * @url https://adventofcode.com/2024/day/7
 */
class Day7 extends AbstractTask
{
    private const OP_ADD = '+';
    private const OP_MULTIPLY = '*';

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
        $totalResult = 0;
        foreach ($parsedInput as $line) {
            $operands = $line['e'];
            $result = $line['r'];
            $operatorCount = count($operands) - 1;

            // Count possible combinations and prefill empty array structure
            $possibleCombinations = pow(2, $operatorCount);
            $combinations = array_fill(0, $possibleCombinations, []);

            // Create all combinations of operands
            $opCombinations = $this->createPossibleCombinations([self::OP_ADD, self::OP_MULTIPLY], $operatorCount);

            // Combine the operands with the operators
            $combinationLength = 2 * count($operands) - 1;
            for ($i = 0; $i < $combinationLength; $i++) {
                for ($j = 0; $j < count($combinations); $j++) {
                    if ($i % 2 === 0) {
                        // Insert operand
                        $combinations[$j][$i] = $operands[$i / 2];
                    } else {
                        // Insert operator
                        $combinations[$j][$i] = $opCombinations[$j][$i / 2];
                    }
                }
            }

            foreach ($combinations as $combination) {
                if ($this->evaluate($combination, $result)) {
                    $totalResult += $result;
                    break;
                }
            }
        }

        return (string) $totalResult;
    }

    /**
     * --- Part Two ---
     */
    public function solvePartTwo(array $map): string
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
            if (empty($line)) {
                continue;
            }

            $splitted = explode(':', $line);
            $result[] = [
                'e' => array_map('intval', explode(' ', trim($splitted[1]))),
                'r' => (int) $splitted[0],
            ];
        }

        return $result;
    }

    private function createPossibleCombinations(array $elements, int $length): array
    {
        if ($length < 1 || empty($elements)) {
            return [];
        }

        $result = [];
        $this->combine($elements, $length, [], $result);

        return $result;
    }

    private function combine(array $elements, int $length, array $current, array &$result): void
    {
        if (count($current) === $length) {
            $result[] = $current;

            return;
        }

        for ($i = 0; $i < count($elements); $i++) {
            $current[] = $elements[$i];
            $this->combine($elements, $length, $current, $result);
            array_pop($current);
        }
    }

    /**
     * Test if a fiven equation matches the given result.
     * Operators are always evaluated left-to-right, not according to precedence rules.
     */
    private function evaluate(array $equation, int $expectedResult): bool
    {
        $actualResult = null;
        foreach ($equation as $index => $element) {
            if ($index % 2 === 0) {
                // It's an operand (number)
                if ($index === 0) {
                    $actualResult = $element;
                } else {
                    $operator = $equation[$index - 1];
                    $actualResult = match ($operator) {
                        self::OP_ADD => $actualResult + $element,
                        self::OP_MULTIPLY => $actualResult * $element,
                    };
                }
            }
        }

        return $actualResult === $expectedResult;
    }
}
