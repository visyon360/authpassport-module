<?php

return [
    'name' => 'AuthPassport',

    /**
     * Get the login username to be used by the controller.
     */
    'username' => env('MODULE_PASSPORT_USERNAME', 'email')
];
