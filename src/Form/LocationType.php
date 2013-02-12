<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',        'text');
        $builder->add('adress',      'text');
        $builder->add('zip_code',    'number');
        $builder->add('city',        'text');
        $builder->add('phone',       'number',   array('required' => false,));
        $builder->add('description', 'textarea', array('required' => false, 'attr' => array('rows' => 3,)));
    }

    public function getName()
    {
        return 'location';
    }
}
