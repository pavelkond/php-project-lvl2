<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private $jsonBefore = $this->getFixtureFullPath('json_before_nested.json');
    private $jsonAfter = $this->getFixtureFullPath('json_after_nested.json');
    private $yamlBefore = $this->getFixtureFullPath('yaml_before_nested.yml');
    private $yamlAfter = $this->getFixtureFullPath('yaml_after_nested.yaml');

    public function getFixtureFullPath(string $fileName): string
    {
        if (str_starts_with($fileName, 'json')) {
            $fixtureDir = 'json';
        } elseif (str_starts_with($fileName, 'yaml')) {
            $fixtureDir = 'yaml';
        } else {
            $fixtureDir = 'results';
        }
        $fullPath = [__DIR__, 'fixtures', $fixtureDir, $fileName];
        return implode('/', $fullPath);
    }

    public function testGenDiffStylish(): void
    {
        $stylishResult = $this->getFixtureFullPath('nested_result.txt');

        $this->assertStringEqualsFile($stylishResult, genDiff($this->jsonBefore, $this->jsonAfter));
        $this->assertStringEqualsFile($stylishResult, genDiff($this->yamlBefore, $this->yamlAfter));
    }
}
