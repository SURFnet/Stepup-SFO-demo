<?php
/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote 
 */

// *** SFO Pilot ***
// SAML 2.0 IdP metadata for the SFO endpoint of the SURFconext Strong Authentication Pilot environment
// Downloaded from: https://gateway.pilot.stepup.surfconext.nl/second-factor-only/metadata

$metadata['https://gateway.pilot.stepup.surfconext.nl/second-factor-only/metadata'] = array (
    'entityid' => 'https://gateway.pilot.stepup.surfconext.nl/second-factor-only/metadata',
    'metadata-set' => 'saml20-idp-remote',
    'redirect.sign' => TRUE,    // AuthnRequests to this IdP must be signed
    'name' => array(
        'en' => 'SURFconext Strong Authentication Pilot environment (SFO)',
    ),
    'SingleSignOnService' =>
        array (
            0 =>
                array (
                    'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                    'Location' => 'https://gateway.pilot.stepup.surfconext.nl/second-factor-only/single-sign-on',
                ),
        ),
    'keys' =>
        array (
            0 =>
                array (
                    'encryption' => false,
                    'signing' => true,
                    'type' => 'X509Certificate',
                    'X509Certificate' => 'MIICwjCCAaoCCQDs1IDIiytYMTANBgkqhkiG9w0BAQUFADAjMSEwHwYDVQQDDBhTdGVwdXAgUGlsb3QgR2F0ZXdheSBJZFAwHhcNMTUwMjI3MTA0MTU5WhcNMjUwMjI0MTA0MTU5WjAjMSEwHwYDVQQDDBhTdGVwdXAgUGlsb3QgR2F0ZXdheSBJZFAwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDzlSzxPhG+B+o0ulUcR499NoMhaP4oX0+zpzi2vfY+Q1qw8x6b0eeUrIk99IpMrWN74twvuQ/eIecLZXIYhG94AYGB620OX7KMM8oCfdjc2I0lk8d+/rsxUqH0U4DDVBryrMcLjLGwe2CMncdSBuc2LCg15TveClC/QW/NJ6rvDiR1GoLouqx+CLBd+z2gwC+Od7YTUVY+22XrVzatzOZuz8Wdvja4VxHzv9Qyi6ta78ah345HWLeZsIh6pRF80qX0lpPRgwQSVXNpT8IxvoL28ViVVGkLU5SmdS3fbfLzlL5i3jHpWFcvRGPj5z8zmVZuDAka6P80WiKDVRg7gouXAgMBAAEwDQYJKoZIhvcNAQEFBQADggEBANlnKHackl7MfHi+0lxb/ERuMkRpIGej29RSWL0aFojNpRjN2ihnuIjp4PPk98xQCKbVeN+PWXNqrrschbUfC5ikcYP5hoU7WJrHAWvEwmMNy1/UzcKtSgNby8loLFRzi68R92ZTumgFEBFYow9HzgC3HvDeBpRw/qFLZjsYqAjezTeRtafx8NIaBtKabRr5hedwUpnzldFbPqLxR1o0B/tqcUIqJOjdpEFIYus7VcBI6N6T1TKB4DyfqjbgzxhS5zrE1jFeKaamRWCqKUcEUfngoxQWlKd9LSBWRXjw0aM+P22WHdxxX/1rIneV5jVgOIlRgDO0Dpxn0qie4XnIqzo=',
                ),
        ),
);

// *** SFO Production ***
// SAML 2.0 IdP metadata for the SFO endpoint of the SURFconext Strong Authentication Production environment
// Downloaded from: https://sa-gw.surfconext.nl/second-factor-only/metadata

$metadata['https://sa-gw.surfconext.nl/second-factor-only/metadata'] = array (
    'entityid' => 'https://sa-gw.surfconext.nl/second-factor-only/metadata',
    'metadata-set' => 'saml20-idp-remote',
    'redirect.sign' => TRUE,    // AuthnRequests to this IdP must be signed
    'name' => array(
        'en' => 'SURFconext Strong Authentication Production environment (SFO)',
    ),

    'SingleSignOnService' =>
        array (
            0 =>
                array (
                    'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                    'Location' => 'https://sa-gw.surfconext.nl/second-factor-only/single-sign-on',
                ),
        ),
    'keys' =>
        array (
            0 =>
                array (
                    'encryption' => false,
                    'signing' => true,
                    'type' => 'X509Certificate',
                    'X509Certificate' => 'MIICsjCCAZoCCQDHN3+HzElEDDANBgkqhkiG9w0BAQUFADAbMRkwFwYDVQQDFBBnYXRld2F5X3NhbWxfaWRwMB4XDTE1MDcyMzEyMTUxOVoXDTIwMDcyMTEyMTUxOVowGzEZMBcGA1UEAxQQZ2F0ZXdheV9zYW1sX2lkcDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALK/JwHWd5JftXYKO9qcTQ4dfKEnl35oJj6PlEyR6gpikdpgm2OY/zy4e7vcXfBChedVF3OUI4rRDWCz4yXT2sldzjuIyONJfA86xva5lxDARqT/+gRBuZ2pyMTb0okvl1G9ZlAjPumVH14591rp6OGT5TJIkILQ/pKp1INdiBqpiR53Z5YvsXEUJ8PHHZyILO00HnBldq0d77lmATr6QamXpbY+CZ9pIw65t32fhFcUfRC68C81/P2crCn3v5GMyrQcM/tB/xdVf/haEZiqgI/bjcreBpQobnAhwEsve+uvbSLFN1Rsc7o0W/7Pn6EGBX1h9rjKjDgqssHuWkVuU4sCAwEAATANBgkqhkiG9w0BAQUFAAOCAQEAmlqfTvEfGDeqqqvuAMDG5IKDo6h21wwByywNbRhimfOvL6FqIgAgx+D3gxW1lO41PcqQQKYIVUEAuYv+tW8COLdHcFRh/UV9ei4iquMwBCkO/XOoMC9FsRBo3yPaQClRK8OYj1IXer4JXNuFHeLblzf+GLYFoqMWWwT2dnBLAePoEgANKUm2aasxyiJmnroNa+O5zTP9ExT3qHphCCG1gh3iHrQu9iSEJxY12zAQYtPomIs8Vk/GBfj+ucUiBEEqaMpCH+t6f0VOIoP1SNHgNAaeBLVuOpS0VlLnwZFJkNPVOQpFgRuoFsH3/9i53Fs3eQreb9wzq2VkjDhhlc5eyA==',
                ),
        ),
);


// *** SFO Test ***
// Not publicly accessible

$metadata['https://sa-gw.test.surfconext.nl/second-factor-only/metadata'] = array (
    'entityid' => 'https://sa-gw.test.surfconext.nl/second-factor-only/metadata',
    'metadata-set' => 'saml20-idp-remote',
    'redirect.sign' => TRUE,    // AuthnRequests to this IdP must be signed
    'name' => array(
        'en' => 'SURFconext Strong Authentication Test environment (SFO)',
    ),
    'SingleSignOnService' =>
        array (
            0 =>
                array (
                    'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                    'Location' => 'https://sa-gw.test.surfconext.nl/second-factor-only/single-sign-on',
                ),
        ),
    'keys' =>
        array (
            0 =>
                array (
                    'encryption' => false,
                    'signing' => true,
                    'type' => 'X509Certificate',
                    'X509Certificate' => 'MIICsjCCAZoCCQDWcSfwdCCZPzANBgkqhkiG9w0BAQsFADAbMRkwFwYDVQQDDBBnYXRld2F5X3NhbWxfaWRwMB4XDTE1MDYxMzE2MjUyOFoXDTIwMDYxMTE2MjUyOFowGzEZMBcGA1UEAwwQZ2F0ZXdheV9zYW1sX2lkcDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAJeWtHxcj4Qpf2VMn8lWvU2TRkoyrti15lS45pxBseqZh/OUmEcG+IMC1LJDFZr9KXpSBIlnFUfyBt5DgwVb65ke5BVXKKROOS8VVG517itf49kTMI3+KnX+tHvxdsi4cAM4Oo2iZ16OcJCrPduahxlferEz4nZmaS41bsfY4UzmuKJF8/xJZq27rq1B0ttZw9e/gocqJVGagbgQb3YGsCEIgy96csi1WCQOD7dQOk6GevHB9EdlLVAbYNFZLU6yBT2Frne6coScrRWnj3S9qscU45cIOOh+a3LoA8+WTEqqplFWavHbTiV3vC05PyDOk0DUhNvmAdn2Vi8Oxn3yUB0CAwEAATANBgkqhkiG9w0BAQsFAAOCAQEABJzYbNP7+X559QnI7WjXfLSC7TzdnRtI7M3CWBor06ZRuhmzLuuGAL/ZRQZb8v3rfNEzmufOZcjES/3cA8GKJubw0OLZbll1ebdDEUm9PL0fATCd3188fsIy14SJzfBAqlc5rOgZbl5OybpB5z07gSTe9H5ZAc2DJ4rnWipxyr8kBTU0k6eZBOL8V0eLT3Lzi3dYLxoa+31yWr62KUZyMhkeIOiNPhUNq4iZyZN33Fvvk512yy/8cw9EotbNFHyGti5fvQ+PDmY2dmE1A0beY9MIK9opXSMvQ+qUVlYMsMcQ13+MozEty18cK4qURAthu6s2JcBdG29l1FwfBeNtmQ==',
                ),
        ),
);

