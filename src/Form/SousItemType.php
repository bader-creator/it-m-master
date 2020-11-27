<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\SousItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class SousItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('question', EntityType::class, [
                'class'=>Question::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('q')
                        ->orderBy('q.label', 'ASC');
                },
                'choice_label' => 'label',
                'attr' => array('style' => 'width: 100%'),
                'multiple' => true
            ])
            ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SousItem::class,
        ]);
    }
}
