<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Orgabat\GameBundle\Entity\HistoryRealisation;

class APIGameController extends Controller
{
    public function scoreAction(Request $req)
    {
        $res = new JsonResponse();

        if ($req->getMethod() === Request::METHOD_POST) {
            $content = $req->getContent();

            if (!empty($content)) {
                // Récupération des données envoyées
                $params = json_decode($content, true);

                $data = $params['data'];
                if (!isset($data['id']) || gettype($data['id']) !== 'integer' ||
                    !isset($data['time']) || gettype($data['time']) !== 'integer' ||
                    !isset($data['health']) || gettype($data['health']) !== 'integer' ||
                    !isset($data['organization']) || gettype($data['organization']) !== 'integer' ||
                    !isset($data['business']) || gettype($data['business']) !== 'integer') {
                    var_dump($data);

                    $res->setStatusCode(JsonResponse::HTTP_BAD_REQUEST); // 400
                    $res->setData([
                        'message' => 'Données incorrectes',
                    ]);
                } else {
                    $em = $this->getDoctrine()->getManager();
                    $realisation = new HistoryRealisation();

                    $user = $this->get('security.token_storage')->getToken()->getUser();
                    $realisation->setUser($user);

                    $exercise = $em->getRepository('OrgabatGameBundle:Exercise')->find($data['id']);
                    $realisation->setExercise($exercise);

                    $realisation->setTimer($data['time']);
                    $realisation->setDate(new \Datetime());
                    $realisation->setHealthNote($data['health']);
                    $realisation->setOrganizationNote($data['organization']);
                    $realisation->setBusinessNotorietyNote($data['business']);

                    // Envoi en BDD
                    $em->persist($realisation);
                    $em->flush();

                    // TODO: Vérification des informations
                    // TODO: Upsert en BDD

                    $res->setStatusCode(JsonResponse::HTTP_ACCEPTED); // 202
                }
            } else {
                // Tentative
                $res->setStatusCode(JsonResponse::HTTP_BAD_REQUEST); // 400
                $res->setData([
                    'message' => 'Données incorrectes',
                ]);
            }
        }

        return $res;
    }
}
