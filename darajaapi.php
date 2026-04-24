<?php

/**
 * M-Pesa Daraja API Access Token Generation
 * 
 * This script demonstrates how to authenticate with the Safaricom Daraja API 
 * and obtain an OAuth access token, which is required for all other API requests.
 */

// Your App Credentials (found in the Safaricom Developer Portal)
$consumerKey = 'GHqQ2vmGrm6eFIXxdXPZq5DoDnAwC2xelWw8G6oKeFgCe6dr';
$consumerSecret = 'ZRME5XoMfdE5Wx2d4WLdfkBZECNiRdFAGhgpGNVErvjUabZHzhz5hAgBMu0XvSfy';

// The endpoint for generating an OAuth access token
$apiUrl = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

// Initialize a cURL session
$ch = curl_init();

// Set the URL for the request
curl_setopt($ch, CURLOPT_URL, $apiUrl);

// Set the transfer to return the output as a string instead of outputting it directly
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Set the HTTP header to specify the content type as JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

/**
 * Authentication Header
 * Note: For production, you would typically use Basic Auth with base64_encode("$consumerKey:$consumerSecret")
 * However, Daraja's /generate endpoint also accepts credentials via the URL or specific internal handling
 * depending on the environment configuration.
 */
curl_setopt($ch, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);

// Execute the cURL request and store the response
$response = curl_exec($ch);

// Close the cURL session to free up system resources
curl_close($ch);

// Decode the JSON response into an associative array
$responseBody = json_decode($response, true);

// Extract the access token from the response
// This token is typically valid for 3600 seconds (1 hour)
if (isset($responseBody['access_token'])) {
    $accessToken = $responseBody['access_token'];
    echo "<h2 style='color: green;'>Access Token generated successfully!</h2>";
    echo "<p><strong>Token:</strong> <code>" . $accessToken . "</code></p>";
} else {
    echo "<h2 style='color: red;'>Failed to generate access token.</h2>";
    echo "<p><strong>Response from Safaricom:</strong></p>";
    echo "<pre>" . print_r($responseBody, true) . "</pre>";
}

?>
