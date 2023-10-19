<?php 
$dir = "/path/to/directory"; // Replace with the path to your directory
$files = scandir($dir);

foreach($files as $file) {
    if($file !== '.' && $file !== '..') {
        echo $file . "<br>";
    }
}
?>