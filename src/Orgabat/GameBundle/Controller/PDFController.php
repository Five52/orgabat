<?php
/**
 * Created by PhpStorm.
 * User: lenaic
 * Date: 29/01/2017
 * Time: 16:19
 */

namespace Orgabat\GameBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PDFController extends Controller
{
    /**
     * Get a pdf file for an apprentice using his games statistics
     *
     * @return Response : pdf file
     */
    public function getUserInfosUsingPDFAction() {
        // Get the current user
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        // Get all exercises
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();

        // Get the categories played by the current user
        $categories = $em
            ->getRepository('OrgabatGameBundle:Category')
            ->getExercisesOfAllCategoriesByUser($user)
        ;

        // Get the total global score
        $globalScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $globalScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }

        // Create a view for rendering the pdf
        $html = $this->renderView('OrgabatGameBundle:Pdf:user.html.twig', array(
            'user' => $user,
            'categories' => $categories,
            'stats' => [
                'global' => $globalScore
            ],
        ));

        //If You want to see the twig file
        /*return $this->render('OrgabatGameBundle:Pdf:user.html.twig', array(
            'user' => $user,
            'categories' => $categories,
            'stats' => [
                'global' => $globalScore
            ],
        ));*/

        // Generate the pdf
        $html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response(
            $html2pdf->Output(__DIR__.$user->getName().".pdf", 'S'),
            200,
            [
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="' . $user->getName() . '".pdf"'
            ]
        );
    }

    /**
     * Get a pdf file for all apprentices statistics
     *
     * @return Response : pdf file
     */
    public function getAllUsersInfosUsingPDFAction() {
        // Get the current user
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        // Get the sections managed by the user
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // Get all sections if user == admin
            $sections = $em
                ->getRepository('OrgabatGameBundle:Section')
                ->getWithTrainersAndApprentices()
            ;
        } else {
            // Get section managed if user == trainer
            $sections = $em
                ->getRepository('OrgabatGameBundle:Trainer')
                ->getWithSections($user->getId())
                ->getSections()
            ;
        }

        // Get all exercises
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();

        // Test
        //$userManager = $this->get('fos_user.user_manager');
        //$user = $userManager->findUserBy(array('id' => '36'));

        foreach ($sections as $section) {
            // Iterate all apprentices of the current user
            foreach ($section->getApprentices() as $apprentice) {
                $apprentices[] = $apprentice;
                $categories = $em
                    ->getRepository('OrgabatGameBundle:Category')
                    ->getExercisesOfAllCategoriesByUser($apprentice);
                $categoriesOfAllUsers[] = $categories;
            }
        }
        // Get the total global score
        $globalScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $globalScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }

        // Create a view for rendering the pdf
        $html = $this->renderView('OrgabatGameBundle:Pdf:allUsers.html.twig', array(
            'users' => $apprentices,
            'categories' => $categoriesOfAllUsers,
            'stats' => [
                'global' => $globalScore
            ],
        ));

        /*
        // If you want to render the twig file
        return $this->render('OrgabatGameBundle:Pdf:allUsers.html.twig', array(
            'users' => $apprentices,
            'categories' => $categoriesOfAllUsers,
            'stats' => [
                'global' => $globalScore
            ],
        ));
        */

        // Generate the pdf
        
        $html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response(
            $html2pdf->Output(__DIR__."apprentis.pdf", 'S'),
            200,
            [
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="apprentis.pdf"'
            ]
        );

    }
}