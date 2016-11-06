# SURFconext SA Second Factor Only Sample Application

This application demonstrates using the second factor only (SFO) authentication endpoint of SURFconext 
Strong Authentication (SA). SFO authentication is for applications that want to authenticate
*only* the second authentication factor of the user. These applications use some other,
application specific, means of handling the first factor authentications and establishing the
identity of the user.

Applications that do not use SFO use the normal SURFconext Strong Authentication endpoint and get
all of the above in one authentication step.

More information about SURFconext Strong Authentication can be found at:

* http://www.surfconext.nl - About SURFconext
* https://wiki.surfnet.nl/display/surfconextdev/SURFconext+Strong+Authentication - SURFconext Strong Authentication
* https://wiki.surfnet.nl/display/surfconextdev/Second+Factor+Only+%28SFO%29+Authentication - Description of SFO

## Installation

The installation instructions are for running the demo using the php builtin webserver.

Requirements:

* The demo should work with PHP version >= 5.4 (cli version). Tested with 5.5 and 5.6.
* Installation uses composer. See [https://getcomposer.org/download/]() for download/installation instructions.

Installation:

1. Clone this repository locally
2. Change to the root of this repository
3. Run `composer install`
4. Start the PHP builtin webserver using `php -S localhost:8080 -t ./www`

After installation you can browse to [http://localhost:8080](http://localhost:8080) to access the demo. The SimpleSAMLphp 
admin interface can be accessed from [http://localhost:8080/simplesaml](http://localhost:8080/simplesaml) the password
for the 'admin' user is 'admin'. 
The SimpleSAMLphp installation is fully functional. You can use the first authentication step ("native authentication").
There are two user accounts in the demo authsource (see [authsources.php](config/simplesaml/authsources.php#L99-120)):

* User *student* with password *studentpass*
* User *employee* with password *employeepass*

The SFO authentication will not work yet, because this application is not trusted by the SFO IdP that the demo uses you
will get an error on gateway.pilot.stepup.surfconext.nl. You can have a look at the SAML AuthnRequest that the demo
application generates using the [Firefox saml-tracer plugin](https://addons.mozilla.org/nl/firefox/addon/saml-tracer/).
 
## Configuration

To get a fully functional demo that can be connected to the SURFconext SA Pilot environment you would need to:
- Install the application on a webserver and make it available over HTTPS
- Generate a new SAML signing certificate and key see the `sfo-sp` authsource in [authsources.php](config/simplesaml/authsources.php#L35-43)
- Update [config.php](config/simplesaml/config.php) with production settings
- Contact info@surfconext.nl and provide the SAML metadata of the SFO SP
- Update the `native-auth` authsource in [authsources.php](config/simplesaml/authsources.php#L99-120) so it can 
  authenticate the users of your institution and provide the attributes to generate the userID for SFO.  
