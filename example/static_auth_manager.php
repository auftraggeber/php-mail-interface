<?php
require_once 'auth.php';

final class StaticAuthManager extends IAuthManager {

    private array $auth_keys;

    public function __construct(array $keys)
    {
        $this->auth_keys = $keys;
    }

    public function validAuthKey(string $key): bool
    {
        return in_array($key, $this->auth_keys);
    }
}
$auth_manager = new StaticAuthManager(["static key 1", "static key 2"]);
$auth_manager->setAuthHeader("Auth-Token");
IAuthManager::setAuthManager($auth_manager);