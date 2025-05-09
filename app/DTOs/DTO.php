<?php

namespace App\DTOs;

abstract class DTO
{
    /**
     * This method should be overridden in the child class.
     * It should return an array mapping the input data keys to the DTO properties.
     *
     * @return array The mapping of input keys to DTO properties (defaults to an empty array).
     */
    protected static function Translate(): array
    {
        return [];
    }

    /**
     * Converts an array or object into a DTO instance, using the translation map defined in the `Translate()` method.
     *
     * @param array|object $data The data to be converted into a DTO.
     *
     * @return object The DTO instance with the translated values assigned to its properties.
     */
    public static function ToDTOTranslate(array|object $data)
    {
        $dto = new static();
        $dtoTranslated = (object)[];
        $dataArray = (array) $data;
        $translate = static::Translate();

        foreach ($dataArray as $key => $value) {
            if (isset($translate[$key]) && property_exists($dto, $key)) {
                $translatedKey = $translate[$key];

                $reflection = new \ReflectionProperty($dto, $key);
                $tipo = $reflection->getType();

                if ($tipo && !$tipo->isBuiltin()) {
                    $clase = $tipo->getName();
                    if (is_subclass_of($clase, DTO::class)) {
                        // Obtener las traducciones del sub-DTO
                        $subTranslate = $clase::Translate();

                        // Si no hay traducciones, usar ToDTO
                        if (empty($subTranslate)) {
                            $dtoTranslated->{$translatedKey} = $clase::ToDTO($value);
                        } else {
                            // Si hay traducciones, usar ToDTOTranslate
                            $dtoTranslated->{$translatedKey} = $clase::ToDTOTranslate($value);
                        }
                    } else {
                        $dtoTranslated->{$translatedKey} = $value;
                    }
                } else {
                    $dtoTranslated->{$translatedKey} = $value;
                }
            }
        }
        return $dtoTranslated;
    }


    /**
     * Converts an array of data (arrays or objects) into a list of DTO instances using the translation map from `Translate()`.
     *
     * @param array $data The data to be converted into a list of DTOs.
     *
     * @return array A list of DTO instances with the translated values assigned to their properties.
     */
    public static function ToDTOListTranslate(array $data)
    {
        $DTOList = [];

        foreach ($data as $dto) {
            if (is_array($dto)) {
                array_push($DTOList, self::ToDTOTranslate($dto));
            }
        }

        return $DTOList;
    }

    /**
     * Converts an array or object into a DTO instance, directly assigning values to properties that match the input keys.
     *
     * @param array|object $data The data to be converted into a DTO.
     *
     * @return static The DTO instance with values assigned to its properties.
     */
    public static function ToDTO(array|object $data): static
    {
        $dto = new static();
        $dataArray = is_array($data) ? $data : (array) $data;

        $reflectionClass = new \ReflectionClass($dto);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $key = $property->getName();

            if (!array_key_exists($key, $dataArray)) {
                continue;
            }

            $tipo = $property->getType();
            $tipos = $tipo instanceof \ReflectionUnionType ? $tipo->getTypes() : [$tipo];
            $value = $dataArray[$key];

            if ($value === null) {
                $dto->{$key} = null;
                continue;
            }

            if (is_object($value) || is_array($value)) {
                foreach ($tipos as $tipoEspecifico) {
                    if ($tipoEspecifico != null && !$tipoEspecifico->isBuiltin()) {
                        $clase = $tipoEspecifico->getName();
                        if (is_subclass_of($clase, DTO::class)) {
                            $dto->{$key} = $clase::ToDTO((array) $value);
                            break;
                        }
                    }
                }
                continue;
            }

            foreach ($tipos as $tipoEspecifico) {
                $dto->{$key} = $value;
                break;
            }
        }

        return $dto;
    }

    /**
     * Converts an array of data (arrays or objects) into a list of DTO instances, directly assigning values to properties.
     *
     * @param array $data The data to be converted into a list of DTOs.
     *
     * @return array A list of DTO instances with values assigned to their properties.
     */
    public static function ToDTOList(array $data)
    {
        $DTOList = [];

        foreach ($data as $dto) {
            if (is_array($dto)) {
                array_push($DTOList, self::ToDTO($dto));
            } elseif (is_object($dto)) {
                array_push($DTOList, self::ToDTO((array)$dto));
            }
        }

        return $DTOList;
    }


    /**
     * Convierte el DTO en un array, incluyendo propiedades anidadas.
     */
    public function toArray(): array
    {
        return self::convertToArray($this);
    }

    /**
     * Convierte un iterable (array, colección) de DTOs en un array de arrays.
     */
    public static function iterableToArray(iterable $data): array
    {
        return self::convertIterableToArray($data);
    }

    /**
     * Método estático para convertir cualquier DTO o iterable.
     */
    public static function convertToArray(object $dto): array
    {
        $array = [];
        foreach (get_object_vars($dto) as $key => $value) {
            $array[$key] = self::convertValueToArray($value);
        }
        return $array;
    }

    private static function convertValueToArray(mixed $value): mixed
    {
        return match (true) {
            $value instanceof \DateTime => $value->format('Y-m-d H:i:s'),
            $value instanceof DTO => $value->toArray(),
            is_iterable($value) => self::convertIterableToArray($value),
            default => $value
        };
    }

    private static function convertIterableToArray(iterable $iterable): array
    {
        $result = [];
        foreach ($iterable as $key => $value) {
            $result[$key] = self::convertValueToArray($value);
        }
        return $result;
    }
}
