<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private $file1;
    private $file2;
    private $resultFile;

    public function getFixtureFullPath(string $fileName): string
    {
        $fullPath = [__DIR__, 'fixtures', $fileName];
        return implode('/', $fullPath);
    }

    public function testGenDiff(): void
    {
        $this->file1 = $this->getFixtureFullPath('plain_file_1.json');
        $this->file2 = $this->getFixtureFullPath('plain_file_2.json');
        $this->resultFile = $this->getFixtureFullPath('plain_result.txt');

        $this->assertStringEqualsFile($this->resultFile, genDiff($this->file1, $this->file2));
    }
}
