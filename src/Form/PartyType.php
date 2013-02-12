<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',    'text');
        $builder->add('date',    'datetime');
        $builder->add('message', 'textarea', array('required' => false, 'attr' => array('rows' => 3,)));
    }

    public function getName()
    {
        return 'party';
    }
}
