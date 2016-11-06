<?php
/**
 * Copyright 2016 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

include_once '../src/bootstrap.inc';

// When using SFO, login consists of three steps:
// 1. Native authentication by the application
// 2. Establishing the identifier of the user for use with SFO authentication
// 3. The SFO authentication.

// ** 1. Native authentication **
// In this demo we use simpleSAMLphp to do the native authentication for us using "native-auth"
// authsource (see <root>/config/simplesaml/authsources.php for it's definition) that we did setup for that purpose.
// In your own app, this could be any method.
// After the authentication we have the identity of the user that we need for the next step - the SFO authentication

$nativeAuth = new SimpleSAML_Auth_Simple('native-auth');
if ( !$nativeAuth->isAuthenticated() ) {
    $nativeAuth->login( array (
        // Return to index page after first login. This means you have login a second time for SFO
        // To continue after finishing this one, comment the line below.
        'ReturnTo' => '/index.php'
        )
    );
}


// ** 2. Establishing the user identifier for use with SFO **
// Extract the identity information of the user from the native authentication
// The SAML IdP of the institution did send two attributes to SURFconext that together uniquely identify the
// user and that were recorded when the second authentication factor of the user was recorded. These are:
// - "urn:mace:dir:attribute-def:uid": the local user identifier
// - "urn:mace:terena.org:attribute-def:schacHomeOrganization": the identifier of the institution
// Your application needs to device a way to get hold of this information. For the demo we've stored
// the identifiers locally as attributes in "native-auth" authsource.

$userID = \SURFnet\SFOSSPSample\getUserID($nativeAuth);
if (false === $userID) {
    throw new Exception('Could not get the userid from the native authentication session');
}

// Build user identifier for use in SFO AuthnRequest
$nameID = array(
    'Format' => SAML2_Const::NAMEID_UNSPECIFIED,
    'Value' => $userID
);


// *** 3. Authenticate the user with SFO ***

// What AuthnContextClassRef to use with what SFO IdP
// The IdPs are defined in /config/simplesaml/metadata/saml20-idp-remote.php
$sfoLoaMap=array(
    'https://gateway.pilot.stepup.surfconext.nl/second-factor-only/metadata' => 'http://pilot.surfconext.nl/assurance/sfo-level2',
    'https://sa-gw.surfconext.nl/second-factor-only/metadata' => 'http://surfconext.nl/assurance/sfo-level2',
    'https://sa-gw.test.surfconext.nl/second-factor-only/metadata' => 'http://test.surfconext.nl/assurance/sfo-level2'
);
// The SFO Idp to use
$sfoIdpEntityID='https://gateway.pilot.stepup.surfconext.nl/second-factor-only/metadata';  // Pilot
//$sfoIdpEntityID='https://sa-gw.test.surfconext.nl/second-factor-only/metadata';  // Test (SURFnet internal)

// Start SFO authentication
$sfoAuth = new SimpleSAML_Auth_Simple('sfo-sp');
if ( !$sfoAuth->isAuthenticated() ) {
    $sfoAuth->login(
        array(
            'saml:NameID' => $nameID, // Set Subject in AuthnRequest
            'ReturnTo' => '/index.php',
            'saml:idp' => $sfoIdpEntityID, // Set IdP to use to skip discovery
            'saml:AuthnContextClassRef' => $sfoLoaMap[$sfoIdpEntityID],
        )
    );
}

\SimpleSAML\Utils\HTTP::redirectTrustedURL('index.php'); // Return to index page