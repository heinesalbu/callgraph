<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Dependency Graph Generator</title>
</head>
<body>
    <h1>PHP Dependency Graph Generator</h1>
    <form action="generate.php" method="post">
        <label for="directory">Choose a directory:</label>
        <select name="directory" id="directory">
            <?php
            $rootDir = '/var/www';
            if (is_dir($rootDir)) {
                $directories = array_filter(glob($rootDir . '/*'), 'is_dir');
                foreach ($directories as $dir) {
                    $dirName = basename($dir);
                    echo "<option value=\"$dir\">$dirName</option>";
                }
            } else {
                echo "<option value=\"\">No directories found</option>";
            }
            ?>
        </select>
        <button type="submit">Generate</button>
    </form>
    <?php
    if (isset($_GET['image'])) {
        $image = htmlspecialchars($_GET['image']);
        echo "<h2>Generated Graph</h2>";
        echo "<img src=\"$image\" alt=\"Generated graph\">";
    }
    ?>
</body>
</html>
