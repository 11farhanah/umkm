<?php

function resolveImageUrl(?string $imagePath, string $fallback): string
{
    if (empty($imagePath)) {
        return $fallback;
    }

    $imageUrl = parse_url($imagePath);
    if ($imageUrl === false) {
        return $fallback;
    }

    $imageHost = strtolower($imageUrl['host'] ?? '');
    $appHost = strtolower(parse_url(APP_URL, PHP_URL_HOST) ?? '');
    $isAppImage = $imageHost !== '' && (
        $imageHost === $appHost
        || in_array($imageHost, ['localhost', '127.0.0.1', '::1'], true)
    );
    $isExternalUrl = preg_match('/^https?:\/\//i', $imagePath) === 1;

    if ($isExternalUrl && !$isAppImage) {
        return $imagePath;
    }

    $relativePath = ltrim($isAppImage ? ($imageUrl['path'] ?? '') : $imagePath, '/');
    if (
        $relativePath === ''
        || preg_match('#(^|[\\\\/])\\.\\.([\\\\/]|$)#', $relativePath)
        || !is_file(APP_PUBLIC . '/' . $relativePath)
    ) {
        return $fallback;
    }

    return APP_URL . '/' . $relativePath;
}
