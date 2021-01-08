<?php

namespace App\Form;

use App\Entity\Praticien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PraticienType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('prenom', TextType      ::class, ['label' => false, 'attr' => ['placeholder' => 'Prenom'      , 'class' => 'form-control mt-5']])
            ->add('nom', TextType         ::class, ['label' => false, 'attr' => ['placeholder' => 'Nom'         , 'class' => 'form-control mt-5']])
            ->add('specialite', TextType  ::class, ['label' => false, 'attr' => ['placeholder' => 'Specialité'  , 'class' => "form-control mt-5"]])
            ->add('email', EmailType      ::class, ['label' => false, 'attr' => ['placeholder' => 'email'       , 'class' => "form-control mt-5"]])
            ->add('password', PasswordType::class, ['label' => false, 'attr' => ['placeholder' => 'password'    , 'class' => 'form-control mt-5']])
            ->add('submit', SubmitType    ::class, ['attr'  => ['class' => 'btn btn-outline-info my-2 my-sm-0']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Praticien::class,
        ]);
    }
}
