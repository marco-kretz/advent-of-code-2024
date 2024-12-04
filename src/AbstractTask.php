<?php

namespace MarcoKretz\AdventOfCode2024;

abstract class AbstractTask
{
    public function __construct(protected readonly string $input)
    {
    }

    abstract public function solve(): string;
}
