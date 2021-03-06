<?php
namespace Ophportunidades\DataAccess;

use Ophportunidades\DataAccess\Entity\Position;
use Ophportunidades\DataAccess\DataAccess;

class PDOAccessTest extends AbstractDataAccessTest
{

    public function assertPreConditions()
    {
        $this->assertTrue(
                class_exists($class = 'Ophportunidades\DataAccess\PDODataAccess'),
                'Class not found: '.$class
        );
    }

    public function testInsertPosition()
    {
        $position2Insert = new Position();
        $position2Insert->setTitle('The job');
        $position2Insert->setDescription('job description');
        $position2Insert->setPlace('Rua dos bobos, 0');

        $dataAccess = new PDODataAccess($this->pdo);
        $id = $dataAccess->insert($position2Insert);

        $this->assertEquals(1, $id);

        $insertedPosition = $dataAccess->getById($id);

        $this->assertInstanceOf('Ophportunidades\DataAccess\Entity\Position', $insertedPosition);
        $this->assertEquals($position2Insert->getTitle(), $insertedPosition->getTitle());
        $this->assertEquals($position2Insert->getDescription(), $insertedPosition->getDescription());
        $this->assertEquals($position2Insert->getPlace(), $insertedPosition->getPlace());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExpcetionMessage Fail to insert some data
     */
    public function testInsertWithNullPositionShouldThrownAnException()
    {
        $position2Insert = new Position();

        $dataAccess = new PDODataAccess($this->pdo);
        $id = $insertedPosition = $dataAccess->insert($position2Insert);

        $this->assertNotEquals(1, $id);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetByIdWithAnInvalidArgument()
    {
        $dataAccess = new PDODataAccess($this->pdo);
        $dataAccess->getById(null);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Fail to retrieve the position
     */
    public function testGetByIdWithAnInvalidID()
    {
        $dataAccess = new PDODataAccess($this->pdo);
        $dataAccess->getById(-1);
    }

    public function testGetAll()
    {
        $dataAccess = new PDODataAccess($this->pdo);
        $position2Insert = new Position();

        for($i = 0; $i < 10; ++$i) {
            $position2Insert->setTitle('The job ' . $i);
            $position2Insert->setDescription('job description ' . $i);
            $position2Insert->setPlace('Rua dos bobos, ' . $i);

            $this->assertEquals($i + 1, $dataAccess->insert($position2Insert));
        }

        $this->assertCount(10, $dataAccess->getAll());
    }
}