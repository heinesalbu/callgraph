<?php
function getPhpFiles($dir) {
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

// function generateGraphViz($graph) {
//     $dot = "digraph G {\n";

//     foreach ($graph as $file => $includes) {
//         $file = basename($file);
//         foreach ($includes as $include) {
//             $include = basename($include);
//             $dot .= "    \"$file\" -> \"$include\";\n";
//         }
//     }

//     $dot .= "}\n";

//     return $dot;
// }

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


$dir = '../kcounter';
$graph = buildDependencyGraph($dir);
$dot = generateGraphViz($graph);

file_put_contents('graph.dot', $dot);
exec('dot -Tpng graph.dot -o graph.png');

echo "Graph generated as graph.png\n";

?>
