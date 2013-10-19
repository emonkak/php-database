<?php
/**
 * Copyright (c) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PDOInterface;

/**
 * PDOInterface implementation class.
 */
class PDO extends \PDO implements PDOInterface
{
    /**
     * @param string      $dsn
     * @param string|null $user
     * @param string|null $password
     * @param array|null  $options
     */
    public function __construct($dsn, $user = null, $password = null, array $options = null)
    {
        parent::__construct($dsn, $user, $password, $options);

        $this->setAttribute(
            PDO::ATTR_STATEMENT_CLASS,
            array(__NAMESPACE__ . '\\PDOStatement', array())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement)
    {
        return parent::query($statement);
    }
}
