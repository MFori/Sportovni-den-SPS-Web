<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 19.12.2016
 * Time: 19:06
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    private static $index = 0;

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('teams' => false));
        parent::configureOptions($resolver);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $team \AppBundle\Entity\Team */
        $team = $options['teams'][self::$index];

        $builder
            ->add('active', CheckboxType::class, array(
                'label' => $team->getName(),
                'required' => false,
                'data' => true
            ));

        self::$index++;
    }
}