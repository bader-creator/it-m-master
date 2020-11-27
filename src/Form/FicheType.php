<?php

namespace App\Form;

use App\Entity\Fiche;
use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class FicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('items', EntityType::class, [
                
                'class'=>Item::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.label', 'ASC');
                },
                'choice_label' => 'label',
                'attr' => array('style' => 'width: 100%'),
           
                'multiple' => true,
                
            ])
            ->add('type',ChoiceType::class,[
                'choices'=>$this->getTypeFiche(),
                'placeholder' => 'Sélectionner un type de fiche',
            ])
            ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fiche::class,
        ]);
    }
    private function getTypeFiche()
    {
        $typeFiche = Fiche:: TypeFiche;
        $output = [];
        foreach($typeFiche as $k =>$v){
            $output[$v] = $k;
        }
        return $output;
    }
}
