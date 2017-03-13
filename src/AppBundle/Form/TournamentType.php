<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 19.12.2016
 * Time: 19:01
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournamentType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('scoring_types' => array()));
        parent::configureOptions($resolver);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('data' => \AppBundle\Model\DateUtils::createTournamentName(), 'label' => 'Název'))
            ->add('teams', CollectionType::class, array(
                'entry_type' => TeamType::class,
                'entry_options' => array(
                    'teams' => $builder->getData()->getTeams(),
                    'label' => false
                )
            ))
            ->add('sports', CollectionType::class, array(
                'entry_type' => SportType::class,
                'entry_options' => array(
                    'sports' => $builder->getData()->getSports(),
                    'scoring_types' => $options['scoring_types']
                )
            ))
            ->add('submit', SubmitType::class, array('label' => 'Vytvořit turnaj'));
    }
}