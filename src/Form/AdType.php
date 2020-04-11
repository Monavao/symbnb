<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfig(string $label = '', string $placeholder = ''): array
    {
        return [
            'label' => $label,
            'attr'  => [
                'placeholder' => $placeholder
            ]
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, $this->getConfig('Titre', "Entrez votre titre"))
                ->add('slug', TextType::class, $this->getConfig('Chaîne URL', 'Titre de la page dans la barre d\'adresse (automatique)'))
                ->add('coverImage', UrlType::class, $this->getConfig('Url de l\'image Principale', 'Une belle image pour votre annonce'))
                ->add('introduction', TextType::class, $this->getConfig('Introduction', 'Donnez description globale de votre annonce'))
                ->add('content', TextareaType::class, $this->getConfig('Détaillez votre annonce', 'Description détaillée'))
                ->add('rooms', IntegerType::class, $this->getConfig('Nombre de chambre', 'Chambre(s) disponible(s)'))
                ->add('price', MoneyType::class, $this->getConfig('Prix par nuit', 'Prix par nuit'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Ad::class,
            ]);
    }
}
