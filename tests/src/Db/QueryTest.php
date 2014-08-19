<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Tests\Db;

use Bluz;
use Bluz\Db;
use Bluz\Db\Query;
use Bluz\Db\Query\Select;
use Bluz\Db\Query\Insert;
use Bluz\Db\Query\Update;
use Bluz\Db\Query\Delete;
use Bluz\Tests;

/**
 * Test class for Query Builder.
 * Generated by PHPUnit on 2013-06-17 at 13:52:01.
 * @todo Separate to 4 tests for every builder
 * @todo Write tests for different DB type
 */
class QueryTest extends Bluz\Tests\TestCase
{
    /**
     * setUp
     */
    public function setUp()
    {
        $this->getApp()->getDb()->setDefaultAdapter();
    }

    /**
     * Complex test of select builder
     */
    public function testSelect()
    {
        $builder = new Select();
        $builder = $builder
            ->select('u.*')
            ->addSelect('ua.*')
            ->from('users', 'u')
            ->leftJoin('u', 'users_actions', 'ua', 'ua.userId = u.id')
            ->where('u.id = ? OR u.id = ?', 4, 5)
            ->orWhere('u.id IN (?)', [4, 5])
            ->andWhere('u.status = ? OR u.status = ?', 'active', 'pending')
            ->orWhere('u.login LIKE (?)', 'A%')
            ->orderBy('u.id')
            ->addOrderBy('u.login')
            ->groupBy('u.id')
            ->addGroupBy('u.status')
            ->having('u.id')
            ->andHaving('u.status')
            ->limit(5)
        ;

        $check = 'SELECT u.*, ua.*'
            . ' FROM users u LEFT JOIN users_actions ua ON ua.userId = u.id'
            . ' WHERE (((u.id = "4" OR u.id = "5") OR (u.id IN ("4","5")))'
            . ' AND (u.status = "active" OR u.status = "pending")) OR (u.login LIKE ("A%"))'
            . ' GROUP BY u.id, u.status'
            . ' HAVING (u.id) AND (u.status)'
            . ' ORDER BY u.id ASC, u.login ASC'
            . ' LIMIT 5 OFFSET 0';

        $this->assertEquals($builder->getQuery(), $check);
    }

    /**
     * Complex test of select builder
     */
    public function testSelectWithInnerJoin()
    {
        $builder = new Select();
        $builder = $builder
            ->select('u.*', 'p.*')
            ->from('users', 'u')
            ->join('u', 'pages', 'p', 'p.userId = u.id')
        ;

        $check = 'SELECT u.*, p.*'
            . ' FROM users u INNER JOIN pages p ON p.userId = u.id';

        $this->assertEquals($builder->getQuery(), $check);
    }

    /**
     * Complex test of select builder
     */
    public function testSelectWithRightJoin()
    {
        $builder = new Select();
        $builder = $builder
            ->select('u.*', 'p.*')
            ->from('users', 'u')
            ->rightJoin('u', 'pages', 'p', 'p.userId = u.id')
        ;

        $check = 'SELECT u.*, p.*'
            . ' FROM users u RIGHT JOIN pages p ON p.userId = u.id';

        $this->assertEquals($builder->getQuery(), $check);
    }

    /**
     * Complex test of insert builder
     */
    public function testInsert()
    {
        $builder = new Insert();
        $builder = $builder
            ->insert('test')
            ->set('name', 'example')
            ->set('email', 'example@domain.com')
        ;
        $check = 'INSERT INTO `test` SET `name` = "example", `email` = "example@domain.com"';

        $this->assertEquals($builder->getQuery(), $check);
    }

    /**
     * Complex test of update builder
     */
    public function testUpdate()
    {
        $builder = new Update();
        $builder = $builder
            ->update('test')
            ->setArray(
                [
                    'status' => 'disable'
                ]
            )
            ->where('id = ?', 5)
        ;
        $check = 'UPDATE `test` SET `status` = "disable" WHERE id = "5"';

        $this->assertEquals($builder->getQuery(), $check);
    }

    /**
     * Complex test of delete builder
     */
    public function testDelete()
    {
        $builder = new Delete();
        $builder = $builder
            ->delete('test')
            ->where('id = ?', 5)
            ->limit(1)
        ;
        $check = 'DELETE FROM `test` WHERE id = "5" LIMIT 1';

        $this->assertEquals($builder->getQuery(), $check);
    }
}
