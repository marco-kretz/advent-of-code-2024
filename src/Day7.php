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
            foreach ($this->combine([self::OP_ADD, self::OP_MULTIPLY], $operatorCount) as $operators) {
                // Test equation
                if ($this->evaluate($operands, $operators, $result)) {
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
    public function solvePartTwo(array $parsedInput): string
    {
        $totalResult = 0;
        foreach ($parsedInput as $line) {
            $operands = $line['e'];
            $result = $line['r'];
            $operatorCount = count($operands) - 1;

            // Create all combinations of operands
            foreach ($this->combine([self::OP_ADD, self::OP_MULTIPLY, self::OP_CONCAT], $operatorCount) as $operators) {
                // Test equation
                if ($this->evaluate($operands, $operators, $result)) {
                    $totalResult += $result;
                    break;
                }
            }
        }

        return (string) $totalResult;
    }

    public function parseInput(): array
    {
        $result = [];
        foreach (file($this->input, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            [$res, $ops] = explode(':', $line);
            $result[] = [
                'e' => array_map('intval', explode(' ', trim($ops))),
                'r' => (int) $res,
            ];
        }

        return $result;
    }

    private function combine(array $elements, int $length, array $current = []): Iterator
    {
        if (count($current) === $length) {
            yield $current;
            return;
        }

        $elementCount = count($elements);
        foreach ($elements as $element) {
            $current[] = $element;
            yield from $this->combine($elements, $length, $current);
            array_pop($current);
        }
    }

    /**
     * Test if a fiven equation matches the given result.
     * Operators are always evaluated left-to-right, not according to precedence rules.
     */
    private function evaluate(array $operands, array $operators, int $expectedResult): bool
    {
        $result = $operands[0];
        foreach ($operators as $i => $operator) {
            $nextOperand = $operands[$i + 1];
            $result = match ($operator) {
                self::OP_ADD => $result + $nextOperand,
                self::OP_MULTIPLY => $result * $nextOperand,
                self::OP_CONCAT => (int) ("$result$nextOperand"),
            };
        }

        return $result === $expectedResult;
    }
}
