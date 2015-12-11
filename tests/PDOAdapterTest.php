<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOAdapter;

class PDOAdapterTest extends AbstractPDOTest
{
    protected function providePdo()
    {
        return new PDOAdapter(new \PDO('sqlite::memory:'));
    }
}
