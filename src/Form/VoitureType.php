<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prixJournalier')
            ->add('prixMensuel')
            ->add('nbPlaces', ChoiceType::class, [
                'choices' => range(1, 9,1),
                'choice_label' => function($choice){
                    return $choice;
                }
            ])
            ->add('isManuelle', ChoiceType::class, [
                'choices' => [
                    'Manuelle' => true,
                    'Automatique' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
