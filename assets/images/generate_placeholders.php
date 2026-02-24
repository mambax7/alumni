<?php
/**
 * Generate placeholder images for Alumni module
 * Run this script once to create all placeholder images
 */

// Image configurations
$images = [
    [
        'filename' => 'default-avatar.png',
        'width' => 400,
        'height' => 400,
        'text' => 'No Photo',
        'bg_color' => [230, 230, 230],
        'text_color' => [100, 100, 100]
    ],
    [
        'filename' => 'default-event.png',
        'width' => 1200,
        'height' => 400,
        'text' => 'Event Banner',
        'bg_color' => [220, 220, 220],
        'text_color' => [80, 80, 80]
    ],
    [
        'filename' => 'logo.png',
        'width' => 128,
        'height' => 128,
        'text' => 'Alumni',
        'bg_color' => [13, 110, 253],
        'text_color' => [255, 255, 255]
    ],
    [
        'filename' => 'iconsmall.png',
        'width' => 16,
        'height' => 16,
        'text' => '',
        'bg_color' => [13, 110, 253],
        'text_color' => [255, 255, 255]
    ],
    [
        'filename' => 'iconbig.png',
        'width' => 32,
        'height' => 32,
        'text' => '',
        'bg_color' => [13, 110, 253],
        'text_color' => [255, 255, 255]
    ]
];

// Admin icon configurations
$adminIcons = [
    ['name' => 'index', 'size' => 16],
    ['name' => 'index', 'size' => 32],
    ['name' => 'profiles', 'size' => 16],
    ['name' => 'profiles', 'size' => 32],
    ['name' => 'events', 'size' => 16],
    ['name' => 'events', 'size' => 32],
    ['name' => 'connections', 'size' => 16],
    ['name' => 'connections', 'size' => 32],
    ['name' => 'about', 'size' => 16],
    ['name' => 'about', 'size' => 32]
];

// Function to create image
function createImage($config) {
    $width = $config['width'];
    $height = $config['height'];

    // Create image
    $image = imagecreatetruecolor($width, $height);

    // Colors
    $bgColor = imagecolorallocate($image, $config['bg_color'][0], $config['bg_color'][1], $config['bg_color'][2]);
    $textColor = imagecolorallocate($image, $config['text_color'][0], $config['text_color'][1], $config['text_color'][2]);

    // Fill background
    imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

    // Add text if provided
    if (!empty($config['text'])) {
        // Calculate font size based on image size
        $fontSize = min($width, $height) / 8;

        // Try to use TrueType font, fallback to built-in
        $fontFile = __DIR__ . '/../../../../xoops_lib/vendor/tecnickcom/tcpdf/fonts/helvetica.ttf';

        if (file_exists($fontFile) && function_exists('imagettfbbox')) {
            $bbox = imagettfbbox($fontSize, 0, $fontFile, $config['text']);
            $textWidth = abs($bbox[4] - $bbox[0]);
            $textHeight = abs($bbox[5] - $bbox[1]);

            $x = ($width - $textWidth) / 2;
            $y = ($height + $textHeight) / 2;

            imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontFile, $config['text']);
        } else {
            // Fallback to built-in font
            $font = 5; // Large built-in font
            $textWidth = imagefontwidth($font) * strlen($config['text']);
            $textHeight = imagefontheight($font);

            $x = ($width - $textWidth) / 2;
            $y = ($height - $textHeight) / 2;

            imagestring($image, $font, $x, $y, $config['text'], $textColor);
        }
    }

    return $image;
}

// Function to create icon
function createIcon($name, $size) {
    $image = imagecreatetruecolor($size, $size);

    // Colors
    $bgColor = imagecolorallocate($image, 13, 110, 253);
    $iconColor = imagecolorallocate($image, 255, 255, 255);

    // Fill background
    imagefilledrectangle($image, 0, 0, $size, $size, $bgColor);

    // Draw simple icon representation
    $margin = (int)($size * 0.2);

    switch ($name) {
        case 'index':
            // Dashboard grid
            $cellSize = (int)(($size - 2 * $margin) / 2);
            for ($i = 0; $i < 2; $i++) {
                for ($j = 0; $j < 2; $j++) {
                    $x1 = $margin + $i * $cellSize;
                    $y1 = $margin + $j * $cellSize;
                    imagefilledrectangle($image, $x1, $y1, $x1 + $cellSize - 2, $y1 + $cellSize - 2, $iconColor);
                }
            }
            break;

        case 'profiles':
            // Person silhouette
            $centerX = (int)($size / 2);
            $centerY = (int)($size / 2);
            $headRadius = (int)($size * 0.15);
            $bodyWidth = (int)($size * 0.4);
            $bodyHeight = (int)($size * 0.3);

            imagefilledellipse($image, $centerX, $centerY - (int)($size * 0.1), $headRadius * 2, $headRadius * 2, $iconColor);
            imagefilledrectangle($image, $centerX - (int)($bodyWidth / 2), $centerY + (int)($size * 0.05),
                               $centerX + (int)($bodyWidth / 2), $centerY + (int)($size * 0.05) + $bodyHeight, $iconColor);
            break;

        case 'events':
            // Calendar
            imagefilledrectangle($image, $margin, $margin, $size - $margin, $size - $margin, $iconColor);
            imagefilledrectangle($image, $margin + 2, $margin + (int)($size * 0.15),
                               $size - $margin - 2, $size - $margin - 2, $bgColor);
            break;

        case 'connections':
            // Network nodes
            $centerX = (int)($size / 2);
            $radius = (int)($size * 0.08);
            imagefilledellipse($image, $margin + $radius, $margin + $radius, $radius * 2, $radius * 2, $iconColor);
            imagefilledellipse($image, $size - $margin - $radius, $margin + $radius, $radius * 2, $radius * 2, $iconColor);
            imagefilledellipse($image, $centerX, $size - $margin - $radius, $radius * 2, $radius * 2, $iconColor);
            break;

        case 'about':
            // Info circle
            $centerX = (int)($size / 2);
            $centerY = (int)($size / 2);
            $radius = (int)($size * 0.35);
            imagefilledellipse($image, $centerX, $centerY, $radius * 2, $radius * 2, $iconColor);
            imagefilledellipse($image, $centerX, $centerY, (int)($radius * 1.6), (int)($radius * 1.6), $bgColor);
            break;
    }

    return $image;
}

// Create main images
echo "Creating placeholder images...\n";

foreach ($images as $config) {
    $image = createImage($config);
    $filepath = __DIR__ . '/' . $config['filename'];

    if (imagepng($image, $filepath)) {
        echo "Created: {$config['filename']} ({$config['width']}x{$config['height']})\n";
    } else {
        echo "Failed to create: {$config['filename']}\n";
    }

    imagedestroy($image);
}

// Create admin icons
echo "\nCreating admin icons...\n";

$iconsDir = __DIR__ . '/icons';
if (!is_dir($iconsDir)) {
    mkdir($iconsDir, 0755, true);
}

foreach ($adminIcons as $iconConfig) {
    $image = createIcon($iconConfig['name'], $iconConfig['size']);
    $filename = "{$iconConfig['name']}{$iconConfig['size']}.png";
    $filepath = $iconsDir . '/' . $filename;

    if (imagepng($image, $filepath)) {
        echo "Created: icons/{$filename}\n";
    } else {
        echo "Failed to create: icons/{$filename}\n";
    }

    imagedestroy($image);
}

echo "\nAll placeholder images created successfully!\n";
echo "\nGenerated files:\n";
echo "- default-avatar.png (400x400) - Default profile photo\n";
echo "- default-event.png (1200x400) - Default event banner\n";
echo "- logo.png (128x128) - Module logo\n";
echo "- iconsmall.png (16x16) - Small module icon\n";
echo "- iconbig.png (32x32) - Large module icon\n";
echo "- icons/*.png - Admin menu icons\n";
