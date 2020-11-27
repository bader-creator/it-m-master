<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use  Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Groupe;
use Symfony\Component\Validator\Constraints\Choice;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('type',ChoiceType::class,[
                     'choices'=>$this->getType(),
                     'placeholder' => 'Sélectionner un type',
                 ])
                ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }
    private function getType()
    {
        $type = Groupe:: TYPE;
        $output = [];
        foreach($type as $k =>$v){
            $output[$v] = $k;
        }
        return $output;
    }
}
