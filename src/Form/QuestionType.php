<?php

namespace App\Form;

use App\Entity\Choix;
use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('type',ChoiceType::class,[
                'choices'=>$this->getType(),
                 ]
            )
            ->add('criticity',ChoiceType::class,[
                'choices'=>$this->getCriticity(),
                'placeholder' => 'Sélectionner une criticité',
            ])
            
            ->add('choix', EntityType::class, [
                'class'=>Choix::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.label', 'ASC');
                },
              
                'choice_label' => 'label',
                'attr' => array('style' => 'width: 100%'),
                'multiple' => true,
                
            ])
            ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
    
    private function getType()
    {
        $types = Question:: Type;
        $output = [];
        foreach($types as $k =>$v){
            $output[$v] = $k;
        }
        return $output;
    }

    private function getCriticity()
    {
        $criticity = Question:: CRITCITY;
        $output = [];
        foreach($criticity as $k =>$v){
            $output[$v] = $k;
        }
        return $output;
    }
}
