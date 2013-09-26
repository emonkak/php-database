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
 * PDOStatement implementation of the PDOStatementInterface.
 */
class PDOStatement extends \PDOStatement implements PDOStatementInterface
{
    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($fetch_style, $fetch_argument = null, $ctor_args = null)
    {
        if ($ctor_args !== null) {
            return parent::setFetchMode($fetch_style, $fetch_argument, $ctor_args);
        }

        if ($fetch_argument !== null) {
            return parent::setFetchMode($fetch_style, $fetch_argument);
        }

        return parent::setFetchMode($fetch_style);
    }
}
