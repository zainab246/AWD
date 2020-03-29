<?php
/**
 * Created by PhpStorm.
 * User: sgb638
 * Date: 08/11/19
 * Time: 14:34
 */

namespace Blogger\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName');

    }

    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }


}