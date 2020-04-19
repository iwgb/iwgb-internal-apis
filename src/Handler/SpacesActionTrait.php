<?php

namespace Iwgb\Media\Handler;

use Mimey\MimeTypes;

trait SpacesActionTrait {

    private static string $NAME_REGEX = '/^[a-zA-Z0-9\-]*$/';
    private static string $FILE_NAME_REGEX = '/^[a-zA-Z0-9\-]+\.[a-zA-Z0-9]+$/';

    protected static function getExtension(string $fileName): string {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    protected static function isImage(string $fileName): bool {
        return explode('/',
            (new MimeTypes())->getMimeType(self::getExtension($fileName)),
        )[0] === 'image';
    }

    private function getEncodedRoot(): string {
        return base64_encode($this->settings['spaces']['publicRoot']);
    }

    private function getRoot(bool $trailingSlash = true): string {
        $root = $this->settings['spaces']['publicRoot'];
        if ($trailingSlash) {
            return $root;
        }
        return substr($root, 0, -1);
    }
}