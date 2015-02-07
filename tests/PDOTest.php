<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDO;

class PDOTest extends AbstractPDOTest
{
    public function providePdo()
    {
        return new PDO('sqlite::memory:');
    }
}
