<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.groq.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$result = curl_exec($ch);
$error  = curl_error($ch);
$info   = curl_getinfo($ch);
echo 'HTTP Status: ' . $info['http_code'] . PHP_EOL;
echo 'Result: ' . $result . PHP_EOL;
echo 'Error: ' . $error . PHP_EOL;
curl_close($ch);