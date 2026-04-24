<?php

/**
 * M-Pesa STK Push (Lipa na M-Pesa Online) Implementation
 * 
 * This script initiates an STK Push (pop-up) on a user's phone to request payment.
 */

// 1. INCLUDE ACCESS TOKEN FILE
// This includes the darajaapi.php file which contains the logic to generate $accessToken
include 'darajaapi.php';

// 2. SET CONFIGURATION AND ENDPOINTS
// Set the timezone to Nairobi as Safaricom's API is time-sensitive
date_default_timezone_set('Africa/Nairobi');

// STK Push API URL for the sandbox environment
$processRequestUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

// Callback URL: Safaricom will send the transaction results to this URL
$callbackUrl = 'https://yourdomain.com/callback.php';

// Business Shortcode and Passkey (Test credentials for sandbox)
$shortCode = '174379';
$passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

// 3. GENERATE AUTHENTICATION PASSWORD
// The password is a base64 encoded string of (Shortcode + Passkey + Timestamp)
$timestamp = date('YmdHis');
$password = base64_encode($shortCode . $passkey . $timestamp);

// 4. TRANSACTION DETAILS
$businessShortCode = $shortCode;
$phone = '254740559541'; // The customer's phone number (format: 254xxxxxxxxx)
$amount = 1;              // The amount to be charged (KSh)
$accountReference = 'Loire pay limited'; // Reference for the transaction (e.g. Invoice No)
$transactionDesc = 'stkpush test';       // Description of the transaction
$partyA = $phone;         // The phone number sending the money
$partyB = $shortCode;     // The shortcode receiving the money

// 5. INITIALIZE cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $processRequestUrl);

// Set HTTP Headers including the dynamic Bearer Token from darajaapi.php
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
));

// 6. PREPARE THE DATA PAYLOAD
$curl_post_data = array(
    'BusinessShortCode' => $shortCode,
    'Password'          => $password,
    'Timestamp'         => $timestamp,
    'TransactionType'   => 'CustomerPayBillOnline', // For Paybill/Till use CustomerPayBillOnline
    'Amount'            => $amount,
    'PartyA'            => $partyA,
    'PartyB'            => $partyB,
    'PhoneNumber'       => $phone,
    'CallBackURL'       => $callbackUrl,
    'AccountReference'  => $accountReference,
    'TransactionDesc'   => $transactionDesc,
);

// Convert the data array into a JSON string
$curl_post_data = json_encode($curl_post_data);

// 7. EXECUTE THE POST REQUEST
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);

// Execute request and store response
$curl_response = curl_exec($curl);

// 8. DISPLAY THE RESPONSE
// This will show if the request was successfully accepted by Safaricom
echo $curl_response;

// 9. CLOSE cURL SESSION
curl_close($curl);

?>
