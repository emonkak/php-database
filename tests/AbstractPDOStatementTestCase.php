<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractPDOStatementTestCase extends TestCase
{
    protected $pdo;

    public function setUp(): void
    {
        $this->pdo = $this->preparePdo();
    }

    public function testBindValue(): void
    {
        $stmt = $this->pdo->prepare('SELECT ? AS `bool`, ? AS `int`, ? AS `null`, ? AS `str`');
        $this->assertTrue($stmt->bindValue(1, 1, \PDO::PARAM_BOOL));
        $this->assertTrue($stmt->bindValue(2, 123, \PDO::PARAM_INT));
        $this->assertTrue($stmt->bindValue(3, 0, \PDO::PARAM_NULL));
        $this->assertTrue($stmt->bindValue(4, 'foo', \PDO::PARAM_STR));
        $this->assertTrue($stmt->execute());
        $this->assertEquals([
            'bool' => 1,
            'int' => 123,
            'null' => null,
            'str' => 'foo',
        ], $stmt->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * @dataProvider providerFetchAll
     */
    public function testFetchAll($fetch_args, $sql, $input_parameters, $expected): void
    {
        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, $stmt->fetchAll(...$fetch_args));

        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue($stmt->setFetchMode(...$fetch_args));
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, $stmt->fetchAll());

        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue($stmt->setFetchMode(...$fetch_args));
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, iterator_to_array($stmt, false));
    }

    public static function providerFetchAll(): array
    {
        return [
            [[\PDO::FETCH_ASSOC], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], []],
            [[\PDO::FETCH_ASSOC], 'SELECT 1 AS foo, 2 AS bar', [], [['foo' => 1, 'bar' => 2]]],
            [[\PDO::FETCH_ASSOC], 'SELECT ? AS foo, ? AS bar', [123, 456], [['foo' => 123, 'bar' => 456]]],
            [[\PDO::FETCH_ASSOC], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], [['foo' => 1], ['foo' => 2], ['foo' => 3]]],
            [[\PDO::FETCH_CLASS, \stdClass::class], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], []],
            [[\PDO::FETCH_CLASS, \stdClass::class], 'SELECT 1 AS foo, 2 AS bar', [], [(object) ['foo' => 1, 'bar' => 2]]],
            [[\PDO::FETCH_CLASS, \stdClass::class], 'SELECT ? AS foo, ? AS bar', [123, 456], [(object) ['foo' => 123, 'bar' => 456]]],
            [[\PDO::FETCH_CLASS, \stdClass::class], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], [(object) ['foo' => 1], (object) ['foo' => 2], (object) ['foo' => 3]]],
            [[\PDO::FETCH_CLASS, Entity::class, []], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], []],
            [[\PDO::FETCH_CLASS, Entity::class, []], 'SELECT 1 AS foo, 2 AS bar', [], [Entity::fromArray(['foo' => 1, 'bar' => 2])]],
            [[\PDO::FETCH_CLASS, Entity::class, []], 'SELECT ? AS foo, ? AS bar', [123, 456], [Entity::fromArray(['foo' => 123, 'bar' => 456])]],
            [[\PDO::FETCH_CLASS, Entity::class, []], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], [Entity::fromArray(['foo' => 1]), Entity::fromArray(['foo' => 2]), Entity::fromArray(['foo' => 3])]],
            [[\PDO::FETCH_NUM], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], []],
            [[\PDO::FETCH_NUM], 'SELECT 1 AS foo, 2 AS bar', [], [[1, 2]]],
            [[\PDO::FETCH_NUM], 'SELECT ? AS foo, ? AS bar', [123, 456], [[123, 456]]],
            [[\PDO::FETCH_NUM], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], [[1], [2], [3]]],
            [[\PDO::FETCH_BOTH], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], []],
            [[\PDO::FETCH_BOTH], 'SELECT 1 AS foo, 2 AS bar', [], [['foo' => 1, 0 => 1, 'bar' => 2, 1 => 2]]],
            [[\PDO::FETCH_BOTH], 'SELECT ? AS foo, ? AS bar', [123, 456], [['foo' => 123, 0 => 123, 'bar' => 456, 1 => 456]]],
            [[\PDO::FETCH_BOTH], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], [['foo' => 1, 0 => 1], ['foo' => 2, 0 => 2], ['foo' => 3, 0 => 3]]],
            [[\PDO::FETCH_COLUMN, 0], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], []],
            [[\PDO::FETCH_COLUMN, 0], 'SELECT 1 AS foo, 2 AS bar', [], [1]],
            [[\PDO::FETCH_COLUMN, 1], 'SELECT 1 AS foo, 2 AS bar', [], [2]],
            [[\PDO::FETCH_COLUMN, 0], 'SELECT ? AS foo, ? AS bar', [123, 456], [123]],
            [[\PDO::FETCH_COLUMN, 1], 'SELECT ? AS foo, ? AS bar', [123, 456], [456]],
            [[\PDO::FETCH_COLUMN, 0], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], [1, 2, 3]],
        ];
    }

    /**
     * @dataProvider providerFetchAllThrowsRuntimeException
     */
    public function testFetchAllThrowsException($fetch_args, $sql, $input_parameters): void
    {
        $this->expectException(\ValueError::class);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($input_parameters);
        $stmt->fetchAll(...$fetch_args);
    }

    public static function providerFetchAllThrowsRuntimeException(): array
    {
        return [
            [[\PDO::FETCH_COLUMN, 999], 'SELECT 1 AS foo, 2 AS bar', [], [2]],
        ];
    }

    public function testFetchAllWithoutExecute(): void
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $this->assertEquals([], $stmt->fetchAll());
    }

    public function testFetchAllWithInvalidFetchMode()
    {
        $this->expectException(\ValueError::class);
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->execute();
        $stmt->fetchAll(-1);
    }

    /**
     * @dataProvider providerFetch
     */
    public function testFetch($fetch_args, $sql, $input_parameters, $expected): void
    {
        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, $stmt->fetch(...$fetch_args));
    }

    public static function providerFetch(): array
    {
        return [
            [[\PDO::FETCH_ASSOC], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], false],
            [[\PDO::FETCH_ASSOC], 'SELECT 1 AS foo, 2 AS bar', [], ['foo' => 1, 'bar' => 2]],
            [[\PDO::FETCH_ASSOC], 'SELECT ? AS foo, ? AS bar', [123, 456], ['foo' => 123, 'bar' => 456]],
            [[\PDO::FETCH_ASSOC], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], ['foo' => 1], ['foo' => 2], ['foo' => 3]],
            [[\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_NEXT], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], ['foo' => 1], ['foo' => 2], ['foo' => 3]],
            [[\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_NEXT, 0], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], ['foo' => 1], ['foo' => 2], ['foo' => 3]],
            [[\PDO::FETCH_NUM], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], false],
            [[\PDO::FETCH_NUM], 'SELECT 1 AS foo, 2 AS bar', [], [1, 2]],
            [[\PDO::FETCH_NUM], 'SELECT ? AS foo, ? AS bar', [123, 456], [123, 456]],
            [[\PDO::FETCH_NUM], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], [1], [2], [3]],
            [[\PDO::FETCH_BOTH], 'SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], false],
            [[\PDO::FETCH_BOTH], 'SELECT 1 AS foo, 2 AS bar', [], ['foo' => 1, 0 => 1, 'bar' => 2, 1 => 2]],
            [[\PDO::FETCH_BOTH], 'SELECT ? AS foo, ? AS bar', [123, 456], ['foo' => 123, 0 => 123, 'bar' => 456, 1 => 456]],
            [[\PDO::FETCH_BOTH], 'SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], ['foo' => 1, 0 => 1], ['foo' => 2, 0 => 2], ['foo' => 3, 0 => 3]],
        ];
    }

    public function testFetchWithoutExecute(): void
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $this->assertFalse($stmt->fetch());
    }

    public function testFetchWithInvalidFetchMode(): void
    {
        $this->expectException(\ValueError::class);
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->execute();
        $stmt->fetch(-1);
    }

    /**
     * @dataProvider providerFetchColumn
     */
    public function testFetchColumn($sql, $input_parameters, $column_number, $expected): void
    {
        $stmt = $this->pdo->prepare($sql);
        $this->assertTrue($stmt->execute($input_parameters));
        $this->assertEquals($expected, $stmt->fetchColumn($column_number));
    }

    public static function providerFetchColumn(): array
    {
        return [
            ['SELECT * FROM (SELECT 1) AS tmp WHERE 0', [], 0, false],
            ['SELECT 1 AS foo, 2 AS bar', [], 0, 1],
            ['SELECT 1 AS foo, 2 AS bar', [], 1, 2],
            ['SELECT ? AS foo, ? AS bar', [123, 456], 0, 123],
            ['SELECT ? AS foo, ? AS bar', [123, 456], 1, 456],
            ['SELECT 1 AS foo UNION ALL SELECT 2 AS foo UNION ALL SELECT 3 AS foo', [], 0, 1],
        ];
    }

    public function testFetchColumnWithoutExecute(): void
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $this->assertFalse($stmt->fetchColumn());
    }

    public function testErrorCode(): void
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->execute();
        $this->assertEquals(0, $stmt->errorCode());
    }

    public function testErrorInfo(): void
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->execute();
        $error = $stmt->errorInfo();
        $this->assertIsArray($error);
        $this->assertCount(3, $error);
    }

    public function testRowCount(): void
    {
        $stmt = $this->pdo->prepare('SELECT * FROM (SELECT 1) AS tmp WHERE 0');
        $stmt->execute();
        $this->assertSame(0, $stmt->rowCount());
    }

    abstract protected function preparePdo(): PDOInterface;
}
