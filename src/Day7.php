<?php

namespace MarcoKretz\AdventOfCode2024;

use Iterator;
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
    private const OP_CONCAT = '||';

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

            // Create all combinations of operands
            foreach ($this->combine([self::OP_ADD, self::OP_MULTIPLY], $operatorCount, []) as $operatorComb) {
                // Create equation container
                $equation = array_fill(0, 2 * count($operands) - 1, null);

                // Fill equation container
                for ($i = 0; $i < count($equation); $i++) {
                    if ($i % 2 === 0) {
                        $equation[$i] = $operands[$i / 2];
                    } else {
                        $equation[$i] = $operatorComb[$i / 2];
                    }
                }

                // Test equation
                if ($this->evaluate($equation, $result)) {
                    $totalResult += $result;
                    continue 2;
                }
            }
        }

        return (string) $totalResult;
    }

    /**
     * --- Part Two ---
     */
    public function solvePartTwo(array $parsedInput): string
    {
        $totalResult = 0;
        foreach ($parsedInput as $line) {
            $operands = $line['e'];
            $result = $line['r'];
            $operatorCount = count($operands) - 1;

            // Create all combinations of operands
            foreach ($this->combine([self::OP_ADD, self::OP_MULTIPLY, self::OP_CONCAT], $operatorCount, []) as $operatorComb) {
                // Create equation container
                $equation = array_fill(0, 2 * count($operands) - 1, null);

                // Fill equation container
                for ($i = 0; $i < count($equation); $i++) {
                    if ($i % 2 === 0) {
                        $equation[$i] = $operands[$i / 2];
                    } else {
                        $equation[$i] = $operatorComb[$i / 2];
                    }
                }

                // Test equation
                if ($this->evaluate($equation, $result)) {
                    $totalResult += $result;
                    continue 2;
                }
            }
        }

        return (string) $totalResult;
    }

    public function parseInput(): array
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

    private function combine(array $elements, int $length, array $current)
    {
        if (count($current) === $length) {
            yield $current;
            return;
        }

        $elementCount = count($elements);
        for ($i = 0; $i < $elementCount; $i++) {
            $current[] = $elements[$i];
            yield from $this->combine($elements, $length, $current);
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
        $eqSize = count($equation);
        for ($i = 0; $i < $eqSize; $i++) {
            if ($i % 2 === 0) {
                // It's an operand (number)
                if ($i === 0) {
                    $actualResult = $equation[$i];
                } else {
                    $actualResult = match ($equation[$i - 1]) {
                        self::OP_ADD => $actualResult + $equation[$i],
                        self::OP_MULTIPLY => $actualResult * $equation[$i],
                        self::OP_CONCAT => (int) ($actualResult . $equation[$i])
                    };
                }
            }
        }

        return $actualResult === $expectedResult;
    }
}
