<?php

namespace Orgabat\GameBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class APIGameController extends Controller
{
    /**
     * @Route("/api/score")
     * @Method({"POST"})
     */
    public function testRouteApi(Request $req)
    {
        $res = new JsonResponse();
        // $usr = $this->get('security.token_storage')->getToken()->getUser();
        // $id = $usr->getId();

        if ($req->getMethod() === Request::METHOD_POST) {
            $content = $req->getContent();

            if (!empty($content)) {
                // Récupération des données envoyées
                $params = json_decode($content, true);

                // TODO: Vérification des informations
                // TODO: Update du timer
                // TODO: Upsert en BDD

                // IF soucis BDD
                // $res->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

                $res->setStatusCode(JsonResponse::HTTP_ACCEPTED); // 202
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
