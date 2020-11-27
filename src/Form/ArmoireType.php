<?php

namespace App\Form;
use App\Entity\Region;
use App\Entity\Armoire;
use App\Entity\ValeurNomenclature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ArmoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('name')
            ->add('typeArmoire', EntityType::class, array(
                'class' => ValeurNomenclature::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->join('u.typeNomenclature','t')
                    ->where('t.code = :only')
                    ->setParameter('only','code01');
                },
                'placeholder' => 'Choisir un type d\'armoire',
                'choice_label' => 'name',
                'required' => true,
                'attr' => array('style' => 'width: 100%')
            ))
            ->add('longitude')
            ->add('latitude')
            ->add('adress')
            ->add('region', EntityType::class, array(
                'class' => Region::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r');
                },
                'placeholder' => 'Choisir un région',
                'choice_label' => 'name',
                'required' => true,
                'attr' => array('style' => 'width: 100%')
            ))
            ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
        ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Armoire::class,
        ]);
    }
}
