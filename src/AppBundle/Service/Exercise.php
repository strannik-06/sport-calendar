<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;

/**
 * Service for Exercise entity
 */
class Exercise
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User      $user
     * @param \DateTime $currentDate
     *
     * @return array
     */
    public function getLastResults(User $user, \DateTime $currentDate = null)
    {
        if (!isset($currentDate)) {
            $currentDate = new \DateTime();
        }
        $oneWeekAgo = clone $currentDate;
        $oneWeekAgo->modify('- 1 week');
        $twoWeekAgo = clone $currentDate;
        $twoWeekAgo->modify('- 2 week');
        $repository = $this->entityManager->getRepository('AppBundle\Entity\Exercise');

        return array(
            'today' => $repository->findBy(array(
                'user' => $user,
                'date' => $currentDate,
            )),
            'one-week-ago' => $repository->findBy(array(
                'user' => $user,
                'date' => $oneWeekAgo,
            )),
            'two-week-ago' => $repository->findBy(array(
                'user' => $user,
                'date' => $twoWeekAgo,
            )),
        );
    }
}
