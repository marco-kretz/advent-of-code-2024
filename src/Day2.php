<?php

namespace MarcoKretz\AdventOfCode2024;

use RuntimeException;

/**
 * Advent of Code - Day 2: Red-Nosed Reports
 *
 * @url https://adventofcode.com/2024/day/2
 */
class Day2 extends AbstractTask
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
        $unsafeCount = 0;
        $totalReports = count($parsedInput);

        // Count all report which are not valid
        foreach ($parsedInput as $report) {
            if (!$this->isReportValid($report)) {
                $unsafeCount++;
            }
        }

        return (string) ($totalReports - $unsafeCount);
    }

    /**
     * --- Part Two ---
     */
    public function solvePartTwo(array $parsedInput): string
    {
        $unsafeCount = 0;
        $totalReports = count($parsedInput);

        // Count all report which are not valid, even after dampening one level
        foreach ($parsedInput as $report) {
            if (!$this->isReportValid($report) && $this->dampenReport($report) === null) {
                $unsafeCount++;
            }
        }

        return (string) ($totalReports - $unsafeCount);
    }

    private function parseInput(): array
    {
        $handle = fopen($this->input, 'r');
        if (!$handle) {
            throw new RuntimeException('Can not open input file.');
        }

        $result = [];

        while (($line = fgets($handle)) !== false) {
            $values = explode(' ', $line);
            $result[] = array_map('intval', $values);
        }

        fclose($handle);

        return $result;
    }

    private function isReportValid(array $report): bool
    {
        $direction = null;
        $reportSize = count($report);
        for ($i = 1; $i < $reportSize; $i++) {
            // Check level difference
            $diff = abs($report[$i - 1] - $report[$i]);
            if ($diff < 1 || $diff > 3) {
                return false;
            }

            // Increasing levels
            if ($report[$i - 1] < $report[$i]) {
                // First loop sets "direction"
                if ($i === 1) {
                    $direction = 'inc';
                    continue;
                }

                // We are already decreasing, so bye
                if ($direction === 'dec') {
                    return false;
                }
            }

            // Decreasing levels
            if ($report[$i - 1] > $report[$i]) {
                // First loop sets "direction"
                if ($i === 1) {
                    $direction = 'dec';
                    continue;
                }

                // We are already increasing, so bye
                if ($direction === 'inc') {
                    return false;
                }
            }
        }

        return true;
    }

    private function dampenReport(array $report): ?array
    {
        if ($this->isReportValid($report)) {
            return $report;
        }

        for ($i = 0; $i < count($report); $i++) {
            // Copy original array
            $dampenedReport = $report;

            // Remove one element
            unset($dampenedReport[$i]);

            // Reset array keys
            $dampenedReport = array_values($dampenedReport);

            // Check if report is now valid
            if ($this->isReportValid($dampenedReport)) {
                return $dampenedReport;
            }
        }

        return null;
    }
}
