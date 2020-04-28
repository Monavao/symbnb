<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, $this->getConfig('Date d\'arrivée', '', ['widget' => 'single_text']))
            ->add('endDate', DateType::class, $this->getConfig('Date de départ','', ['widget' => 'single_text']))
            ->add('comment', TextareaType::class, $this->getConfig('Commentaire'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
