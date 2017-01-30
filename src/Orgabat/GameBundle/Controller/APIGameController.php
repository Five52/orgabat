<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Orgabat\GameBundle\Entity\ExerciseHistory;

class APIGameController extends Controller
{
    /**
     * Retrieve and insert the game score in database
     * @param Request $req The request
     * @return JsonResponse The response
     */
    public function scoreAction(Request $req)
    {
        $res = new JsonResponse();

        if ($req->getMethod() === Request::METHOD_POST) {
            $content = $req->getContent();

            if (!empty($content)) {
                // Retrieve data
                $params = json_decode($content, true);

                $data = $params['data'];
                if (!isset($data['exerciseId']) || gettype($data['exerciseId']) !== 'integer' ||
                    !isset($data['time']) || gettype($data['time']) !== 'integer' ||
                    !isset($data['health']) || gettype($data['health']) !== 'integer' ||
                    !isset($data['organization']) || gettype($data['organization']) !== 'integer' ||
                    !isset($data['business']) || gettype($data['business']) !== 'integer') {

                    $res->setStatusCode(JsonResponse::HTTP_BAD_REQUEST); // 400
                    $res->setData([
                        'message' => 'Données incorrectes',
                    ]);
                } else {
                    $user = $this->get('security.token_storage')->getToken()->getUser();
                    $em = $this->getDoctrine()->getManager();
                    $exercise = $em->getRepository('OrgabatGameBundle:Exercise')
                        ->find($data['exerciseId']);

                    // Create the new attempt
                    $exerciseHistory = new ExerciseHistory();
                    $exerciseHistory
                        ->setUser($user)
                        ->setExercise($exercise)
                        ->setTimer($data['time'])
                        ->setHealthNote($data['health'])
                        ->setOrganizationNote($data['organization'])
                        ->setBusinessNotorietyNote($data['business']);

                    // Save in DB
                    $em->persist($exerciseHistory);
                    $em->flush();

                    $res->setStatusCode(JsonResponse::HTTP_ACCEPTED); // 202
                }
            } else {
                $res->setStatusCode(JsonResponse::HTTP_BAD_REQUEST); // 400
                $res->setData([
                    'message' => 'Données incorrectes',
                ]);
            }
        }

        return $res;
    }
}
