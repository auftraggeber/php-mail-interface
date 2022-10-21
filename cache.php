<?php

final class BodyCache {

    private const CACHE_DIR = "cache";
    public const POST_CACHE_ID = "cache_id";

    private static ?BodyCache $instance = null;

    public static function shared(): BodyCache {
        if (self::$instance === null) {
            $id = $_POST[self::POST_CACHE_ID] ?? null;

            self::$instance = new BodyCache($id);
        }

        return self::$instance;
    }

    public static function deleteOldCaches(): void {
        $dirs = scandir(self::CACHE_DIR);

        foreach ($dirs as $dir) {
            if ($dir === "." || $dir === "..") {
                continue;
            }

            $path = self::CACHE_DIR . DIRECTORY_SEPARATOR . $dir;

            if (is_dir($path)) {
                $time_diff = time() - filemtime($path);

                if ($time_diff > 3600) {
                    rmdir($path);
                }
            }
        }
    }

    private string $name;

    public function __construct(?string $name = null) {
        $this->name = $name ?? uniqid() . "-T-" . microtime();
    }

    public function addBody(int $chunk_index, string $content): bool {
        if (!file_exists(self::CACHE_DIR . DIRECTORY_SEPARATOR . $this->name)) {
            mkdir(self::CACHE_DIR . DIRECTORY_SEPARATOR . $this->name, 0777, true);
        }

        return file_put_contents(self::CACHE_DIR . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . $chunk_index, $content) !== false;
    }

    public function delete() {
        rmdir(self::CACHE_DIR . DIRECTORY_SEPARATOR . $this->name);
    }

    public function getBody(): ?string {
        if (!file_exists(self::CACHE_DIR . DIRECTORY_SEPARATOR . $this->name)) {
            return null;
        }

        $chunks = [];

        foreach (scandir(self::CACHE_DIR . DIRECTORY_SEPARATOR . $this->name) as $chunk) {
            if ($chunk === "." || $chunk === "..") {
                continue;
            }
            if (intval($chunk) != $chunk) {
                continue;
            }

            $chunks[intval($chunk)] = file_get_contents(self::CACHE_DIR . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . $chunk);
        }

        ksort($chunks);

        return implode("", $chunks);
    }

    // getter
    public function getName(): string {
        return $this->name;
    }
}