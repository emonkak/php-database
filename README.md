# PDO Interface

[![Build Status](https://travis-ci.org/emonkak/php-pdo-interface.png)](https://travis-ci.org/emonkak/php-pdo-interface)
[![Coverage Status](https://coveralls.io/repos/emonkak/php-pdo-interface/badge.png)](https://coveralls.io/r/emonkak/php-pdo-interface)

The interface as a subset of PDO.

## PDOInterface\PDOInterface

`PDOInterface\PDO` implements this interface.

Please see below links for details of the method.

- [beginTransaction](http://www.php.net/manual/pdo.begintransaction.php)()
- [commit](http://www.php.net/manual/pdo.commit.php)()
- [errorCode](http://www.php.net/manual/pdo.errorcode.php)()
- [exec](http://www.php.net/manual/pdo.exec.php)($statement)
- [inTransaction](http://www.php.net/manual/pdo.intransaction.php)()
- [lastInsertId](http://www.php.net/manual/pdo.lastinsertid.php)($name = null)
- [prepare](http://www.php.net/manual/pdo.prepare.php)($statement)
- [query](http://www.php.net/manual/pdo.query.php)($statement)
- [quote](http://www.php.net/manual/pdo.quote.php)($string, $parameter_type = null)
- [rollback](http://www.php.net/manual/pdo.rollback.php)

## PDOInterface\PDOStatementInterface

`PDOInterface\PDOStatement` implements this interface.

Please see below links for details of the method.

- [bindValue](http://www.php.net/manual/pdostatement.bindvalue.php)($parameter, $value, $data_type = null)
- [closeCursor](http://www.php.net/manual/pdostatement.closecursor.php)()
- [columnCount](http://www.php.net/manual/pdostatement.columncount.php)()
- [errorCode](http://www.php.net/manual/pdostatement.errorcode.php)()
- [errorInfo](http://www.php.net/manual/pdostatement.errorinfo.php)()
- [execute](http://www.php.net/manual/pdostatement.execute.php)($input_parameters = null)
- [fetch](http://www.php.net/manual/pdostatement.fetch.php)($fetch\_style = null, $cursor\_orientation = null, $cursor_offset = null)
- [fetchAll](http://www.php.net/manual/pdostatement.fetchall.php)($fetch\_style = null, $fetch\_argument = null, $ctor_args = null)
- [fetchColumn](http://www.php.net/manual/pdostatement.fetchcolumn.php)($column_number = 0)
- [nextRowset](http://www.php.net/manual/pdostatement.nextrowset.php)()
- [rowCount](http://www.php.net/manual/pdostatement.rowcount.php)()
- [setFetchMode](http://www.php.net/manual/pdostatement.setfetchmode.php)($fetch_style, $fetch_argument = null, $ctor_args = null)
