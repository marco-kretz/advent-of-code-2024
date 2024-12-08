<?php

namespace MarcoKretz\AdventOfCode2024;

/**
 * Advent of Code - Day 8: Resonant Collinearity
 *
 * @url https://adventofcode.com/2024/day/8
 */
class Day8 extends AbstractTask
{
    /**
     * --- Part One ---
     */
    public function solvePartOne(array $parsedInput): string
    {
        ['rows' => $rows, 'cols' => $cols, 'antennas' => $antennas] = $parsedInput;
        $antinodes = [];

        foreach ($antennas as $type => $locations) {
            // Match all unique antenna pairs
            for ($i = 0; $i < count($locations); $i++) {
                for ($j = count($locations) - 1; $j >= 0; $j--) {
                    if ($i >= $j) {
                        continue;
                    }

                    // Current antenna pair
                    $pair = [$locations[$i], $locations[$j]];

                    // Calculate the delta (distance) of the pair
                    $dx = $pair[1][0] - $pair[0][0];
                    $dy = $pair[1][1] - $pair[0][1];

                    // Calculate first antinode
                    $antinode1 = [($pair[0][0] - $dx), ($pair[0][1] - $dy)];
                    // Check if it's within the map
                    if (
                        ($antinode1[0] >= 0 && $antinode1[0] < $cols) &&
                        ($antinode1[1] >= 0 && $antinode1[1] < $rows)
                    ) {
                        $antinodes["{$antinode1[0]}/{$antinode1[1]}"] = true;
                    }

                    // Calculate second antinode
                    $antinode2 = [($pair[1][0] + $dx), ($pair[1][1] + $dy)];
                    // Check if it's within the map
                    if (
                        ($antinode2[0] >= 0 && $antinode2[0] < $cols) &&
                        ($antinode2[1] >= 0 && $antinode2[1] < $rows)
                    ) {
                        $antinodes["{$antinode2[0]}/{$antinode2[1]}"] = true;
                    }
                }
            }
        }

        return (string) count($antinodes);
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
        $antennaLocations = [];
        $rowCount = 0;
        $colCount = 0;
        foreach (file($this->input, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $row = str_split($line);
            foreach ($row as $index => $element) {
                // We only need to save antenna locations
                if ($element === '.') {
                    continue;
                }

                if (!isset($antennaLocations[$element])) {
                    $antennaLocations[$element] = [];
                }

                $antennaLocations[$element][] = [$index, $rowCount];
            }

            $rowCount++;
            if ($colCount === 0) {
                $colCount = count($row);
            }
        }

        return [
            'rows' => $rowCount,
            'cols' => $colCount,
            'antennas' => $antennaLocations,
        ];
    }
}
