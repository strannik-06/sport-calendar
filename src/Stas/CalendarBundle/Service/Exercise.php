<?php
namespace Stas\CalendarBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Service for Exercise entity
 */
class Exercise
{
    /** @var \Doctrine\ORM\EntityManager */
    private $_entityManager;

    /**
     * @param EntityManager     $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->_entityManager = $entityManager;
    }

    /**
     * @param \DateTime $currentDate
     *
     * @return array
     */
    public function getLastResults($currentDate = null)
    {
        $repository = $this->_entityManager->getRepository('Stas\CalendarBundle\Entity\Exercise');

        return array(
            'two-week-ago' => $repository->findBy(array(
                'date' => new \DateTime('2 week ago'),
            )),
            'one-week-ago' => $repository->findBy(array(
                'date' => new \DateTime('1 week ago'),
            )),
            'now' => $repository->findBy(array(
                'date' => new \DateTime(),
            )),
        );
    }
}
