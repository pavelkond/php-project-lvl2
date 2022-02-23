<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private $jsonBefore;
    private $jsonAfter;
    private $yamlBefore;
    private $yamlAfter;

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

    public function setUp(): void
    {
        $this->jsonBefore = $this->getFixtureFullPath('json_before_nested.json');
        $this->jsonAfter = $this->getFixtureFullPath('json_after_nested.json');
        $this->yamlBefore = $this->getFixtureFullPath('yaml_before_nested.yml');
        $this->yamlAfter = $this->getFixtureFullPath('yaml_after_nested.yaml');
    }

    public function testGenDiffStylish(): void
    {
        $stylishResult = $this->getFixtureFullPath('stylish_result.txt');

        $this->assertStringEqualsFile($stylishResult, genDiff($this->jsonBefore, $this->jsonAfter));
        $this->assertStringEqualsFile($stylishResult, genDiff($this->yamlBefore, $this->yamlAfter));
    }

    public function testGenDiffPlain(): void
    {
        $plainResult = $this->getFixtureFullPath('plain_result.txt');
        $format = 'plain';

        $this->assertStringEqualsFile($plainResult, genDiff($this->jsonBefore, $this->jsonAfter, $format));
        $this->assertStringEqualsFile($plainResult, genDiff($this->yamlBefore, $this->yamlAfter, $format));
    }

    public function testGenDiffJson(): void
    {
        $jsonResult = $this->getFixtureFullPath('result_json.json');
        $format = 'json';

        $this->assertJsonStringEqualsJsonFile($jsonResult, genDiff($this->jsonBefore, $this->jsonAfter, $format));
        $this->assertJsonStringEqualsJsonFile($jsonResult, genDiff($this->yamlBefore, $this->yamlAfter, $format));
    }
}
