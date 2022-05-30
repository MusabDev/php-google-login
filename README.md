# Google Login with PHP SDK

## Create a Google API Project

Firstly, you need to create an application with Google which will allow you to register your site with Google. It allows you to set up basic information about your website and a couple of technical details as well.

Once you’re logged in with Google, open the [Google Developers console](https://console.developers.google.com/). That should open up the **Google Dashboard** page, as shown in the following screenshot.

### Dashboard

From the top-left menu, click on the **Select a project** link. That should open up a popup, as shown in the following screenshot.

### New Project Pop Up

Click on the **New Project** link and it will ask you to enter the **Project Name** and other details. Fill in the necessary details, as shown in the following example.

### Create New Project

Click on the **Create** button to save your new project. You will be redirected to the **Dashboard** page. Click on the **Credentials** from the left sidebar, and go to the **OAuth consent screen** tab.

### oAuth Consent Screen

On this page, you need to enter the details about your application, like the application name, logo, and a few other details. Fill in the necessary details and save them. For testing purposes, just entering the application name should do it.

Next, click on **Credentials** in the left sidebar. That should show you the **API Credentials** box under the **Credentials** tab, as shown in the following screenshot.

### Credentials Tab

Click **Client credentials > OAuth client ID** to create a new set of credentials for our application. That should present you with a screen that asks you to choose an appropriate option. In our case, select the **Web application** option and click on the **Create** button. You will be asked to provide a few more details about your application.

### App Settings

Enter the details shown in the above screenshot and save it! Of course, you need to set the **Redirect URI** as per your application settings. It is the URL where the user will be redirected after login.

At this point, we’ve created the Google OAuth2 client application, and now we should be able to use this application to integrate Google login on our site. Please note down the **Client ID** and **Client Secret** values that will be required during the application configuration on our end. You can always find the **Client ID** and **Client Secret** when you edit your application.

## Install the Google PHP SDK Client Library

In this section, we’ll see how to install the Google PHP API client library. There are two options you could choose from to install it:

Use composer to download Google API Client

```bash
composer require google/apiclient:"^2.0"
```

## Client Library Integration

Recall that while configuring the Google application, we had to provide the redirect URI in the application configuration, and we set it to redirect to https://localhost/redirect.php. Now it’s time to create the redirect.php file.

Go ahead and create the redirect.php with the following contents.

```php
<?php
require_once 'vendor/autoload.php';

// init configuration
$clientID = '<YOUR_CLIENT_ID>';
$clientSecret = '<YOUR_CLIENT_SECRET>';
$redirectUri = '<REDIRECT_URI>';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;

  // now you can use this profile info to create account in your website and make user logged in.
} else {
  echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
}
?>
```

#### Let’s go through the key parts of the code.

The first thing we need to do is to include the autoload.php file. This is part of Composer and ensures that the classes we use in our script are autoloaded.

```php
require_once 'vendor/autoload.php';
```

Next, there’s a configuration section, which initializes the application configuration by setting up the necessary settings. Of course, you need to replace the placeholders with your corresponding values.

```php
// init configuration
$clientID = '<YOUR_CLIENT_ID>';
$clientSecret = '<YOUR_CLIENT_SECRET>';
$redirectUri = '<REDIRECT_URI>';
```

The next section instantiates the Google_Client object, which will be used to perform various actions. Along with that, we’ve also initialized our application settings.

```php
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
```

Next, we’ve added email and profile scopes, so after login we have access to the basic profile information.

```php
$client->addScope("email");
$client->addScope("profile");
```

Finally, we have a piece of code which does the login flow magic.

```php
// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;

  // now you can use this profile info to create account in your website and make user logged in.
} else {
  echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
}
```

Firstly, let’s go through the else part, which will be triggered when you access the script directly. It displays a link which takes the user to Google for login. It’s important to note that we’ve used the createAuthUrl method of the Google_Client to build the OAuth URL.

After clicking on the Google login link, users will be taken to the Google site for login. Once they log in, Google redirects users back to our site by passing the code query string variable. And that’s when the PHP code in the if block will be triggered. We’ll use the code to exchange the access token.

Once we have the access token, we can use the Google_Service_Oauth2 service to fetch the profile information of the logged-in user.

So in this way, you’ll have access to the profile information once the user logs in to the Google account. You can use this information to create accounts on your site, or you could store it in a session. Basically, it’s up to you how you use this information and respond to the fact that the user is logged in to your site.
