<?php // Set the API endpoint URL
$url = 'http://localhost:3000/api/data';

// Set the request options
$options = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'Content-Type: application/json'
    )
);

// Send the request and get the response
$response = file_get_contents($url, false, stream_context_create($options));

// Print the response
echo $response;
?>
