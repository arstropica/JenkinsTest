<?php
// Check if the file path is provided as a command line argument
if (empty($argv[1])) {
    die('Please provide a file path.');
}

// Use the provided file path
$file = file_get_contents($argv[1]);
$filename = basename($argv[1]);
$url = 'http://192.168.50.7:82/wp-json/wp/v2/media/';
$ch = curl_init();
$username = 'arstropica';
$password = "EKbE YmVM 4Y3h hjWc 89YR 74lO";

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Disposition: form-data; filename="' . $filename . '"',
    'Authorization: Basic ' . base64_encode($username . ':' . $password),
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = json_decode(curl_exec($ch), true);
curl_close($ch);

if ($result && isset($result['guid']['raw'])) {
    $postUrl = 'http://192.168.50.7:82/wp-json/wp/v2/posts/';
    $postData = [
        'title' => 'New Document Post',
        'content' => 'Here is my new post with document: [pdf-embedder url="' . $result['guid']['raw'] . '"]',
        'status' => 'publish'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $postUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($username . ':' . $password)
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $postResult = curl_exec($ch);
    curl_close($ch);
    echo $postResult;
} else {
    echo "Failed to create post.";
}
?>
