<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Mission;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class AffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

  
        $builder

          ->add('magasinier', EntityType::class, [
            'class'=>User::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('l')
                ->join('l.groupe','g')
                ->where('l.enabled =:is_active and g.type=:magasinier')
                ->setParameters(array('is_active'=>1,'magasinier'=>'T0'));
               },
            'choice_label' => 'fullName',
            'attr' => array('style' => 'width: 100%'),
            'placeholder' => 'Choisir un magasinier'
       
            ])


            ->add('livreur', EntityType::class, [
            'class'=>User::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('l')
                ->join('l.groupe','g')
                ->where('l.enabled =:is_active and g.type=:livreur')
                ->setParameters(array('is_active'=>1,'livreur'=>'T1'));
               },
            'choice_label' => 'fullName',
            'attr' => array('style' => 'width: 100%'),
            'placeholder' => 'Choisir un livreur'
       
            ])
           ->add('installateur', EntityType::class, [
                
            'class'=>User::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('l')
                ->join('l.groupe','g')
                ->where('l.enabled =:is_active and g.type=:installateur')
                ->setParameters(array('is_active'=>1,'installateur'=>'T5'));      
            },
            'choice_label' => 'fullName',
            'attr' => array('style' => 'width: 100%'),
            'placeholder' => 'Choisir un installateur'
       
            ])
            ->add('metteurService', EntityType::class, [
                
                'class'=>User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                    ->join('l.groupe','g')
                    ->where('l.enabled =:is_active and g.type=:metteurService')
                    ->setParameters(array('is_active'=>1,'metteurService'=>'T2'));  
                },
                'choice_label' => 'fullName',
                'attr' => array('style' => 'width: 100%'),
                'placeholder' => 'Choisir un metteur en service'
           
                ])
            ->add('acceptateur', EntityType::class, [
                
                    'class'=>User::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('l')
                        ->join('l.groupe','g')
                        ->where('l.enabled =:is_active and g.type=:acceptateur')
                        ->setParameters(array('is_active'=>1,'acceptateur'=>'T3'));  
                    },
                    'choice_label' => 'fullName',
                    'attr' => array('style' => 'width: 100%'),
                    'placeholder' => 'Choisir un acceptateur'
                    
                    ])
             
            ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
         
        ]);
    }
}
