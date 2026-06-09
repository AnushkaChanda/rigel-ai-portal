<?php
$ch = curl_init('http://localhost:8000/pages/process_interview.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['fileName' => 'interview_1_1779695870.webm']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$res = curl_exec($ch);
echo "RESPONSE: " . $res . "\n";
?>
