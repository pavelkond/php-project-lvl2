<?php

namespace Differ\Formatters\Json;

function getAddedProperty(string $property, mixed $value): array
{
    return [
        "op" => "add",
        "property" => $property,
        "value" => $value
    ];
}

function getRemovedProperty(string $property): array
{
    return [
        "op" => "remove",
        "property" => $property
    ];
}

function getUpdatedProperty(string $property, mixed $oldValue, mixed $newValue): array
{
    return [
        "op" => "update",
        "property" => $property,
        "oldValue" => $oldValue,
        "newValue" => $newValue
    ];
}

function iter(array $data, string $path = ''): array
{
    $result = [];
    foreach ($data as $key => $value) {
        $currentPropPath = $path === '' ? $key : "{$path}.{$key}";
        if (array_keys($value) === [0, 1]) {
            [$valBefore, $valAfter] = $value;
            if (is_null($valBefore)) {
                $result[] = getAddedProperty($currentPropPath, json_decode($valAfter, true));
            } elseif (is_null($valAfter)) {
                $result[] = getRemovedProperty($currentPropPath);
            } elseif ($valAfter !== $valBefore) {
                $result[] = getUpdatedProperty(
                    $currentPropPath,
                    json_decode($valBefore, true),
                    json_decode($valAfter, true)
                );
            }
        } else {
            $nestedResult = iter($value, $currentPropPath);
            $result = [...$result, ...$nestedResult];
        }
    }

    return $result;
}

function formatJson(array $data): string
{
    $formatted = iter($data);

    return json_encode($formatted);
}
