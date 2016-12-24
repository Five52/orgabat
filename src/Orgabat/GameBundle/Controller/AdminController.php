<?php

namespace Orgabat\GameBundle\Controller;

use Orgabat\GameBundle\Entity\Apprentice;
use Orgabat\GameBundle\Entity\Section;
use Orgabat\GameBundle\Form\SectionType;
use Orgabat\GameBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function createUserAction(Request $request)
    {
        $user = new Apprentice();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
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
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('default_admin_board');
    }
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
}
