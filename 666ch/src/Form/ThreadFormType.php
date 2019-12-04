<?php

namespace App\Form;

use App\Entity\ThreadForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ThreadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('threadText', TextType::class, [
                'label' => 'threadText',
                'required' => true, 
            ])

            ->add('mediaFile', FileType::class, [
                'label' => 'mediaFile',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '100024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'audio/mp3',
                            'audio/mpeg',
                            'application/mp4'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid mediaFile',
                    ])
                ],
            ])
            // ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ThreadForm::class,
        ]);
    }
}