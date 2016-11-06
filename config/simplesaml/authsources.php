<?php

$config = array(

    // This is the authentication source which handles admin authentication to the SimpleSAMLphp interface
    // The passowrd is set in /config/simplesaml/config.php
    'admin' => array(
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ),

    // Define "sfo-sp" authsource that uses the "saml:SP" module
    // This is the SFO SP that is used to authenticate against the SURFconext SFO IdP endpoint
    'sfo-sp' => array(
        'saml:SP',

        // The entity ID of this SP.
        // Can be NULL/unset, in which case an entity ID is generated based on the metadata URL.
        // E.g.: http://localhost:8080/simplesaml/module.php/saml/sp/metadata.php/sfo-sp
        //
        // For production it is recommended to explicitly define this value because the generated value
        // depends on the url that the client uses to access the site. Setting this value prevents issues
        // because of variations in url that are used to access the site.. E.g. "http://example.org",
        // "https://example.org", "http://www.example.org", "http://example.org:8080" etc
        'entityID' => null,

        // Sign the SAML AuthnRequest, required for SFO
        'sign.authnrequest' => TRUE,

        // Use SHA-256 signing algorithm, required for SFO
        'signature.algorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',

        // The X.509 Certificate and RSA private key that are used to sign the SAML Authn Requests
        // from this SP. Best practices:
        // - Use a self signed certificate, do not reuse the SSL/TLS server certificate of the webserver
        // - Use RSA key with a modulus of minimal 2048 bits
        // Do not reuse the private key of certificate in this example in production, but generate new ones.
        // This can be done using the openssl command:
        // openssl req -new -newkey rsa:2048 -days 3650 -nodes -x509 -keyout saml_signing.key -out saml_signing.crt
        'privatekey' => 'saml_signing.key',
        'certificate' => 'saml_signing.crt',

        // Bindings to advertise for receiving the SAML Response
        'acs.Bindings' => array(
            'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST'
        ),


        // OrganizationName and OrganiztationDisplayName for publication in this SPs metadata
        'OrganizationName' => array(
            'en' => 'SFO SSP Demo',
        ),
        // OrganizationURL for publication in this SPs metadata
        'OrganizationURL' => array(
            'en' => 'https://todo.example.org',
        ),
        // Contact information for publication in this SPs metadata, this is in addition to the technical contact that
        // is configured in /config/simplesaml/config.php
        'contacts' => array(
            array(
                'contactType'       => 'support',
                'emailAddress'      => 'support@example.org',
                'telephoneNumber'   => '+31(0)12345678',
                'company'           => 'Example Inc.',
            ),
            array(
                'contactType'       => 'technical',
                'emailAddress'      => 'technical@example.org',
                'telephoneNumber'   => '+31(0)12345678',
                'company'           => 'Example Inc.',
            ),
            array(
                'contactType'       => 'administrative',
                'emailAddress'      => 'admin@example.org',
                'givenName'         => 'John',
                'surName'           => 'Doe',
                'telephoneNumber'   => '+31(0)12345678',
                'company'           => 'Example Inc.',
            )
        ),

        // The entity ID of the IdP this should SP should contact.
        // Can be NULL/unset, in which case the user will be shown a list of available IdPs.
        // This is one of the IdPs defined in /config/simplesaml/metadata/saml20-idp-remote.php
        // The parameter can also be set during the login() of requireAuth call using the
        // "saml:idp" parameter
        'idp' => null,

        // The URL to the discovery service.
        // Can be NULL/unset, in which case a builtin discovery service will be used.
        'discoURL' => null,
    ),


    // Define "native-auth" authsource that uses the "exampleauth:UserPass" modules
    // This is the authsource that is used for the native login
    'native-auth' => array(
        'exampleauth:UserPass',

        // Give the user an option to save their username for future login attempts
        // And when enabled, what should the default be, to save the username or not
        'remember.username.enabled' => TRUE,
        'remember.username.checked' => FALSE,

        // format username:password
        'student:studentpass' => array(
            UID_ATTRIBUTE_NAME => array('student'),
            ORG_ATTRIBUTE_NAME => array('example.org'),
        ),
        'employee:employeepass' => array(
            UID_ATTRIBUTE_NAME => array('employee'),
            ORG_ATTRIBUTE_NAME => array('example.org'),
        ),
    ),

    // Many more authentication modules are available.

);
