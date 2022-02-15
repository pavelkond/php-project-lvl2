<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function getFixtureFullPath(string $fileName): string
    {
        $fullPath = [__DIR__, 'fixtures', $fileName];
        return implode('/', $fullPath);
    }

    public function testGenDiffJson(): void
    {
        $file1 = $this->getFixtureFullPath('json_before.json');
        $file2 = $this->getFixtureFullPath('json_after.json');
        $resultFile = $this->getFixtureFullPath('plain_results.txt');

        $this->assertStringEqualsFile($resultFile, genDiff($file1, $file2));
    }

    public function testGenDiffYaml(): void
    {
        $file1 = $this->getFixtureFullPath('yaml_before.yml');
        $file2 = $this->getFixtureFullPath('yaml_after.yaml');
        $resultFile = $this->getFixtureFullPath('plain_results.txt');

        $this->assertStringEqualsFile($resultFile, genDiff($file1, $file2));
    }
}
