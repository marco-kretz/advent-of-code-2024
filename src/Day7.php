<?php

namespace MarcoKretz\AdventOfCode2024;

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
            [$result, $operands] = $line;
            if ($this->evaluate($operands, [self::OP_ADD, self::OP_MULTIPLY], $result)) {
                $totalResult += $result;
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
            [$result, $operands] = $line;
            if ($this->evaluate($operands, [self::OP_ADD, self::OP_MULTIPLY, self::OP_CONCAT], $result)) {
                $totalResult += $result;
            }
        }

        return (string) $totalResult;
    }

    public function parseInput(): array
    {
        $result = [];
        foreach (file($this->input, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            [$res, $ops] = explode(':', $line);
            $result[] = [(int) $res, array_map('intval', explode(' ', trim($ops)))];
        }

        return $result;
    }

    /**
     * Test if a fiven equation matches the given result.
     * Operators are always evaluated left-to-right, not according to precedence rules.
     *
     * Edit: Switched to using DSF after seeing how slow the bruteforce method was :D
     */
    private function evaluate(array $operands, array $operators, int $expectedResult): bool
    {
        $search = null;
        $search = function (int $acc, int $index) use ($operands, $operators, &$search): bool {
            if ($index === 0) {
                return $acc === $operands[0];
            }

            $number = $operands[$index];
            $validOps = array_filter($operators, function ($operator) use ($acc, $number): bool {
                return (
                    !($operator === self::OP_ADD && $acc < $number) &&
                    !($operator === self::OP_MULTIPLY && ($number === 0 || $acc % $number != 0)) &&
                    !($operator === self::OP_CONCAT && ($acc === $number || !str_ends_with($acc, $number)))
                );
            });

            foreach ($validOps as $validOp) {
                $result = match ($validOp) {
                    self::OP_ADD => $acc - $number,
                    self::OP_MULTIPLY => $acc / $number,
                    self::OP_CONCAT => substr($acc, 0, -strlen((string) $number)),
                };

                if ($search($result, $index - 1)) {
                    return true;
                }
            }

            return false;
        };

        return $search($expectedResult, count($operands) - 1);
    }
}
