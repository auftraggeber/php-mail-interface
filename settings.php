<?php

final class DefaultSettings {

    /**
     * default values for post parameters - if a parameter is not set, the default value will be used
     * @param array $post the post parameters with their default values
     */
    protected static function getDefaultValues(): array {
        return [];
    }

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