<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('body',     'textarea', array('attr' => array('rows' => 3,)));
    }

    public function getName()
    {
        return 'comment';
    }
}
