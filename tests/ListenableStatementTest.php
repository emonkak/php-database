<?php

namespace Emonkak\Database;

class ListenableStatementTest extends \PHPUnit_Framework_TestCase
{
    public function testDelegate()
    {
        $queryString = 'SELECT 123 AS foo';

        $pdo = $this->getMock('Emonkak\Database\PDOInterface');
        $listener = $this->getMock('Emonkak\Database\PDOListenerInterface');
        $delegate = $this->getMock('Emonkak\Database\PDOStatementInterface');
        $delegate
            ->expects($this->once())
            ->method('errorCode')
            ->willReturn(123);
        $delegate
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(array('HY000', 1, 'error'));
        $delegate
            ->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_CLASS, 'stdClass', array())
            ->willReturn((object) array('foo' => 123));
        $delegate
            ->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_CLASS, 'stdClass', array())
            ->willReturn(array((object) array('foo' => 123)));
        $delegate
            ->expects($this->once())
            ->method('fetchColumn')
            ->with(0)
            ->willReturn(123);
        $delegate
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);
        $delegate
            ->expects($this->once())
            ->method('setFetchMode')
            ->with(PDO::FETCH_CLASS, 'stdClass', array())
            ->willReturn(true);

        $stmt = new ListenableStatement($pdo, array($listener), $delegate, $queryString);

        $this->assertSame($delegate, $stmt->getIterator());
        $this->assertSame(123, $stmt->errorCode());
        $this->assertEquals(array('HY000', 1, 'error'), $stmt->errorInfo());
        $this->assertEquals((object) array('foo' => 123), $stmt->fetch(PDO::FETCH_CLASS, 'stdClass', array()));
        $this->assertEquals(array((object) array('foo' => 123)), $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass', array()));
        $this->assertSame(123, $stmt->fetchColumn(0));
        $this->assertSame(1, $stmt->rowCount());
        $this->assertTrue($stmt->setFetchMode(PDO::FETCH_CLASS, 'stdClass', array()));
    }

    public function testExecute()
    {
        $queryString = 'SELECT ? AS foo, ? AS bar';

        $pdo = $this->getMock('Emonkak\Database\PDOInterface');
        $listener = $this->getMock('Emonkak\Database\PDOListenerInterface');
        $listener
            ->expects($this->once())
            ->method('onQuery')
            ->with(
                $this->identicalTo($pdo),
                $queryString,
                array(123, 456),
                $this->greaterThan(0)
            );
        $delegate = $this->getMock('Emonkak\Database\PDOStatementInterface');
        $delegate
            ->expects($this->once())
            ->method('bindValue')
            ->with(1, 123, \PDO::PARAM_INT)
            ->willReturn(true);
        $delegate
            ->expects($this->once())
            ->method('execute')
            ->with(array(456))
            ->willReturn(true);

        $stmt = new ListenableStatement($pdo, array($listener), $delegate, $queryString);

        $this->assertTrue($stmt->bindValue(1, 123, \PDO::PARAM_INT));
        $this->assertTrue($stmt->execute(array(456)));
    }
}
