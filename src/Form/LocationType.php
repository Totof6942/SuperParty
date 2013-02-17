<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',        'text');
        $builder->add('adress',      'text');
        $builder->add('zip_code',    'number');
        $builder->add('city',        'text');
        $builder->add('phone',       'text',   array('required' => false,));
        $builder->add('description', 'textarea', array('required' => false, 'attr' => array('rows' => 3,)));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Model\Entity\Location',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'location';
    }
}
