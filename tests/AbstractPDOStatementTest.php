<?php

namespace Emonkak\Database\Tests;

abstract class AbstractPDOStatementTest extends \PHPUnit_Framework_TestCase
{
    protected $pdo;

    public function setUp()
    {
        $this->pdo = $this->preparePdo();
    }

    public function testBindValue()
    {
        $stmt = $this->pdo->prepare('SELECT ? AS `bool`, ? AS `int`, ? AS `null`, ? AS `str`');
        $this->assertTrue($stmt->bindValue(1, 1, \PDO::PARAM_BOOL));
        $this->assertTrue($stmt->bindValue(2, 123, \PDO::PARAM_INT));
        $this->assertTrue($stmt->bindValue(3, 0, \PDO::PARAM_NULL));
        $this->assertTrue($stmt->bindValue(4, 'foo', \PDO::PARAM_STR));
        $this->assertTrue($stmt->execute());
        $this->assertEquals(array(
            'bool' => 1,
            'int' => 123,
            'null' => null,
            'str' => 'foo',
        ), $stmt->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * @dataProvider providerFetchAll
     */
    public function testFetchAll($fetch_args, $sql, $input_parameters, $expected)
    {
        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, call_user_func_array(array($stmt, 'fetchAll'), $fetch_args));

        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue(call_user_func_array(array($stmt, 'setFetchMode'), $fetch_args));
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, $stmt->fetchAll());

        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue(call_user_func_array(array($stmt, 'setFetchMode'), $fetch_args));
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, iterator_to_array($stmt, false));
    }

    public function providerFetchAll()
    {
        return array(
            array(array(\PDO::FETCH_ASSOC), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), array()),
            array(array(\PDO::FETCH_ASSOC), 'SELECT 1 AS foo, 2 AS bar', array(), array(array('foo' => 1, 'bar' => 2))),
            array(array(\PDO::FETCH_ASSOC), 'SELECT ? AS foo, ? AS bar', array(123, 456), array(array('foo' => 123, 'bar' => 456))),
            array(array(\PDO::FETCH_ASSOC), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array(array('foo' => 1), array('foo' => 2), array('foo' => 3))),
            array(array(\PDO::FETCH_CLASS, 'stdClass'), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), array()),
            array(array(\PDO::FETCH_CLASS, 'stdClass'), 'SELECT 1 AS foo, 2 AS bar', array(), array((object) array('foo' => 1, 'bar' => 2))),
            array(array(\PDO::FETCH_CLASS, 'stdClass'), 'SELECT ? AS foo, ? AS bar', array(123, 456), array((object) array('foo' => 123, 'bar' => 456))),
            array(array(\PDO::FETCH_CLASS, 'stdClass'), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array((object) array('foo' => 1), (object) array('foo' => 2), (object) array('foo' => 3))),
            array(array(\PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array()), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), array()),
            array(array(\PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array()), 'SELECT 1 AS foo, 2 AS bar', array(), array(Entity::fromArray(array('foo' => 1, 'bar' => 2)))),
            array(array(\PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array()), 'SELECT ? AS foo, ? AS bar', array(123, 456), array(Entity::fromArray(array('foo' => 123, 'bar' => 456)))),
            array(array(\PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array()), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array(Entity::fromArray(array('foo' => 1)), Entity::fromArray(array('foo' => 2)), Entity::fromArray(array('foo' => 3)))),
            array(array(\PDO::FETCH_NUM), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), array()),
            array(array(\PDO::FETCH_NUM), 'SELECT 1 AS foo, 2 AS bar', array(), array(array(1, 2))),
            array(array(\PDO::FETCH_NUM), 'SELECT ? AS foo, ? AS bar', array(123, 456), array(array(123, 456))),
            array(array(\PDO::FETCH_NUM), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array(array(1), array(2), array(3))),
            array(array(\PDO::FETCH_BOTH), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), array()),
            array(array(\PDO::FETCH_BOTH), 'SELECT 1 AS foo, 2 AS bar', array(), array(array('foo' => 1, 0 => 1, 'bar' => 2, 1 => 2))),
            array(array(\PDO::FETCH_BOTH), 'SELECT ? AS foo, ? AS bar', array(123, 456), array(array('foo' => 123, 0 => 123, 'bar' => 456, 1 => 456))),
            array(array(\PDO::FETCH_BOTH), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array(array('foo' => 1, 0 => 1), array('foo' => 2, 0 => 2), array('foo' => 3, 0 => 3))),
            array(array(\PDO::FETCH_COLUMN, 0), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), array()),
            array(array(\PDO::FETCH_COLUMN, 0), 'SELECT 1 AS foo, 2 AS bar', array(), array(1)),
            array(array(\PDO::FETCH_COLUMN, 1), 'SELECT 1 AS foo, 2 AS bar', array(), array(2)),
            array(array(\PDO::FETCH_COLUMN, 0), 'SELECT ? AS foo, ? AS bar', array(123, 456), array(123)),
            array(array(\PDO::FETCH_COLUMN, 1), 'SELECT ? AS foo, ? AS bar', array(123, 456), array(456)),
            array(array(\PDO::FETCH_COLUMN, 0), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array(1, 2, 3)),
        );
    }

    /**
     * @dataProvider providerFetchAllThrowsRuntimeException
     *
     * @expectedException RuntimeException
     */
    public function testFetchAllThrowsException($fetch_args, $sql, $input_parameters)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($input_parameters);
        call_user_func_array(array($stmt, 'fetchAll'), $fetch_args);
    }

    public function providerFetchAllThrowsRuntimeException()
    {
        return array(
            array(array(\PDO::FETCH_COLUMN, 999), 'SELECT 1 AS foo, 2 AS bar', array(), array(2)),
        );
    }

    public function testFetchAllWithoutExecute()
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $this->assertEquals(array(), $stmt->fetchAll());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testFetchAllWithInvalidFetchMode()
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->execute();
        $stmt->fetchAll(-1);
    }

    /**
     * @dataProvider providerFetch
     */
    public function testFetch($fetch_args, $sql, $input_parameters, $expected)
    {
        if ($fetch_args[0] !== \PDO::FETCH_CLASS) {
            $stmt = $this->pdo->prepare($sql);
            $this->assertTrue($stmt->execute($input_parameters));
            $this->assertEquals($expected, call_user_func_array(array($stmt, 'fetch'), $fetch_args));
        }

        $stmt = $this->pdo->prepare($sql);
        call_user_func_array(array($stmt, 'setFetchMode'), $fetch_args);
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, $stmt->fetch());
    }

    public function providerFetch()
    {
        return array(
            array(array(\PDO::FETCH_ASSOC), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), false),
            array(array(\PDO::FETCH_ASSOC), 'SELECT 1 AS foo, 2 AS bar', array(), array('foo' => 1, 'bar' => 2)),
            array(array(\PDO::FETCH_ASSOC), 'SELECT ? AS foo, ? AS bar', array(123, 456), array('foo' => 123, 'bar' => 456)),
            array(array(\PDO::FETCH_ASSOC), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array('foo' => 1), array('foo' => 2), array('foo' => 3)),
            array(array(\PDO::FETCH_CLASS, 'stdClass'), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), false),
            array(array(\PDO::FETCH_CLASS, 'stdClass'), 'SELECT 1 AS foo, 2 AS bar', array(), (object) array('foo' => 1, 'bar' => 2)),
            array(array(\PDO::FETCH_CLASS, 'stdClass'), 'SELECT ? AS foo, ? AS bar', array(123, 456), (object) array('foo' => 123, 'bar' => 456)),
            array(array(\PDO::FETCH_CLASS, 'stdClass'), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), (object) array('foo' => 1), array('foo' => 2), array('foo' => 3)),
            array(array(\PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array()), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), false),
            array(array(\PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array()), 'SELECT 1 AS foo, 2 AS bar', array(), Entity::fromArray(array('foo' => 1, 'bar' => 2))),
            array(array(\PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array()), 'SELECT ? AS foo, ? AS bar', array(123, 456), Entity::fromArray(array('foo' => 123, 'bar' => 456))),
            array(array(\PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array()), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), Entity::fromArray(array('foo' => 1), array('foo' => 2), array('foo' => 3))),
            array(array(\PDO::FETCH_NUM), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), false),
            array(array(\PDO::FETCH_NUM), 'SELECT 1 AS foo, 2 AS bar', array(), array(1, 2)),
            array(array(\PDO::FETCH_NUM), 'SELECT ? AS foo, ? AS bar', array(123, 456), array(123, 456)),
            array(array(\PDO::FETCH_NUM), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array(1), array(2), array(3)),
            array(array(\PDO::FETCH_BOTH), 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), false),
            array(array(\PDO::FETCH_BOTH), 'SELECT 1 AS foo, 2 AS bar', array(), array('foo' => 1, 0 => 1, 'bar' => 2, 1 => 2)),
            array(array(\PDO::FETCH_BOTH), 'SELECT ? AS foo, ? AS bar', array(123, 456), array('foo' => 123, 0 => 123, 'bar' => 456, 1 => 456)),
            array(array(\PDO::FETCH_BOTH), 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), array('foo' => 1, 0 => 1), array('foo' => 2, 0 => 2), array('foo' => 3, 0 => 3)),
        );
    }

    public function testFetchWithoutExecute()
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $this->assertFalse($stmt->fetch());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testFetchWithInvalidFetchMode()
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->execute();
        $stmt->fetch(-1);
    }

    /**
     * @dataProvider providerFetchColumn
     */
    public function testFetchColumn($sql, $input_parameters, $column_number, $expected)
    {
        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, $stmt->fetchColumn($column_number));
    }

    public function providerFetchColumn()
    {
        return array(
            array('SELECT * FROM (SELECT 1) AS tmp WHERE 0', array(), 0, false),
            array('SELECT 1 AS foo, 2 AS bar', array(), 0, 1),
            array('SELECT 1 AS foo, 2 AS bar', array(), 1, 2),
            array('SELECT 1 AS foo, 2 AS bar', array(), 2, false),
            array('SELECT ? AS foo, ? AS bar', array(123, 456), 0, 123),
            array('SELECT ? AS foo, ? AS bar', array(123, 456), 1, 456),
            array('SELECT ? AS foo, ? AS bar', array(123, 456), 2, false),
            array('SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', array(), 0, 1),
        );
    }

    public function testFetchColumnWithoutExecute()
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $this->assertFalse($stmt->fetchColumn());
    }

    public function testErrorCode()
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->execute();
        $this->assertEquals(0, $stmt->errorCode());
    }

    public function testErrorInfo()
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->execute();
        $error = $stmt->errorInfo();
        $this->assertInternalType('array', $error);
        $this->assertCount(3, $error);
    }

    public function testRowCount()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM (SELECT 1) AS tmp WHERE 0');
        $stmt->execute();
        $this->assertSame(0, $stmt->rowCount());
    }

    abstract protected function preparePdo();
}
