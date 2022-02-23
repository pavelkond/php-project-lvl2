<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseJson(string $jsonContent)
{
    return json_decode($jsonContent);
}

function parseYaml(string $yamlContent)
{
    return Yaml::parse($yamlContent, Yaml::PARSE_OBJECT_FOR_MAP);
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
