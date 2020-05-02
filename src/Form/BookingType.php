<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    /**
     * @var FrenchToDateTimeTransformer
     */
    private $frenchTransformer;

    public function __construct(FrenchToDateTimeTransformer $frenchTransformer)
    {
        $this->frenchTransformer = $frenchTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getConfig('Date d\'arrivée', ''))
            ->add('endDate', TextType::class, $this->getConfig('Date de départ',''))
            ->add('comment', TextareaType::class, $this->getConfig('Commentaire', '', ['required' => false]))
        ;

        $builder->get('startDate')->addModelTransformer($this->frenchTransformer);
        $builder->get('endDate')->addModelTransformer($this->frenchTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
