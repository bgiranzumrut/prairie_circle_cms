<?php
session_start();

// Set content type to image/png
header('Content-Type: image/png');

// Generate a random CAPTCHA string
$captcha_text = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5);
$_SESSION['captcha'] = $captcha_text;

// Create an image
$width = 150;
$height = 50;
$image = imagecreate($width, $height);

// Set colors
$bg_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 0, 0);     // Black text
$line_color = imagecolorallocate($image, 64, 64, 64);  // Gray for lines
$dot_color = imagecolorallocate($image, 192, 192, 192); // Light gray for noise

// Fill the background
imagefill($image, 0, 0, $bg_color);

// Add random lines for obfuscation
for ($i = 0; $i < 5; $i++) {
    imageline(
        $image,
        rand(0, $width),
        rand(0, $height),
        rand(0, $width),
        rand(0, $height),
        $line_color
    );
}

// Add random dots for noise
for ($i = 0; $i < 500; $i++) {
    imagesetpixel($image, rand(0, $width), rand(0, $height), $dot_color);
}

// Add the CAPTCHA text with distortion
$font_size = 20;
$font = __DIR__ . '/fonts/Roboto-Regular.ttf'; // Path to your downloaded font
if (!file_exists($font)) {
    imagestring($image, 5, 50, 15, 'FONT ERROR', $text_color);
} else {
    $x = 20;
    for ($i = 0; $i < strlen($captcha_text); $i++) {
        $angle = rand(-20, 20); // Random rotation
        $y = rand(30, 40); // Random y-position
        imagettftext($image, $font_size, $angle, $x, $y, $text_color, $font, $captcha_text[$i]);
        $x += 25; // Adjust spacing
    }
}

// Output the image as PNG
imagepng($image);

// Free up memory
imagedestroy($image);
?>
