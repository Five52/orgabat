<?php
/**
 * Created by PhpStorm.
 * User: lcoue
 * Date: 24/05/2016
 * Time: 17:26
 */

namespace Orgabat\GameBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,array('label'=>'Prénom'))
            ->add('lastname',TextType::class,array('label'=>'Nom'))
            ->add('password',TextType::class,array('label'=>'Date de naissance (jjmmaaaaa)'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Orgabat\GameBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'app_bundle_randonnee_form';
    }

}