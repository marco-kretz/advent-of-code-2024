<?php

namespace MarcoKretz\AdventOfCode2024;

abstract class AbstractTask
{
    public function __construct(protected readonly string $input)
    {
    }

    abstract public function parseInput(): array;
    abstract public function solvePartOne(array $input): string;
    abstract public function solvePartTwo(array $input): string;
}
