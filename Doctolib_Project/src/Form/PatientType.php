<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PatientType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('prenom', TextType      ::class, ['label' => false, 'attr' => ['placeholder' => 'Prenom'      , 'class' => 'form-control mt-5']])
            ->add('nom', TextType         ::class, ['label' => false, 'attr' => ['placeholder' => 'Nom'         , 'class' => 'form-control mt-5']])
            ->add('age', NumberType       ::class, ['label' => false, 'attr' => ['placeholder' => 'Age'         , 'class' => "form-control"]])
            ->add('email', EmailType      ::class, ['label' => false, 'attr' => ['placeholder' => 'email'       , 'class' => "form-control"]])
            ->add('password', PasswordType::class, ['label' => false, 'attr' => ['placeholder' => 'password'    , 'class' => 'form-control mt-5']])
            ->add('submit', SubmitType    ::class, ['attr'  => ['class' => 'btn btn-outline-info my-2 my-sm-0']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
