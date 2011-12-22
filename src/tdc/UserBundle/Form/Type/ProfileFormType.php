<?php

namespace tdc\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildUserForm(FormBuilder $builder, array $options)
    {
        parent::buildUserForm($builder, $options);

        // add your custom field
        $builder->add('name');
        $builder->add('lastname');
    }

    public function getName()
    {
        return 'tdc_user_profile';
    }
}
