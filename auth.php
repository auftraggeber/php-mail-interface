<?php
/**
 * The AuthManager holds the allowed Auth-Keys.
 * There is only one active manager.
 * @author Jonas Langner
 * @version 0.1.1
 * @since 12.10.2023
 */
abstract class IAuthManager {
    public static ?IAuthManager $shared = null;

    public static function setAuthManager(IAuthManager $m): void {
        self::$shared = $m;
    }

    public static function shared(): IAuthManager {
        return self::$shared ?? new NullAuthManager();
    }

    public abstract function getAuthKeys(): array;
}

/**
 * A default AuthManager to avoid null pointers.
 * @author Jonas Langner
 * @version 0.1.1
 * @since 12.10.2023
 */
final class NullAuthManager extends IAuthManager {
    public function getAuthKeys(): array
    {
        return [];
    }
}

/**
 * A AuthManager that reads the keys from a file.
 * @author Jonas Langner
 * @version 0.1.1
 * @since 12.10.2023
 */
final class JSONAuthManager extends IAuthManager {
    private array $auth_keys = [];

    public function __construct(string $file)
    {
        $content = file_get_contents($file);

        if ($content !== false) {
            $arr = json_decode($content);

            if ($arr !== null) {
                $this->auth_keys = $arr;
            }
        }
    }

    public function getAuthKeys(): array
    {
        return $this->auth_keys;
    }
}

/**
 * Checks for authentication.
 * If request is not authorized, the site will shutdown (404).
 * Looking for Bearer-Token.
 */
function auth_this_http_request(): void {
    $headers = getallheaders();

    if (is_array($headers)) {
        $auth_key = isset($headers['Authorization']) ? $headers['Authorization'] : null;

        if ($auth_key !== null) {
            $auth_key_subs = explode(" ", $auth_key);
            $auth_key = $auth_key_subs[count($auth_key_subs) - 1];

            if (in_array($auth_key, IAuthManager::shared()->getAuthKeys())){
                return;
            }
        }
    }

    http_response_code(404); // hide endpoint
    die;
}