<?php
namespace Stas\CalendarBundle\Tests\Service;

use Stas\CalendarBundle\Service\Exercise as ExerciseService;
use Stas\CalendarBundle\Entity\Exercise as ExerciseEntity;
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

    /** @var \PHPUnit_Framework_MockObject_MockObject | ExerciseEntity */
    protected $entityMock;

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

        $this->entityMock = $this->getMockBuilder('Stas\CalendarBundle\Entity\Exercise')
            ->getMock();
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
        unset($this->entityMock);
        unset($this->userMock);
        unset($this->repositoryMock);
        unset($this->entityManagerMock);
    }

    /**
     * Test for Stas\CalendarBundle\Service\Exercise::getLastResults
     */
    public function testGetLastResults()
    {
        //todo: it's better if $currentDate is some fixed date.
        // Then you do not need to modify it. Just set manually these 3 dates in the test. And check with them.
        $currentDate = new \DateTime();
        //todo: Incorrect expected data type. In real code array will contain 3 arrays with entities.
        // In test you are checking that it is not array of entities, but one entity
        $expectedResult = array(
            'today' => $this->entityMock,
            'one-week-ago' => $this->entityMock,
            'two-week-ago' => $this->entityMock,
        );

        $this->entityManagerMock->expects($this->once())->method('getRepository')
            ->with('Stas\CalendarBundle\Entity\Exercise')
            ->will($this->returnValue($this->repositoryMock));

        $this->repositoryMock->expects($this->at(0))
            ->method('findBy')
            ->with(array('user' => $this->userMock, 'date' => $currentDate))
            ->will($this->returnValue($this->entityMock));
        $this->repositoryMock->expects($this->at(1))
            ->method('findBy')
            ->with(array('user' => $this->userMock, 'date' => $currentDate->modify('- 1 week')))
            ->will($this->returnValue($this->entityMock));
        $this->repositoryMock->expects($this->at(2))
            ->method('findBy')
            ->with(array('user' => $this->userMock, 'date' => $currentDate->modify('- 1 week')))
            ->will($this->returnValue($this->entityMock));

        $this->assertEquals($expectedResult, $this->service->getLastResults($this->userMock, $currentDate));
    }
}
