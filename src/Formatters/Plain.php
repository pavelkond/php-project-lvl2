<?php

namespace Differ\Formatters\Plain;

function stringify(mixed $value): string
{
    switch (gettype($value)) {
        case 'string':
            return "'{$value}'";
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'NULL':
            return 'null';
        case 'array':
            return '[complex value]';
        default:
            return (string) $value;
    }
}

function getAddedRow(string $property, string $value): string
{
    return "Property {$property} was added with value: {$value}";
}

function getRemovedRow(string $property): string
{
    return "Property {$property} was removed";
}

function getUpdatedRow(string $property, string $oldValue, string $newValue): string
{
    return "Property {$property} was updated. From {$oldValue} to {$newValue}";
}

function iter(array $data, string $propertyPath = ''): array
{
    $result = [];
    foreach ($data as $key => $value) {
        $currentPropPath = $propertyPath === '' ? $key : $propertyPath . '.' . $key;
        if (array_keys($value) === [0, 1]) {
            [$valBefore, $valAfter] = $value;
            $valBefore = is_null($valBefore) ? $valBefore : stringify(json_decode($valBefore, true));
            $valAfter = is_null($valAfter) ? $valAfter : stringify(json_decode($valAfter, true));

            if (is_null($valBefore)) {
                $result[] = getAddedRow(stringify($currentPropPath), $valAfter);
            } elseif (is_null($valAfter)) {
                $result[] = getRemovedRow(stringify($currentPropPath));
            } elseif ($valBefore !== $valAfter) {
                $result[] = getUpdatedRow(stringify($currentPropPath), $valBefore, $valAfter);
            }
        } else {
            $nestedResult = iter($value, $currentPropPath);
            $result = [...$result, ...$nestedResult];
        }
    }

    return $result;
}

function formatPlain(array $data): string
{
    $formatted = iter($data);

    return implode(PHP_EOL, $formatted);
}
