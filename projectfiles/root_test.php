<?php
//http://www.7logic.info/2016/11/create-folder-in-google-drive-using-php.html
require_once "google_api/google-api-php-client/src/Google_Client.php";

require_once "google_api/google-api-php-client/src/contrib/Google_DriveService.php";

require_once "google_api/google-api-php-client/src/contrib/Google_Oauth2Service.php";

require_once "google_api/vendor/autoload.php";

function buildService() {//function for first build up service
global $DRIVE_SCOPE, $SERVICE_ACCOUNT_EMAIL, $SERVICE_ACCOUNT_PKCS12_FILE_PATH;

  $key = file_get_contents($SERVICE_ACCOUNT_PKCS12_FILE_PATH);
  $auth = new Google_AssertionCredentials(
      $SERVICE_ACCOUNT_EMAIL,
      array($DRIVE_SCOPE),
      $key);
  $client = new Google_Client();
  $client->setUseObjects(true);
  $client->setAssertionCredentials($auth);
  return new Google_DriveService($client);
}
function createPublicFolder($service, $folderName) {//function for create a new folder
  $file = new Google_DriveFile();
  $file->setTitle($folderName);
  $file->setMimeType('application/vnd.google-apps.folder');

  $createdFile = $service->files->insert($file, array(
      'mimeType' => 'application/vnd.google-apps.folder',
  ));

  $permission = new Google_Permission();
  $permission->setValue('me');
  $permission->setType('anyone');
  $permission->setRole('writer');

 $service->permissions->insert(
      $createdFile->getId(), $permission);

  return $createdFile;
}
try {

$DRIVE_SCOPE = 'https://www.googleapis.com/auth/drive';
$SERVICE_ACCOUNT_EMAIL = '';
$SERVICE_ACCOUNT_PKCS12_FILE_PATH = '';

//0B9QY-_3ueNlJZTdUSmlPcG1NbXc
$service=buildService();
$folderName='root_test';
$parent=createPublicFolder($service, $folderName);
echo $parent->getId();
  } catch (Exception $e) {
  print "An error occurred1: " . $e->getMessage();
  }
?>
