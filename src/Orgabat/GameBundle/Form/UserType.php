<?php
/**
 * Created by PhpStorm.
 * User: lcoue
 * Date: 24/05/2016
 * Time: 17:26
 */

namespace Orgabat\GameBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Doctrine\ORM\EntityManager $em */

        /*$sections = $entityManager->getRepository('OrgabatGameBundle:Section')->findAll();
        $choices = [];
        foreach($sections as $section) {
            $choices[$section->getName()] = $section->getId();
        }*/

        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('birthdate', TextType::class, ['label' => 'Date de naissance (JJMMAAAA)'])
            /*->add('section', ChoiceType::class, [
                'choices' => $choices, 'label' => 'Profile'
            ])*/
            // Liste déroulante pour le choix de la Section à partir de celles entrées en BDD
            ->add('section', EntityType::class, array(
                // query choices from this entity
                'class' => 'OrgabatGameBundle:Section',

                // use the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ))
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Orgabat\GameBundle\Entity\Apprentice'
        ));
    }

}