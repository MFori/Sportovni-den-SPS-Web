<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 18.12.2016
 * Time: 18:38
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserType
 * @package AppBundle\Form
 */
class UserType extends AbstractType
{
    /**
     * Build form
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class, array('label' => 'Uživatelské jméno'));

        if ($builder->getData()->getNew()) {
            $builder
                ->add('generate', CheckboxType::class, array('required' => false, 'label' => 'Generovat heslo'))
                ->add('plainPassword', TextType::class, array('required' => false, 'disabled' => false, 'label' => 'Heslo'))
                ->add('send', CheckboxType::class, array('required' => false, 'label' => 'Odeslat heslo na email'));
        }
        $builder
            ->add('admin', CheckboxType::class, array('required' => false, 'label' => 'Administrátor'))
            ->add('save', SubmitType::class, array('label' => 'Uložit'));
    }
}