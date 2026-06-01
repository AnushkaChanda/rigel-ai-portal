<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
session_start();
$_SESSION['user_id'] = 1;

$fileName = 'interview_1_1779695870.webm';
$data = json_encode(["fileName" => $fileName]);

$ch = curl_init('http://localhost/rigel-portal%20(1)/rigel-portal(1)/pages/process_interview.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpcode\n";
echo "Response: $response\n";
?>
