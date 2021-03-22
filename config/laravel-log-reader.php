<?php

return [
    'api_route_path'   => 'admin/api/log-reader',
    'view_route_path'  => 'admin/logs',
    'admin_panel_path' => 'admin',
    'middleware'       => ['web', 'auth', 'admin']
];
