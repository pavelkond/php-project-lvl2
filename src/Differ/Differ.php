<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;

function getFileFullPath(string $pathToFile): string
{
    return str_starts_with($pathToFile, '/') ? $pathToFile : __DIR__ . '/../' . $pathToFile;
}

function getFileExtension($pathToFile)
{
    if (str_ends_with($pathToFile, '.json')) {
        return 'json';
    } elseif (str_ends_with($pathToFile, '.yml') || str_ends_with($pathToFile, 'yaml')) {
        return 'yaml';
    }

    return null;
}

function parseFile($pathToFile)
{
    $filePath = getFileFullPath($pathToFile);
    $fileContent = file_get_contents($filePath);
    $fileExtension = getFileExtension($pathToFile);

    return parse($fileContent, $fileExtension);
}

function stringify($value)
{
    $valueType = gettype($value);
    switch ($valueType) {
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'NULL':
            return 'null';
        default:
            return (string) $value;
    }
}


function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $dataBefore = parseFile($pathToFile1);
    $dataAfter = parseFile($pathToFile2);
    $dataKeys = array_unique(
        array_merge(
            array_keys($dataBefore),
            array_keys($dataAfter)
        )
    );
    sort($dataKeys);

    $result = ['{'];
    foreach ($dataKeys as $key) {
        $valBefore = stringify($dataBefore[$key] ?? null);
        $valAfter = stringify($dataAfter[$key] ?? null);
        if (array_key_exists($key, $dataBefore) && array_key_exists($key, $dataAfter)) {
            if ($valBefore === $valAfter) {
                $result[] = "    {$key}: {$valBefore}";
            } else {
                $result[] = "  - {$key}: {$valBefore}";
                $result[] = "  + {$key}: {$valAfter}";
            }
        } elseif (array_key_exists($key, $dataBefore)) {
            $result[] = "  - {$key}: {$valBefore}";
        } else {
            $result[] = "  + {$key}: {$valAfter}";
        }
    }
    $result[] = '}';

    return implode(PHP_EOL, $result);
}
