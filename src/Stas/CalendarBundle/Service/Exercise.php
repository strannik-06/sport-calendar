<?php
namespace Stas\CalendarBundle\Service;

use Doctrine\ORM\EntityManager;
use Stas\CalendarBundle\Entity\User;

/**
 * Service for Exercise entity
 */
class Exercise
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    /**
     * @param EntityManager     $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User   $user
     * @param string $currentDate
     *
     * @return array
     */
    public function getLastResults(User $user, $currentDate = null)
    {
        if (!isset($currentDate)) {
            $currentDate = date('Y-m-d');
        }
        $repository = $this->entityManager->getRepository('Stas\CalendarBundle\Entity\Exercise');

        return array(
            'today' => $repository->findBy(array(
                'user' => $user,
                'date' => $currentDate,
            )),
            'one-week-ago' => $repository->findBy(array(
                'user' => $user,
                'date' => date('Y-m-d', strtotime("$currentDate - 1 week")),
            )),
            'two-week-ago' => $repository->findBy(array(
                'user' => $user,
                'date' => date('Y-m-d', strtotime("$currentDate - 2 week")),
            )),
        );
    }
}
