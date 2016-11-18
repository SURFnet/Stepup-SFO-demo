<?php
/**
 * SAML 2.0 IdP configuration for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-hosted
 */

// Definition of the hosted IdP for the SimpleSAMLphp proxy

// TODO: Update EntityID to real value
$metadata['http://localhost:8080/simplesaml/saml2/idp/metadata.php'] = array(
	/*
	 * The hostname of the server (VHOST) that will use this SAML entity.
	 *
	 * Can be '__DEFAULT__', to use this entry by default.
	 */
	'host' => '__DEFAULT__',

	// X.509 key and certificate. Relative to the cert directory.
	'privatekey' => 'saml_signing.key',
	'certificate' => 'saml_signing.crt',

	/*
	 * Authentication source to use. Must be one that is configured in
	 * 'config/authsources.php'.
	 */
	'auth' => 'sfo-sp',

	'signature.algorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',

	'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.0:nameid-format:unspecified',

	'validate.authnrequest' => false,	// Setting from saml20-sp-remote.php takes precedence

);
