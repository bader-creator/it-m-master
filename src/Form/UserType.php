<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;
use App\Entity\Groupe;
use App\Entity\Fonction;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username',TextType::class,array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Regex(array(
                            'pattern' => '/^[a-zA-Z0-9\@]+$/',
                            'message' => 'Cette valeur doit contenir que des chiffres et des lettres.'
                            )
                        ),
                        new Assert\Length(array('min' => 3)),
                        new Assert\Length(array('max' => 12))
                    )
                ))
                ->add('phone')
                ->add('firstName')
                ->add('lastName')
                ->add('email',TextType::class,array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Email(),
                    )
                ))
                ->add('sexe',ChoiceType::class,[
                    'choices'=>$this->getGenres()
                ])
                ->add('enabled',ChoiceType::class,[
                    'choices'=>$this->getStatut()
                ])
                ->add('groupe', EntityType::class, [
                    'class'=>Groupe::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.name', 'ASC');
                    },
                    'placeholder' => 'Choisir un groupe',
                    'choice_label' => 'name',
                    'attr' => array('style' => 'width: 100%')
                ])
                ->add('fonction', EntityType::class, [
                    'class'=>Fonction::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.name', 'ASC');
                    },
                    'placeholder' => 'Choisir une fonction',
                    'choice_label' => 'name',
                    'attr' => array('style' => 'width: 100%')
                ])
                ->add('save', SubmitType::class,['label' => 'Sauvegarder']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    private function getGenres()
    {
        $genres = User:: GENRE;
        $output = [];
        foreach($genres as $k =>$v){
            $output[$v] = $k;
        }
        return $output;
    }

    private function getStatut()
    {
        $statuts = User:: STATUT;
        $output = [];
        foreach($statuts as $k =>$v){
            $output[$v] = $k;
        }
        return $output;
    }
}
