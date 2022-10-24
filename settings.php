<?php

final class DefaultSettings {

    public const DEFAULT_VALUES = []; // default values for post parameters - if a parameter is not set, the default value will be used

    public static function applyOn(?array $arr = null): array {
        if ($arr === null) {
            $arr = $_POST;
        }

        foreach (self::DEFAULT_VALUES as $key => $value) {
            if (!isset($arr[$key])) {
                $arr[$key] = $value;
            }
        }

        return $arr;
    }

}