<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\Exercise as ExerciseService;

/**
 * Class ExerciseController
 */
class ExerciseController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $user = $this->getUser();

        /** @var ExerciseService $exerciseService */
        $exerciseService = $this->get('app.exercise');
        $results = $exerciseService->getLastResults($user);

        return $this->render('AppBundle:Exercise:index.html.twig',
            array('results' => $results));
    }
}
