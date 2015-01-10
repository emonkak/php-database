<?php

namespace PDOInterface\Tests;

use PDOInterface\PDO;

class PDOTest extends AbstractPDOTest
{
    public function providePdo()
    {
        return new PDO('sqlite::memory:');
    }
}
