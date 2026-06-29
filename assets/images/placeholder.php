<?php
// assets/images/placeholder.php
// Generates a simple placeholder image for a service
// Usage: /assets/images/placeholder.php?service=wedding&w=800&h=600

header('Content-Type: image/svg+xml');
header('Cache-Control: public, max-age=3600');

$service = isset($_GET['service']) ? htmlspecialchars($_GET['service']) : 'Photography';
$service = ucwords(str_replace('-', ' ', $service));
$w = isset($_GET['w']) ? min(1600, max(100, (int)$_GET['w'])) : 800;
$h = isset($_GET['h']) ? min(1200, max(100, (int)$_GET['h'])) : 600;

$colors = [
    'wedding'          => ['bg' => '#1a1a1a', 'text' => '#ffffff'],
    'new born'         => ['bg' => '#2a2a2a', 'text' => '#ffffff'],
    'model shoot'      => ['bg' => '#111111', 'text' => '#ffffff'],
    'maternity'        => ['bg' => '#222222', 'text' => '#ffffff'],
    'corporate'        => ['bg' => '#1e1e1e', 'text' => '#ffffff'],
    'couple portraits' => ['bg' => '#191919', 'text' => '#ffffff'],
];

$key = strtolower($service);
$c = $colors[$key] ?? ['bg' => '#1a1a1a', 'text' => '#ffffff'];

echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$w}" height="{$h}" viewBox="0 0 {$w} {$h}">
  <rect width="{$w}" height="{$h}" fill="{$c['bg']}"/>
  <text
    x="50%" y="50%"
    dominant-baseline="middle"
    text-anchor="middle"
    font-family="Barlow, Arial, sans-serif"
    font-size="24"
    font-weight="700"
    letter-spacing="6"
    fill="{$c['text']}"
    opacity="0.3"
    text-transform="uppercase"
  >{$service}</text>
  <text
    x="50%" y="calc(50% + 40px)"
    dominant-baseline="middle"
    text-anchor="middle"
    font-family="Arial, sans-serif"
    font-size="12"
    fill="{$c['text']}"
    opacity="0.15"
    letter-spacing="3"
  >THAKSHI PHOTOGRAPHY</text>
</svg>
SVG;
