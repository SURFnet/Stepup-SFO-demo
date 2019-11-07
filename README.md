# SURFconext SA Second Factor Only Sample Application

This application demonstrates using the second factor only (SFO) authentication endpoint of SURFconext 
Strong Authentication (SA). SFO authentication is for applications that want to use SURFconext Strong 
Authentication to authenticate *only* the second authentication factor of the user. These applications 
use some other, application specific, means of handling the first factor authentications and establishing the
identity of the user.

Applications that do not use SFO use the normal SURFconext Strong Authentication endpoint and get
all of the above in one authentication step.

More information about SURFconext Strong Authentication can be found at:

* http://www.surfconext.nl - About SURFconext
* https://wiki.surfnet.nl/display/surfconextdev/SURFconext+Strong+Authentication - SURFconext Strong Authentication
* https://wiki.surfnet.nl/display/surfconextdev/Second+Factor+Only+(SFO)+Authentication - Description of SFO

This demo shows two different methods of using SFO:

1. *Using SFO from an application*. This method is demonstrated by going to [http://localhost:8080](http://localhost:8080). You can find the "application" in the /src and /www directories. The application is in complete control over how and when the 1st and second factor of the user are authenticated. 
2. Advanced and optional -- *Using SimpleSAMLphp as a SAML proxy* to handle the SFO authentication for another application. This method demonstrates how to setup SimpleSAMLphp as SAML proxy that receives an authentication request from an external application and takes care of the authentication of a user to the SFO endpoint on behalf of the external application. In this setup the first factor authentication of the user is provided by the webserver (e.g. using HTTP BasicAuth, IWA, etc) and is available in a `$_SERVER[]` variable or HTTP-header. Setting up this first factor authentication is out of scope of the demo. An external SAML SP (not part of the demo) can then use the SimpleSAMLphp proxy as IdP. The 'authsourcewrapper' module that is included in this demo is used to add the Subject NameID to the AuthnRequest that is required by SFO based on the first factor authentication from the webserver. 

## Installation

The installation instructions are for running the demo using the php builtin webserver.

Requirements:

* The demo should work with PHP version >= 5.5 (cli version). Tested with 5.5, 5.6 and 7.1.
* Installation uses composer. See [https://getcomposer.org/download/]() for download/installation instructions.

Installation:

1. Clone this repository locally
2. Change to the root of this repository
3. Run `composer install`
4. Start the PHP builtin webserver using `php -S localhost:8080 -t ./www`

After installation you can browse to [http://localhost:8080](http://localhost:8080) to access the first demo (Using SFO from an application). The SimpleSAMLphp admin interface can be accessed from [http://localhost:8080/simplesaml](http://localhost:8080/simplesaml). The password for the 'admin' user is 'admin'. 
The SimpleSAMLphp installation is fully functional. You can use the first authentication step ("native authentication").
There are two user accounts in the demo authsource (see [authsources.php](config/simplesaml/authsources.php#L99-L120)):

* User *student* with password *studentpass*
* User *employee* with password *employeepass*

The SFO authentication will not work yet, because this application is not trusted by the SFO IdP that the demo uses you
will get an error on gateway.pilot.stepup.surfconext.nl. You can have a look at the SAML AuthnRequest that the demo
application generates using the [Firefox saml-tracer plugin](https://addons.mozilla.org/nl/firefox/addon/saml-tracer/).
 
## Configuration

To get a fully functional demo that can be connected to the SURFconext SA Pilot environment you would need to:
- Install the application on a webserver and make it available over HTTPS
- Generate a new SAML signing certificate and key see the `sfo-sp` authsource in [authsources.php](config/simplesaml/authsources.php#L35-L43)
- Update [config.php](config/simplesaml/config.php) with production settings
- Contact info@surfconext.nl and provide the SAML metadata of the SFO SP

### Using SFO from an application

For the first demo (Using SFO from an application) you must:
- Update the `native-auth` authsource in [authsources.php](config/simplesaml/authsources.php#L97-L116) so it can 
  authenticate the users of your institution and provide the attributes to generate the userID for SFO.  

### Using SimpleSAMLphp as a SAML proxy

The second demo requires more configuration. You must:
- Setup a remote SP and add the metadata of this SP to [saml20-sp-remote.php](config/simplesaml/metadata/saml20-sp-remote.php). See the [SP remote metadata reference](https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote). Likewise you will probably need IdP metadata of the SimpleSAMLphp proxy for configuring the Remote SP: http://localhost:8080/simplesaml/saml2/idp/metadata.php 
- Update the configuration of the `sfo-sp-wrapper` authsource in [authsources.php](config/simplesaml/authsources.php#L126-L147) to generate the correct user `uid` and `org`.
