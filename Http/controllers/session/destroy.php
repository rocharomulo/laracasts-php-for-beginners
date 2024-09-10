<?php

use Core\Authenticator;

Authenticator::logout();

header('location: /');
exit;
