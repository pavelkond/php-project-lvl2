<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseJson($jsonContent)
{
    return json_decode($jsonContent, true);
}

function parseYaml($yamlContent)
{
    return (array) Yaml::parse($yamlContent, Yaml::PARSE_OBJECT_FOR_MAP);
}

function parse(string $content, string $fileType)
{
    switch ($fileType) {
        case 'json':
            return parseJson($content);
        case 'yaml':
            return parseYaml($content);
        default:
            return false;
    }
}
