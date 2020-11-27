<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\ValeurNomenclature;
use App\Entity\TypeNomenclature;

class ValeurNomenclatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('typeNomenclature', EntityType::class, [
                    'class'=>TypeNomenclature::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.name', 'ASC');
                    },
                    'placeholder' => 'Choisir un type nomenclature',
                    'choice_label' => 'name',
                    'attr' => array('style' => 'width: 100%')
                ])
                ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ValeurNomenclature::class,
        ]);
    }
}
