<?php

namespace Differ\Differ;

function normalizePath(string $pathToFile): string
{
    return str_starts_with($pathToFile, '/') ? $pathToFile : __DIR__ . '/' . $pathToFile;
}

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $fileContent1 = file_get_contents(normalizePath($pathToFile1));
    $fileContent2 = file_get_contents(normalizePath($pathToFile2));
    $jsonContent1 = json_decode($fileContent1, true);
    $jsonContent2 = json_decode($fileContent2, true);
    $allJsonsKeys = array_unique(array_merge(array_keys($jsonContent1), array_keys($jsonContent2)));
    sort($allJsonsKeys);

    $result = ['{'];
    foreach ($allJsonsKeys as $key) {
        $val1 = json_encode($jsonContent1[$key] ?? null);
        $val2 = json_encode($jsonContent2[$key] ?? null);
        if (array_key_exists($key, $jsonContent1) && array_key_exists($key, $jsonContent2)) {
            if ($val1 === $val2) {
                $result[] = "    {$key}: {$val1}";
            } else {
                $result[] = "  - {$key}: {$val1}";
                $result[] = "  + {$key}: {$val2}";
            }
        } elseif (array_key_exists($key, $jsonContent1)) {
            $result[] = "  - {$key}: {$val1}";
        } else {
            $result[] = "  + {$key}: {$val2}";
        }
    }
    $result[] = '}';

    return implode(PHP_EOL, $result);
}
