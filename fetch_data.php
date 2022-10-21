<?php

final class File {

    const POST_FILE_PATH_PARAM = "file_path_";
    const POST_FILE_NAME_PARAM = "file_name_";

    private static array $files = [];

    public static function fetchFiles() {
        $i = 0;
        while (isset($_POST[self::POST_FILE_PATH_PARAM . $i])) {
            new File($_POST[self::POST_FILE_PATH_PARAM . $i], $_POST[self::POST_FILE_NAME_PARAM . $i]);
            $i++;
        }
    }

    private static function append(File $file): void {
        self::$files[] = $file;
    }

    /**
     * @return File[] all files that were fetched
     */
    public static function getFiles(): array {
        return self::$files;
    }

    private string $path;
    private string $display_name;

    public function __construct(string $path, ?string $display_name) {
        $this->path = $path;
        $this->display_name = $display_name ?? basename($path);

        if (file_exists($this->path)) {
            self::append($this);
        }
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getDisplayName(): string {
        return $this->display_name;
    }
}

?>