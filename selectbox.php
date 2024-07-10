<?php
// Setter rotkatalogen som skal undersøkes
$rootDir = '/var/www';
// Sjekker om rotkatalogen eksisterer
if (is_dir($rootDir)) {
    // Finner alle undermapper i rotkatalogen
    $directories = array_filter(glob($rootDir . '/*'), 'is_dir');
    // Går gjennom hver katalog og legger til et alternativ i nedtrekksmenyen
    foreach ($directories as $dir) {
        $dirName = basename($dir); // Henter katalognavnet
        // Legger til katalogen som et alternativ i nedtrekksmenyen
        echo "<option value=\"$dir\">$dirName</option>";
    }
} else {
    // Viser en melding hvis ingen kataloger ble funnet
    echo "<option value=\"\">No directories found</option>";
}
?>