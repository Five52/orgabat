<?php

namespace Orgabat\GameBundle\Controller;

use Orgabat\GameBundle\Entity\Apprentice;
use Orgabat\GameBundle\Entity\Section;
use Orgabat\GameBundle\Entity\Trainer;
use Orgabat\GameBundle\Form\SectionType;
use Orgabat\GameBundle\Form\TrainerType;
use Orgabat\GameBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    
    # User
    
    
    public function createUserAction(Request $request)
    {
        $user = new Apprentice();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setUsername($user->getFirstName().' '.$user->getLastName());
            $user->addRole('ROLE_APPRENTICE');
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:addUser.html.twig', ['form' => $form->createView()]);
    }
    
    public function editUserAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('OrgabatGameBundle:Apprentice')->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setUsername($user->getFirstName().' '.$user->getLastName());
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:editUser.html.twig', ['form' => $form->createView()]);
    }
    
    public function deleteUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('OrgabatGameBundle:Apprentice')->find($id);
        $realisations = $em->getRepository('OrgabatGameBundle:HistoryRealisation')->findBy(array('user' => $user));
        foreach ($realisations as $realisation) {
            $em->remove($realisation);
        }
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('default_admin_board');
    }
    
    public function exportApprenticesBySectionAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $section = $em->getRepository('OrgabatGameBundle:Section')->find($id);

        $apprentices = $em->getRepository('OrgabatGameBundle:Apprentice')->findBy(array('section'=>$section));
        $handle = fopen('php://memory', 'r+');

        foreach ($apprentices as $apprentice) {
            fputcsv($handle, $apprentice->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="apprentis.csv"'
        ));
    }

    public function exportAllApprenticesAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $apprentices = $em->getRepository('OrgabatGameBundle:Apprentice')->findAll();
        $handle = fopen('php://memory', 'r+');

        foreach ($apprentices as $apprentice) {
            fputcsv($handle, $apprentice->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="apprentis.csv"'
        ));
    }

    public function importApprenticesAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, array('label' => 'Fichier CSV :'))
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('submitFile');
            if (($handle = fopen($file->getData(),"r")) !== FALSE) {
                while (($data = fgetcsv($handle, ",")) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        $em = $this->getDoctrine()->getManager();
                        $apprentice = $em
                            ->getRepository('OrgabatGameBundle:Apprentice')
                            ->findOneBy(array("firstName" => $data[0]));
                        if ( !$apprentice) {
                            $apprentice = new Apprentice();
                            $apprentice->setFirstName($data[0]);
                            $apprentice->setLastName($data[1]);
                            $apprentice->setUsername($data[0] . ' ' . $data[1]);
                            $apprentice->setEmail($data[2]);
                            $apprentice->setPassword($data[3]);

                            $section = $em
                                ->getRepository('OrgabatGameBundle:Section')
                                ->findOneBy(array("name" => $data[4]));
                            $apprentice->setSection($section);
                            $apprentice->setEnabled(true);
                            $apprentice->addRole('ROLE_APPRENTICE');
                            $em->persist($apprentice);
                            $em->flush();
                        }
                    }
                }
                fclose($handle);
            }
            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:importFromCsv.html.twig',
            array('form' => $form->createView(),)
        );
    }
    
    # Trainer 

    public function createTrainerAction(Request $request)
    {
        $trainer = new Trainer();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TrainerType::class, $trainer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trainer = $form->getData();
            $trainer->setUsername($trainer->getFirstName().' '.$trainer->getLastName());
            $trainer->addRole('ROLE_TRAINER');
            $em->persist($trainer);
            $em->flush();

            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:addTrainer.html.twig', ['form' => $form->createView()]);
    }

    public function editTrainerAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $trainer = $em->getRepository('OrgabatGameBundle:Trainer')->find($id);
        $form = $this->createForm(TrainerType::class, $trainer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trainer = $form->getData();
            $trainer->setUsername($trainer->getFirstName().' '.$trainer->getLastName());
            $em->persist($trainer);
            $em->flush();
            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:editTrainer.html.twig', ['form' => $form->createView()]);
    }

    public function deleteTrainerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $trainer = $em->getRepository('OrgabatGameBundle:Trainer')->find($id);
        $em->remove($trainer);
        $em->flush();
        return $this->redirectToRoute('default_admin_board');
    }

    public function exportTrainersBySectionAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $section = $em->getRepository('OrgabatGameBundle:Section')->find($id);

        $trainers = $em->getRepository('OrgabatGameBundle:Trainer')->findBy(array('section'=>$section));
        $handle = fopen('php://memory', 'r+');

        foreach ($trainers as $trainer) {
            fputcsv($handle, $trainer->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="enseignants.csv"'
        ));
    }

    public function exportAllTrainersAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $trainers = $em->getRepository('OrgabatGameBundle:Trainer')->findAll();
        $handle = fopen('php://memory', 'r+');

        foreach ($trainers as $trainer) {
            fputcsv($handle, $trainer->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="enseignants.csv"'
        ));
    }

    public function importTrainersAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, array('label' => 'Fichier CSV :'))
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('submitFile');
            if (($handle = fopen($file->getData(),"r")) !== FALSE) {
                while (($data = fgetcsv($handle, ",")) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        $em = $this->getDoctrine()->getManager();
                        $trainer = $em
                            ->getRepository('OrgabatGameBundle:Trainer')
                            ->findOneBy(array("firstName" => $data[0]));
                        if ( !$trainer) {
                            $trainer = new Trainer();
                            $trainer->setFirstName($data[0]);
                            $trainer->setLastName($data[1]);
                            $trainer->setUsername($data[0] . ' ' . $data[1]);
                            $trainer->setEmail($data[2]);
                            $trainer->setPassword($data[3]);

                            $section = $em
                                ->getRepository('OrgabatGameBundle:Section')
                                ->findOneBy(array("name" => $data[4]));
                            $trainer->setSection($section);
                            $trainer->setEnabled(true);
                            $trainer->addRole('ROLE_TRAINER');
                            $em->persist($trainer);
                            $em->flush();
                        }
                    }
                }
                fclose($handle);
            }
            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:importFromCsv.html.twig',
            array('form' => $form->createView(),)
        );
    }
    
    
    # Section
    
    
    public function createSectionAction(Request $request)
    {
        $section = new Section();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $section = $form->getData();
            $em->persist($section);
            $em->flush();

            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:addSection.html.twig', ['form' => $form->createView()]);
    }
    
    public function editSectionAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $section = $em->getRepository('OrgabatGameBundle:Section')->find($id);
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $section = $form->getData();
            $em->persist($section);
            $em->flush();
            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:editSection.html.twig', ['form' => $form->createView()]);
    }
    
    public function deleteSectionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $section = $em->getRepository('OrgabatGameBundle:Section')->find($id);
        $apprentices = $em->getRepository('OrgabatGameBundle:Apprentice')->findBy(array('section' => $section));
        foreach ($apprentices as $apprentice) {
            $apprentice->setSection(null);
        }
        $em->remove($section);
        $em->flush();
        return $this->redirectToRoute('default_admin_board');
    }

    public function exportAllSectionsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $sections = $em->getRepository('OrgabatGameBundle:Section')->findAll();
        $handle = fopen('php://memory', 'r+');

        foreach ($sections as $section) {
            fputcsv($handle, $section->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="classes.csv"'
        ));
    }

    public function importSectionsAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, array('label' => 'Fichier CSV :'))
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('submitFile');
            if (($handle = fopen($file->getData(),"r")) !== FALSE) {
                while (($data = fgetcsv($handle, ",")) !== FALSE) {
                    $num = count($data);
                    for ($c=0; $c < $num; $c++) {
                        $data = explode(",",$data[$c]);
                        $em = $this->getDoctrine()->getManager();
                        $section = new Section();
                        $section->setName($data[0]);
                        $em->persist($section);
                        $em->flush();
                    }
                }
                fclose($handle);
            }
            return $this->redirectToRoute('default_admin_board');
        }
        return $this->render('OrgabatGameBundle:Admin:importFromCsv.html.twig',
            array('form' => $form->createView(),)
        );
    }
}
