<?php
namespace Stas\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Stas\CalendarBundle\Service\Exercise as ExerciseService;

/**
 * Class ExerciseController
 */
class ExerciseController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $user = $this->getUser();

        /** @var ExerciseService $exerciseService */
        $exerciseService = $this->get('stas.calendar.service.exercise');
        $results = $exerciseService->getLastResults($user);

        return $this->render('StasCalendarBundle:Exercise:index.html.twig',
            array('results' => $results));
    }
}
