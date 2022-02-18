<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
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

    public function testGenDiffPlain(): void
    {
        $jsonBefore = $this->getFixtureFullPath('json_before_plain.json');
        $jsonAfter = $this->getFixtureFullPath('json_after_plain.json');

        $yamlBefore = $this->getFixtureFullPath('yaml_before_plain.yml');
        $yamlAfter = $this->getFixtureFullPath('yaml_after_plain.yaml');

        $plainResult = $this->getFixtureFullPath('plain_result.txt');

        $this->assertStringEqualsFile($plainResult, genDiff($jsonBefore, $jsonAfter));
        $this->assertStringEqualsFile($plainResult, genDiff($yamlBefore, $yamlAfter));
    }

    public function testGenDiffNested(): void
    {
        $jsonBefore = $this->getFixtureFullPath('json_before_nested.json');
        $jsonAfter = $this->getFixtureFullPath('json_after_nested.json');

        $yamlBefore = $this->getFixtureFullPath('yaml_before_nested.yml');
        $yamlAfter = $this->getFixtureFullPath('yaml_after_nested.yml');

        $nestedResult = $this->getFixtureFullPath('nested_result.txt');

        $this->assertStringEqualsFile($nestedResult, genDiff($jsonBefore, $jsonAfter));
        $this->assertStringEqualsFile($nestedResult, genDiff($yamlBefore, $yamlAfter));
    }
}
