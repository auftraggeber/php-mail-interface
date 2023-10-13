<?php
/**
 * The AuthManager verifies the Auth-Keys.
 * There is only one active manager.
 * @author Jonas Langner
 * @version 0.1.1
 * @since 12.10.2023
 */
abstract class IAuthManager {
    private static ?IAuthManager $shared = null;

    public static function setAuthManager(IAuthManager $m): void {
        self::$shared = $m;
    }

    public static function shared(): IAuthManager {
        return self::$shared ?? new NullAuthManager();
    }

    private string $auth_header = 'Authorization';

    public function setAuthHeader(string $header): void {
        $this->auth_header = $header;
    }

    public function getAuthHeader(): string {
        return $this->auth_header;
    }

    public abstract function validAuthKey(string $key): bool;
}

/**
 * A default AuthManager to avoid null pointers.
 * @author Jonas Langner
 * @version 0.1.1
 * @since 12.10.2023
 */
final class NullAuthManager extends IAuthManager {
    public function validAuthKey(string $key): bool
    {
        return false;
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

    public function validAuthKey(string $key): bool
    {
        return in_array($key, $this->auth_keys);
    }
}

/**
 * Checks for authentication.
 * If request is not authorized, the site will shutdown (404).
 */
function auth_this_http_request(): void {
    $headers = getallheaders();

    $auth_header_key = IAuthManager::shared()->getAuthHeader();

    if (is_array($headers)) {
        $auth_key = isset($headers[$auth_header_key]) ? $headers[$auth_header_key] : null;

        if ($auth_key !== null) {

            if (IAuthManager::shared()->validAuthKey($auth_key)){
                return;
            }
        }
    }

    http_response_code(404); // hide endpoint
    die;
}