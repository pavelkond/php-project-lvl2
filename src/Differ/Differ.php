<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\Formatters\format;

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

function getDifference(object $dataBefore, object $dataAfter)
{
    $dataKeys = array_unique(
        array_merge(
            array_keys(get_object_vars($dataBefore)),
            array_keys(get_object_vars($dataAfter))
        )
    );
    sort($dataKeys);
    $diff = array_reduce($dataKeys, function ($acc, $key) use ($dataBefore, $dataAfter) {
        if (property_exists($dataBefore, $key) && property_exists($dataAfter, $key)) {
            $acc[$key] = is_object($dataBefore->$key) && is_object($dataAfter->$key)
                ? getDifference($dataBefore->$key, $dataAfter->$key)
                : [json_encode($dataBefore->$key), json_encode($dataAfter->$key)];
        } else {
            $acc[$key] = property_exists($dataBefore, $key)
                ? [json_encode($dataBefore->$key), null]
                : [null, json_encode($dataAfter->$key)];
        }
        return $acc;
    }, []);

    return $diff;
}


function genDiff(string $pathToFile1, string $pathToFile2, string $formatType = 'stylish'): string
{
    $dataBefore = parseFile($pathToFile1);
    $dataAfter = parseFile($pathToFile2);
    $diff = getDifference($dataBefore, $dataAfter);

    return format($diff, $formatType);
}
