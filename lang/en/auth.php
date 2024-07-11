<?php

return [
    'failed' => 'Unable to login, please try again later.',
    'token_not_found' => 'Jwt token is not provided on the request headers for Authorization.',
    'token_invalid' => 'Token is invalid. Either the token format is invalid, or is expired too long to generate a new Jwt Token.',
    'token_expired' => 'Token is expired. Use Refresh API to generate new token, or login again.',
    'token_failed_locate_user' => 'Unable to locate user with the provided Jwt Token. Please try again with a different Jwt Token.'
];
