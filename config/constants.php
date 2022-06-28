<?php

/*
    SHORTLINK_CODE_CHARS => You must not change these once you start creating ShortLinks
    FORWADER_PREVIEW => enabling this feature prevents automatic redirection to malicious websites; toggling this feature requires clearing of cache

    kw_blacklist => Any ShortLink that matches with the keys of this array will be blocked. - ACCEPTS SHELL WILDCARD PATTERNS

    dom_blacklist => Any domain of a URL that matches with the keys of this array will be blocked. - ACCEPTS SHELL WILDCARD PATTERNS
*/

return [
    'options' => [
        'SHORTLINK_CODE_CHARS' => '123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ',
        'FORWADER_PREVIEW' => true,
        'kw_blacklist' => array (
            '*--*',
            '*fuck*',
            '*shit*',
            '*asshole*',
            '*cunt*',
            '*nigger*',
            '*bitch*',
            '*faggot*',
            '*dick*',
            '*dumbass*',
            '*nigga*',
            '*pussy*',
            '*slut*',
            '*whore*',
            '*axwound*',
            '*dildo*',
            '*vagina*',
            '*penis*',
            '*clitoris*',
            '*creampie*',
            '*cum*'
        ),
        'dom_blacklist' => array (
            '*porn*',
            '*adult*',
            '*sex*',
            '*xxx*',
            '*brazzers*',
            '*bdsm*',
            '*fuck*',
            '*bigtit*',
            '*bitch*',
            '*homo*',
            '*horny*',
            '*virgin*',
        ),
    ]
];

?>