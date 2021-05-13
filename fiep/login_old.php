<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
 
error_reporting(-1);
ini_set('display_errors', 'On');
 
$provider = new TheNetworg\OAuth2\Client\Provider\Azure([
 'clientId' => 'b1a31d9a-ebdf-44f4-915f-82497347c0aa',
 'clientSecret' => 'c.LkIVk69~5cU-11ijBBm2X9~M_BiK921-',
 'redirectUri' => 'http://localhost:8000/observatorio/callback',
 'resource' => 'https://management.azure.com/',
 'urlAuthorize' => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
 'urlAccessToken' => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
 'urlResourceOwnerDetails' => '',
 'scopes' => 'openid profile user.read'
]);
 
if (!isset($_GET['code'])) {
 
 // If we don't have an authorization code then get one
 $authUrl = $provider->getAuthorizationUrl();
 $_SESSION['oauth2state'] = $provider->getState();
 header('Location: '.$authUrl);
 exit;
 
} else {
 
 // Try to get an access token (using the authorization code grant)
 $token = $provider->getAccessToken('authorization_code', [
 'code' => $_GET['code']
 ]);
 
 $accesstoken = $provider->getAccessToken('refresh_token', [
 'refresh_token' => $token->getRefreshToken(),
 'resource' => 'https://management.core.windows.net/'
 ]);
 
 $bearertoken = "Bearer " . $accesstoken->getToken();
 
 $client = new GuzzleHttp\Client([
 'base_uri' => 'https://management.azure.com/',
 'timeout' => 2.0,
 ]);
 
 try {
 $result = $client->request('GET', "/subscriptions/?api-version=2015-01-01", [
 'headers' => [
 'User-Agent' => 'testing/1.0',
 'Accept' => 'application/json',
 'Authorization' => $bearertoken
 ]
 ]);
 } catch (RequestException $e) {
 echo Psr7\str($e->getRequest());
 if ($e->hasResponse()) {
 echo Psr7\str($e->getResponse());
 }
 }
 echo $result->getBody();
 
}
 
?>