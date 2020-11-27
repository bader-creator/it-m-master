<?php

namespace App\Form;
use App\Entity\ValeurNomenclature;
use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref')
            ->add('nomProduit')
            ->add('quantiteGenerale')
            ->add('unite')
            ->add('category', EntityType::class, array(
                'class' => ValeurNomenclature::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->join('u.typeNomenclature','t')
                    ->where('t.code = :only')
                    ->setParameter('only','code02');
                },
                'placeholder' => 'Choisir une catgorie',
                'choice_label' => 'name',
                'required' => true,
                'attr' => array('style' => 'width: 100%')
            ))
            ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
