<?php

namespace Differ\Formatters\Plain;

function stringify(mixed $value): string
{
    switch (gettype($value)) {
        case 'string':
            return "'$value'";
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
    return "Property $property was added with value: $value";
}

function getRemovedRow(string $property): string
{
    return "Property $property was removed";
}

function getUpdatedRow(string $property, string $oldValue, string $newValue): string
{
    return "Property $property was updated. From $oldValue to $newValue";
}

function formatData(array $data, string $propertyPath = ''): array
{
    return array_reduce(array_keys($data), function ($acc, $key) use ($data, $propertyPath) {
        $currentPropPath = $propertyPath === '' ? $key : "$propertyPath.$key";
        if (array_keys($data[$key]) === [0, 1]) {
            [$valBefore, $valAfter] = $data[$key];
            $valBefore = is_null($valBefore) ? $valBefore : stringify(json_decode($valBefore, true));
            $valAfter = is_null($valAfter) ? $valAfter : stringify(json_decode($valAfter, true));
            if (is_null($valBefore)) {
                $acc[] = getAddedRow(stringify($currentPropPath), $valAfter);
            } elseif (is_null($valAfter)) {
                $acc[] = getRemovedRow(stringify($currentPropPath));
            } elseif ($valBefore !== $valAfter) {
                $acc[] = getUpdatedRow(stringify($currentPropPath), $valBefore, $valAfter);
            }
        } else {
            $nestedResult = formatData($data[$key], $currentPropPath);
            $acc = [...$acc, ...$nestedResult];
        }
        return $acc;
    }, []);
}

function formatPlain(array $data): string
{
    $formatted = formatData($data);

    return implode(PHP_EOL, $formatted);
}
