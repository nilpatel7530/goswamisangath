<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class WatermarkService
{
    /**
     * Add TrueUnion watermark to an image
     *
     * @param string $imagePath Full path to the image file
     * @param string $watermarkText Text to use as watermark (default: "TrueUnion")
     * @return bool True on success, false on failure
     */
    public static function addWatermark(string $imagePath, string $watermarkText = 'TrueUnion'): bool
    {
        if (!extension_loaded('gd')) {
            \Illuminate\Support\Facades\Log::error('GD extension is not loaded. Watermarking is disabled.');
            return false;
        }

        if (!File::exists($imagePath)) {
            return false;
        }

        // Get image info
        $imageInfo = @getimagesize($imagePath);
        if ($imageInfo === false) {
            return false;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Create image resource based on type
        $image = null;
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($imagePath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($imagePath);
                break;
            default:
                return false;
        }

        if ($image === false) {
            return false;
        }

        // Enable alpha blending for PNG images
        if ($mimeType === 'image/png') {
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

        // Calculate watermark size and position
        $fontSize = max(12, min($width, $height) / 25); // Scale font size based on image size
        $fontPath = self::getFontPath();
        
        // Calculate text dimensions
        $textWidth = 0;
        $textHeight = 0;
        
        if ($fontPath && function_exists('imagettfbbox')) {
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $watermarkText);
            if ($textBox !== false) {
                $textWidth = abs($textBox[4] - $textBox[0]);
                $textHeight = abs($textBox[5] - $textBox[1]);
            }
        }
        
        // Fallback to built-in font if TTF font not available or calculation failed
        if ($textWidth === 0 || $textHeight === 0) {
            $textWidth = imagefontwidth(5) * strlen($watermarkText);
            $textHeight = imagefontheight(5);
            $fontPath = null; // Use built-in font
        }

        // Position: bottom-right corner with padding
        $padding = 15;
        $x = $width - $textWidth - $padding;
        $y = $height - $padding;

        // Create semi-transparent background for watermark
        $bgWidth = $textWidth + 20;
        $bgHeight = $textHeight + 10;
        $bgX = $x - 10;
        $bgY = $y - $textHeight - 5;

        // Create semi-transparent rectangle
        $bgColor = imagecolorallocatealpha($image, 0, 0, 0, 60); // Black with 60% opacity
        imagefilledrectangle($image, $bgX, $bgY, $bgX + $bgWidth, $bgY + $bgHeight, $bgColor);

        // Add watermark text
        $textColor = imagecolorallocate($image, 255, 255, 255); // White text
        
        if ($fontPath && function_exists('imagettftext')) {
            // Use TTF font if available
            imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $watermarkText);
        } else {
            // Fallback to built-in font
            imagestring($image, 5, $x, $y - $textHeight, $watermarkText, $textColor);
        }

        // Save the watermarked image
        $result = false;
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $result = imagejpeg($image, $imagePath, 90); // 90% quality
                break;
            case 'image/png':
                $result = imagepng($image, $imagePath, 9); // Compression level 0-9
                break;
            case 'image/gif':
                $result = imagegif($image, $imagePath);
                break;
            case 'image/webp':
                $result = imagewebp($image, $imagePath, 90);
                break;
        }

        // Free memory
        imagedestroy($image);

        return $result !== false;
    }

    /**
     * Add watermark to image from binary data
     *
     * @param string $imageData Binary image data
     * @param string $imageType Image type (jpeg, png, gif, webp)
     * @param string $watermarkText Text to use as watermark
     * @return string|false Watermarked image binary data or false on failure
     */
    public static function addWatermarkToBinary(string $imageData, string $imageType = 'jpeg', string $watermarkText = 'TrueUnion')
    {
        // Create temporary file
        $tempPath = tempnam(sys_get_temp_dir(), 'watermark_');
        if ($tempPath === false) {
            return false;
        }

        // Write image data to temp file
        File::put($tempPath, $imageData);

        // Add watermark
        $success = self::addWatermark($tempPath, $watermarkText);

        if (!$success) {
            @unlink($tempPath);
            return false;
        }

        // Read watermarked image
        $watermarkedData = File::get($tempPath);
        
        // Clean up temp file
        @unlink($tempPath);

        return $watermarkedData;
    }

    /**
     * Get font path for watermark text
     * Uses system font or falls back to built-in font
     *
     * @return string|null Font path or null for built-in font
     */
    private static function getFontPath(): ?string
    {
        // Try to use a system font
        $possibleFonts = [
            // Windows fonts
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/calibri.ttf',
            // Linux fonts
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/truetype/ttf-dejavu/DejaVuSans-Bold.ttf',
            // macOS fonts
            '/System/Library/Fonts/Supplemental/Arial Bold.ttf',
            '/Library/Fonts/Arial Bold.ttf',
        ];

        foreach ($possibleFonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }

        // Return null to use built-in font
        return null;
    }
}
