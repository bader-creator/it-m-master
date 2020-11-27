<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class,array(
                'label' => 'Ancien mot de passe : ',
                'constraints' => array(
                    new Assert\NotBlank()
                ),
                'attr'=>array(
                    'class'=>'form-control mb-1'
                ),
                'mapped'=>false,
            ))
            ->add('plainPassword',RepeatedType::class,array(
                'type'=>PasswordType::class,
                'invalid_message'=>'les deux mots de passe doivent être identiques',
                'constraints' => array(
                    new Assert\NotBlank()
                ),
                'options'=>array(
                    'attr'=>array(
                        'class'=>'form-control mb-1'
                )),
                'first_options'  => ['label' => 'Nouveau mot de passe : '],
                'second_options' => ['label' => 'Répéter mot de passe : '],
            ))
            ->add('Confirmer',SubmitType::class, array(
                'attr' => array(
                    'class'=>'btn  btn-outline-info pull-right mt-2'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
