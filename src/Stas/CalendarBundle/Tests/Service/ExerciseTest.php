<?php
namespace Stas\CalendarBundle\Tests\Service;

use Stas\CalendarBundle\Service\Exercise as ExerciseService;
use Stas\CalendarBundle\Entity\User as UserEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Class ExerciseTest
 *
 * @group unit
 */
class ExerciseTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExerciseService */
    protected $service;

    /** @var \PHPUnit_Framework_MockObject_MockObject | UserEntity */
    protected $userMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject | EntityManager */
    public $entityManagerMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject | EntityRepository */
    protected $repositoryMock;

    /**
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->initEntityManagerMock(array('getRepository'));

        $this->userMock = $this->getMockBuilder('Stas\CalendarBundle\Entity\User')
            ->getMock();

        $this->repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->setMethods(array('findBy'))
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new ExerciseService($this->entityManagerMock);
    }

    /**
     * Create Doctrine Entity Manager Mock
     *
     * @param array $methods
     */
    public function initEntityManagerMock(array $methods)
    {
        /** @var \PHPUnit_Framework_TestCase $defiant */
        $defiant = $this;
        $this->entityManagerMock = $defiant->getMockBuilder('\Doctrine\ORM\EntityManager')->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->service);
        unset($this->userMock);
        unset($this->repositoryMock);
        unset($this->entityManagerMock);
    }

    /**
     * Test for Stas\CalendarBundle\Service\Exercise::getLastResults
     */
    public function testGetLastResults()
    {
        $mock1 = $this->getMockBuilder('Stas\CalendarBundle\Entity\Exercise')->getMock();
        $mock2 = $this->getMockBuilder('Stas\CalendarBundle\Entity\Exercise')->getMock();
        $mock3 = $this->getMockBuilder('Stas\CalendarBundle\Entity\Exercise')->getMock();
        $expectedResult = array(
            'today' => array($mock1),
            'one-week-ago' => array($mock2),
            'two-week-ago' => array($mock3),
        );

        $currentDate = '2014-09-10';
        $oneWeekAgo = '2014-09-03';
        $twoWeeksAgo = '2014-08-27';

        $this->entityManagerMock->expects($this->once())->method('getRepository')
            ->with('Stas\CalendarBundle\Entity\Exercise')
            ->will($this->returnValue($this->repositoryMock));

        $this->repositoryMock->expects($this->exactly(3))->method('findBy')
            ->will($this->returnValueMap(array(
                array(array('user' => $this->userMock, 'date' => $currentDate), null, null, null, array($mock1)),
                array(array('user' => $this->userMock, 'date' => $oneWeekAgo), null, null, null, array($mock2)),
                array(array('user' => $this->userMock, 'date' => $twoWeeksAgo), null, null, null, array($mock3)),
            )));

        $this->assertEquals($expectedResult, $this->service->getLastResults($this->userMock, $currentDate));
    }
}
