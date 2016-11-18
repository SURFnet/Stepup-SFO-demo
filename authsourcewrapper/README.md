# simplesaml-module-authsourcewrapper

The authsourcewrapper is a authentication module for simpleSAMLphp. It "wraps" another simplesaml authentication module and allows you to configure the Params array in the call to the authenticate() function of the wrapped module. This is useful when proxying from a SimpleSAMLphp IdP to authentication modules (like 'saml:SP') that have parameters that can be not set in the configuration of the authsource but can only be provided during login.

You do not need to use this module when using an authentication source from PHP code, because you can specify the options in the call to SimpleSAML_Auth_Simple::login(), requireAuth(), etc. I.e.: 

    $auth = new SimpleSAML_Auth_Simple('default-sp');
    $auth->login(array(
        'ForceAuthn' => true,
    ));

## Configuration

Add the authsourcewrapper to `authsource.php`:
 
    // Define an authsource of type "authsourcewrapper:Wrapper" with id "wrapped-axample-auth"
    'wrapped-example-auth' => array(
        'authsourcewrapper:Wrapper',
    
        // The ID of the authsource to wrap 
        'authsource' => 'example-auth',
    
        // Closure (function) that is evaluated when authenticating to the wrapped source
        // Returns the params array to use in the call to the login function.
        'loginParams' => function( $authID ) {
            return array(
                // add authentication parameters for the wrapped source here
                // Refer to the documentation of the wrapped auth source for the available
                // parameters.
                
                // E.g. for saml:SP. See: https://simplesamlphp.org/docs/stable/saml:sp
                'ForceAuthn' => true,
            );
        },
    
    ),

## Example

The example configuration below defines SAML proxy. In this example SimpleSAMLphp uses hosted IdP that proxies the authentication from a remote SP to a remote IdP using a hosted SP. The authsourcewrapper is used to add configuration parameters to the  

- The metadata of the sp-remote is defined in saml20-ipd-remote.php
- The metadata of the idp-remote is defined in saml20-ipd-remote.php
- The idp-hosted IdP is configured in saml20-idp-hosted.php
- The `wrapped-sp` and `example-sp` authentication sources are configured in `authsources.php`


    A External SAML SP       SimpleSAML                                             An External SAML IdP
    +-----------+            +------------+  +---------------------------+            +------------+
    | sp-remote | <- SAML -> | idp-hosted |--| wrapped-sp <-> example-sp | <- SAML -> | idp-remote |
    +-----------+            +------------+  |---------------------------+            +------------+
    
    
Configure the module that you want to wrap in `authsources.php`:

    // Hosted SP
    // an authsource of type "saml:SP" with id "example-sp"
    // See: https://simplesamlphp.org/docs/stable/saml:sp
    'example-sp' => array(
        'saml:SP',
        'entityID' => 'https://sp.example.net',
    ),


Configure the authsourcewrapper module in `authsources.php`:

    // Define an authsource of type "authsourcewrapper:Wrapper" with id "wrapper"
    'wrapped-sp' => array(
        'authsourcewrapper:Wrapper',
    
        // The ID of the authsource that is being wrapped 
        'authsource' => 'example-sp',
    
        // Closure (function) that is evaluated when authenticating to the wrapped source
        // Returns the params array to use in the call to the login function.
        // authID is the value of configured for 'authsource'
        'loginParams' => function( $authID ) {
            $org='example.org';
            $uid = 'unknown_user';
                if ( isset($_SERVER['REMOTE_USER']) ) {
                    $uid=$_SERVER['REMOTE_USER'];   // Use username from e.g. basic auth to webserver
                }
            $userID='urn:collab:person:' . $org . ':' . $uid;
            $nameID = array(
                'Format' => SAML2_Const::NAMEID_UNSPECIFIED,
                'Value' => $userID
            );
            return array(
                // Set Subject NameID in the SAML AuthnRequest
                'saml:NameID' => $nameID,
            );
        },
    
    ),

Now you can proxy to the wrapped authsource from the IdP defined in `saml20-idp-hosted.php`. E.g.:

    // See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-hosted
    
    $metadata['__DYNAMIC:1__'] = array(
        /*
         * We use '__DEFAULT__' as the hostname so we won't have to
         * enter a hostname.
         */
        'host' => '__DEFAULT__',
    
        /* The private key and certificate used by this IdP. */
        'certificate' => 'example.org.crt',
        'privatekey' => 'example.org.pem',
    
        /*
         * The authentication source for this IdP. Must be one
         * from config/authsources.php.
         */
         
         // Proxy to 'example-sp' using 'wrapped-sp'
        'auth' => 'wrapped-sp',
    );
    
Add one (or more) IdPs to `saml20-idp-remote.php`. These are the remote IdPs that the hosted SP can use for authentication.

Add one (or more) SPs to `saml20-sp-remote.php`. These are the remote SPs that can use this SimpleSAMLphp proxy for authentication.