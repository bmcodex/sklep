<?php
// Czyszczenie cache Laravel
$commands = [
    'rm -rf storage/framework/cache/*',
    'rm -rf storage/framework/views/*',
    'rm -rf bootstrap/cache/*'
];

foreach ($commands as $cmd) {
    exec($cmd, $output, $return);
    echo "Wykonano: $cmd\n";
}

echo "Cache wyczyszczony!\n";
?>
