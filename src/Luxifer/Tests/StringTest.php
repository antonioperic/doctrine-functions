<?php

namespace Luxifer\Tests;

class StringTest extends DQLFunctionTest
{
    const FAKE_ENTITY = 'Luxifer\Tests\Fixtures\Entity\Fake';

    /**
     * Test CONCAT_WS function
     *
     * @dataProvider partsProvider
     */
    public function testConcatWs($dql, $sql)
    {
        $query = $this->em->createQuery(
            sprintf("SELECT CONCAT_WS(%s) FROM %s as e", $dql, self::FAKE_ENTITY)
        );

        $this->assertEquals(
            $query->getSQL(),
            sprintf("SELECT CONCAT_WS(%s) AS sclr0 FROM some_fake s0_", $sql)
        );
    }

    /**
     * Expect at least 3 arguments
     *
     * @expectedException \Doctrine\ORM\Query\QueryException
     */
    public function testConcatWsFail()
    {
        $query = $this->em->createQuery(
            sprintf("SELECT CONCAT_WS(' ', '1') FROM %s as e", self::FAKE_ENTITY)
        );

        $query->getSQL();

        $this->fail('Should have failed');
    }

    /**
     * Data provider
     * @codeCoverageIgnore
     */
    public function partsProvider()
    {
        return [
            ["'', '1', '2'", "'', '1', '2'"],
            ["' ', '1', '2', '3'", "' ', '1', '2', '3'"],
            ["', ', '1', '2', '3', '4'", "', ', '1', '2', '3', '4'"],
            ["' ', e.id, '2'", "' ', s0_.id, '2'"],
        ];
    }
}
