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
                    $antinode1 = $this->getNextAntinode($pair[0], [$cols, $rows], -$dx, -$dy);
                    if ($antinode1 !== null) {
                        $antinodes["{$antinode1[0]}/{$antinode1[1]}"] = true;
                    }

                    // Calculate second antinode
                    $antinode2 = $this->getNextAntinode($pair[1], [$cols, $rows], $dx, $dy);
                    // Check if it's within the map
                    if ($antinode2 !== null) {
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

                    // Add current antenna to antinodes list
                    $antinodes["{$pair[0][0]}/{$pair[0][1]}"] = true;
                    $antinodes["{$pair[1][0]}/{$pair[1][1]}"] = true;

                    // Calculate the delta (distance) of the pair
                    $dx = $pair[1][0] - $pair[0][0];
                    $dy = $pair[1][1] - $pair[0][1];

                    // Negative dx/dy direction
                    $current = $pair[0];
                    while (($nextAntinode = $this->getNextAntinode($current, [$cols, $rows], -$dx, -$dy)) !== null) {
                        $antinodes["{$nextAntinode[0]}/{$nextAntinode[1]}"] = true;
                        $current = $nextAntinode;
                    }

                    // Positive dx/dy direction
                    $current = $pair[1];
                    while (($nextAntinode = $this->getNextAntinode($current, [$cols, $rows], $dx, $dy)) !== null) {
                        $antinodes["{$nextAntinode[0]}/{$nextAntinode[1]}"] = true;
                        $current = $nextAntinode;
                    }
                }
            }
        }

        return (string) count($antinodes);
    }

    public function parseInput(): array
    {
        $antennaLocations = [];
        $rowCount = 0;
        $colCount = 0;
        foreach (file($this->input, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $row = str_split($line);
            foreach ($row as $index => $element) {
                // We only need to save antenna locations, ignore the rest
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

    /**
     * Calculates the next possible antinode within the map's boundaries.
     *
     * @param array $current Current antennas position
     * @param array $bounds Map boundaries
     * @param int $dx
     * @param int $dy
     *
     * @return array New antinode's position, null if not possible
     */
    private function getNextAntinode(array $current, array $bounds, int $dx, int $dy): ?array
    {
        $antinode = [$current[0] + $dx, $current[1] + $dy];
        if (
            ($antinode[0] >= 0 && $antinode[0] < $bounds[0]) &&
            ($antinode[1] >= 0 && $antinode[1] < $bounds[1])
        ) {
            return $antinode;
        }

        return null;
    }
}
