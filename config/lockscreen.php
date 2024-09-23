<?php

return [
    /**
     * Time to live
     * Seconds to wait after last activity before locking the account.
     */
    'ttl' => 30 * 60, // 30 menit

    /**
     * Append middleware to the web middleware group.
     * If false, you should add the middleware 'lockscreen' manually to the routes.
     */
    'append_middleware' => true,

    /**
     * Route name
     * The name of the route to redirect to when the account is locked.
     */
    'route' => 'locked',
];
