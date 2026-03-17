<?php

$cssUrl = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap';
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36\r\n"
    ]
];
$context = stream_context_create($opts);
$css = file_get_contents($cssUrl, false, $context);

if (!$css) {
    die("Failed to download CSS\n");
}

$fontDir = __DIR__ . '/fonts';
if (!is_dir($fontDir)) {
    mkdir($fontDir, 0777, true);
}

preg_match_all('/url\((https:\/\/fonts\.gstatic\.com\/[^)]+)\)/', $css, $matches);

foreach ($matches[1] as $url) {
    $filename = basename(parse_url($url, PHP_URL_PATH));
    $filepath = $fontDir . '/' . $filename;
    
    if (!file_exists($filepath)) {
        echo "Downloading $filename...\n";
        $fontData = file_get_contents($url, false, $context);
        if ($fontData) {
            file_put_contents($filepath, $fontData);
        } else {
            echo "Failed to download $url\n";
        }
    }
    
    // Replace URL in CSS
    $css = str_replace($url, './fonts/' . $filename, $css);
}

file_put_contents(__DIR__ . '/inter.css', $css);
echo "Fonts downloaded successfully.\n";
