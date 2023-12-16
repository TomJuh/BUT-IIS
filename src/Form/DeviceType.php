<?php

namespace App\Form;

use App\Entity\Device;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

                $builder
                    ->add('description', TextType::class, [
                        'label' => 'Device Description',
                    ])
                    ->add('user_alias', TextType::class, [
                        'label' => 'User Alias',
                    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Device::class,
        ]);
    }
}
