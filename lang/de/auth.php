<?php

return [
    'failed' => 'Anmeldung nicht möglich. Bitte versuchen Sie es später erneut.',
    'token_not_found' => 'In den Anforderungsheadern für die Autorisierung ist kein JWT-Token angegeben.',
    'token_invalid' => 'Token ist ungültig. Entweder ist das Tokenformat ungültig oder es ist zu lange abgelaufen, um ein neues JWT-Token zu generieren.',
    'token_expired' => 'Token ist abgelaufen. Verwenden Sie die Refresh-API, um ein neues Token zu generieren, oder melden Sie sich erneut an.',
    'token_failed_locate_user' => 'Benutzer mit dem angegebenen JWT-Token konnte nicht gefunden werden. Bitte versuchen Sie es erneut mit einem anderen JWT-Token.'
];
