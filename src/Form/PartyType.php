<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',    'text');
        $builder->add('date',    'datetime');
        $builder->add('message', 'textarea', array('required' => false, 'attr' => array('rows' => 3,)));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Model\Entity\Party',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'party';
    }
}
