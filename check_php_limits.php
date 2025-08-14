<?php
// Check current PHP upload limits
echo "<h2>Current PHP Upload Configuration</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Setting</th><th>Current Value</th></tr>";
echo "<tr><td>upload_max_filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
echo "<tr><td>post_max_size</td><td>" . ini_get('post_max_size') . "</td></tr>";
echo "<tr><td>max_file_uploads</td><td>" . ini_get('max_file_uploads') . "</td></tr>";
echo "<tr><td>max_execution_time</td><td>" . ini_get('max_execution_time') . " seconds</td></tr>";
echo "<tr><td>max_input_time</td><td>" . ini_get('max_input_time') . " seconds</td></tr>";
echo "<tr><td>memory_limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "</table>";

// Convert to bytes for comparison
function convertToBytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int)$val;
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}

$upload_max = convertToBytes(ini_get('upload_max_filesize'));
$post_max = convertToBytes(ini_get('post_max_size'));

echo "<h3>Recommendations:</h3>";
echo "<ul>";
if ($upload_max < 50 * 1024 * 1024) {
    echo "<li>upload_max_filesize should be increased to at least 50M</li>";
}
if ($post_max < 50 * 1024 * 1024) {
    echo "<li>post_max_size should be increased to at least 50M</li>";
}
echo "</ul>";
?>
