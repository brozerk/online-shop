<?php

namespace App;

use PDO;

interface ConnectionAwareInterface
{
    public function setConnection(PDO $connection);
}
