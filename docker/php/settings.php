<?php

final class DefaultSettings {

    /**
     * default values for post parameters - if a parameter is not set, the default value will be used
     * @param array $post the post parameters with their default values
     */
    protected static function getDefaultValues(): array {
        return [
            "smtp_host" => getenv("SMTP_HOST"),
            "smtp_port" => intval(getenv("SMTP_PORT")),
            "smtp_username" => getenv("SMTP_USERNAME"),
            "smtp_password" => getenv("SMTP_PASSWORD"),
            "smtp_encryption" => getenv("SMTP_ENCRYPTION"),
            "smtp_auth" => intval(getenv("SMTP_AUTH")),
        ];
    }

    public static function applyOn(?array $arr = null): array {
        if ($arr === null) {
            $arr = $_POST;
        }

        foreach (self::getDefaultValues() as $key => $value) {
            if (!isset($arr[$key])) {
                $arr[$key] = $value;
            }
        }

        return $arr;
    }

}