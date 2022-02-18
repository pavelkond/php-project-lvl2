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

    public function testGenDiffYaml(): void
    {
        $file1 = $this->getFixtureFullPath('yaml_before.yml');
        $file2 = $this->getFixtureFullPath('yaml_after.yaml');
        $resultFile = $this->getFixtureFullPath('plain_results.txt');

        $this->assertStringEqualsFile($resultFile, genDiff($file1, $file2));
    }
}
