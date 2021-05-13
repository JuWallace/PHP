<?php
    require __DIR__ . '/vendor/autoload.php';
    use WindowsAzure\Common\ServicesBuilder;
    use MicrosoftAzure\Storage\Common\ServiceException;
 
$config = [
    'authentication' => [
        'ad' => [
            'client_id' => 'c797acbd-2fcf-47cb-a0ed-5f7a64b0155a',
            'client_secret' => 'e3082615-6b27-4b73-aaaf-4da3e7f9b00b',
            'redirectUri' => 'http://localhost/cursophp.com/fiep/',
            'resource' => 'https://management.azure.com/',
            'urlAuthorize' => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
            'urlAccessToken' => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            'urlResourceOwnerDetails' => '',
            'scopes' => 'openid profile user.read'
        ]
    ]
];

$request = new \Zend\Http\PhpEnvironment\Request();

$ad = new \Magium\ActiveDirectory\ActiveDirectory(
    new \Magium\Configuration\Config\Repository\ArrayConfigurationRepository($config),
    Zend\Psr7Bridge\Psr7ServerRequest::fromZend(new \Zend\Http\PhpEnvironment\Request())
);

$entity = $ad->authenticate();

echo $entity->getName() . '<Br />';
echo $entity->getOid() . '<Br />';
echo $entity->getPreferredUsername() . '<Br />';
 

?>