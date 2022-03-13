<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Employee;
use App\Entity\WorkingHours;

class AddTimeInProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nbHours', TextType::class, ['label' => 'Daily Cost'])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'lastName',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkingHours::class,
        ]);
    }
}
