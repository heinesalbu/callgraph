<?php

function getPhpFiles($dir) {
    if (!is_dir($dir)) {
        throw new UnexpectedValueException("Directory '$dir' does not exist");
    }

    $files = [];
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach ($rii as $file) {
        if ($file->isDir()){
            continue;
        }

        if (pathinfo($file->getPathname(), PATHINFO_EXTENSION) === 'php') {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

function analyzeFile($file) {
    $content = file_get_contents($file);
    $includes = [];

    if (preg_match_all('/(include|require)(_once)?\s*\(?\s*[\'"](.*?)[\'"]\s*\)?\s*;/', $content, $matches)) {
        foreach ($matches[3] as $includedFile) {
            $includes[] = $includedFile;
        }
    }

    return $includes;
}

function buildDependencyGraph($dir) {
    $files = getPhpFiles($dir);
    $graph = [];

    foreach ($files as $file) {
        $includes = analyzeFile($file);
        $graph[$file] = $includes;
    }

    return $graph;
}

function generateGraphViz($graph) {
    $dot = "digraph G {\n";
    $dot .= "    graph [rankdir=LR, size=\"8.5,11\", ratio=fill, orientation=portrait];\n";
    $dot .= "    node [shape=ellipse, fontsize=10];\n";
    $dot .= "    edge [fontsize=8];\n";

    foreach ($graph as $file => $includes) {
        $file = basename($file);
        foreach ($includes as $include) {
            $include = basename($include);
            $dot .= "    \"$file\" -> \"$include\";\n";
        }
    }

    $dot .= "}\n";

    return $dot;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dir = $_POST['directory'];
    try {
        $graph = buildDependencyGraph($dir);
        $dot = generateGraphViz($graph);

        $dotFile = '/var/www/callgraph/graph.dot';
        $svgFile = '/var/www/callgraph/graph.svg';

        file_put_contents($dotFile, $dot);
        exec("dot -Tsvg $dotFile -o $svgFile");

        $svgContent = file_get_contents($svgFile);
        echo $svgContent; // Returner SVG-innholdet
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
    }
} else {
    echo "Invalid request method.";
}

?>
