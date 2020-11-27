<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Mission;
use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      
        $builder
            ->add('quantiteSortie')
            ->add('stock', EntityType::class, [
                'class'=>Stock::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nomProduit', 'ASC');
                },
                'choice_label' => 'nomProduit',
                'placeholder' => 'choisir un nom de produit',
                'attr' => array('style' => 'width: 100%'),
               
            ]) 
            ->add('save', SubmitType::class,['label' => 'Suivant'])
      
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
            
        ]);
    }
}
