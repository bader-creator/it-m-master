<?php

namespace App\Form;

use App\Entity\Armoire;
use App\Entity\Fiche;
use  App\Entity\Site;
use App\Entity\NoeudAcceptance;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class NoeudAcceptanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeAcceptance',ChoiceType::class,[
                'choices'=>$this->getTypeAcceptance(),
                'placeholder' => 'Sélectionner un type d\'acceptance',
            ])
            ->add('userDestinator', EntityType::class, [
                'class'=>User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                            ->join('u.groupe','g')
                            ->where('u.enabled =:is_active and g.type=:isAuditeur')
                            ->setParameters(array('is_active'=>1,'isAuditeur'=>'T4'));
                           
                },
                'choice_label'=>'fullName',
                'placeholder' => 'choisir un auditeur',
                'attr' => array('style' => 'width: 100%'),
             
            ])
            ->add('site', EntityType::class, [
                'class'=>Site::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'choisir un site',
                'attr' => array('style' => 'width: 100%'),
               
            ])
            
            ->add('fiche', EntityType::class, [
                'class'=>Fiche::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.label', 'ASC');
                },
                'choice_label' => 'label',
                'placeholder' => 'choisir une fiche d\'acceptance',
                'attr' => array('style' => 'width: 100%'),
               
            ])
            ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NoeudAcceptance::class,
        ]);
    }
    private function getTypeAcceptance()
    {
        $typeAcceptance = NoeudAcceptance:: TypeAcceptance;
        $output = [];
        foreach($typeAcceptance as $k =>$v){
            $output[$v] = $k;
        }
        return $output;
    }
}
