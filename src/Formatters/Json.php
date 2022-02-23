<?php

namespace Differ\Formatters\Json;

function getPropertyDiff(string $property, string $operationType, $value = null, $newValue = null): array
{
    $diff = ["op" => $operationType, "property" => $property];
    switch ($operationType) {
        case 'add':
            $diff['value'] = $value;
            break;
        case 'update':
            $diff['oldValue'] = $value;
            $diff['newValue'] = $newValue;
            break;
    }

    return $diff;
}

function formatData(array $data, string $propertyPath = ''): array
{
    return array_reduce(array_keys($data), function ($acc, $key) use ($data, $propertyPath) {
        $currentPropPath = $propertyPath === '' ? $key : "$propertyPath.$key";
        if (array_keys($data[$key]) === [0, 1]) {
            [$valBefore, $valAfter] = $data[$key];
            if (is_null($valBefore)) {
                $acc[] = getPropertyDiff($currentPropPath, 'add', json_decode($valAfter, true));
            } elseif (is_null($valAfter)) {
                $acc[] = getPropertyDiff($currentPropPath, 'remove');
            } elseif ($valAfter !== $valBefore) {
                $acc[] = getPropertyDiff(
                    $currentPropPath,
                    'update',
                    json_decode($valBefore, true),
                    json_decode($valAfter, true)
                );
            }
        } else {
            $nestedResult = formatData($data[$key], $currentPropPath);
            $acc = [...$acc, ...$nestedResult];
        }
        return $acc;
    }, []);
}

function formatJson(array $data): string
{
    $formatted = formatData($data);

    return json_encode($formatted);
}
