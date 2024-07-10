<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>PHP Dependency Graph Generator</title>
    <style>
        /* Inkluder CSS-styling her */
        ellipse {
            fill: #f9f9f9;
            stroke: #333;
            stroke-width: 1px;
        }
        path {
            stroke: #333;
            stroke-width: 2px;
        }
    </style>
</head>
<body>
    <h1>PHP Dependency Graph Generator</h1>
    
    <form action="generate.php" method="post"> 
        <label for="directory">Choose a directory:</label>
        <select name="directory" id="directory">
            <?php include("selectbox.php"); ?>
        </select>
        <button type="submit">Generate</button>
    </form>
    
    <div id="graph-container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Inkluder SVG-innholdet direkte
            echo file_get_contents('php://input');
        }
        ?>
    </div>
    
    <script>
        // Inkluder JavaScript her for å manipulere SVG
        document.addEventListener('DOMContentLoaded', (event) => {
            // Eksempel: Endre farge på nodene ved klikk
            document.querySelectorAll('ellipse').forEach(node => {
                node.addEventListener('click', function() {
                    this.style.fill = 'yellow';
                });
            });
        });
    </script>
</body>
</html>
