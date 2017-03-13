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

class SportType extends AbstractType
{
    private static $index = 0;

    private $teamsSports = array(SPORT_FOTBAL, SPORT_NOHEJBAL, SPORT_BASKETBAL, SPORT_VOLEJBAL, SPORT_RINGO,
        SPORT_PRETAH_LANEM, SPORT_PING_PONG);
    private $timeSports = array(SPORT_FOTBAL, SPORT_BASKETBAL);
    private $drawSports = array(SPORT_FOTBAL, SPORT_BASKETBAL);
    private $setSports = array(SPORT_NOHEJBAL, SPORT_VOLEJBAL, SPORT_PING_PONG);
    private $setPointsSports = array(SPORT_NOHEJBAL, SPORT_VOLEJBAL, SPORT_PING_PONG, SPORT_RINGO);

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('sports' => false, 'scoring_types' => array()));
        parent::configureOptions($resolver);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $sport \AppBundle\Form\Sport */
        $sport = $options['sports'][self::$index];

        $builder
            ->add('active', CheckboxType::class, array(
                'label' => $sport->getTitle(),
                'required' => false,
                'data' => true
            ));

        $scoringTypes = $options['scoring_types'];

        $choices = array();
        /* @var $type \AppBundle\Entity\ScoringType */
        foreach ($scoringTypes as $type) {
            if ($type->getId() != TYPE_INDIVIDUALS)
                $choices[$type->getName()] = $type->getId();
        }


        if (in_array($sport->getId(), $this->teamsSports)) {
            $builder
                ->add('scoring_type', ChoiceType::class, array(
                    'choices' => $choices,
                    'label' => 'Systém'
                ));
        }

        if (in_array($sport->getId(), $this->drawSports)) {
            $builder
                ->add('win', TextType::class, array('required' => false, 'label' => 'Vítězství'))
                //->add('isDraw', CheckboxType::class, array('required' => false))
                ->add('draw', TextType::class, array('required' => false, 'label' => 'Remíza'))
                ->add('lose', TextType::class, array('required' => false, 'label' => 'Prohra'));
                //->add('forfeit', TextType::class, array('required' => false));
        }

        if (in_array($sport->getId(), $this->setSports)) {
            $builder
                ->add('sets', TextType::class, array('label' => 'Počet setů'));
        }

        if (in_array($sport->getId(), $this->setPointsSports)) {
            $builder
                ->add('set_points', TextType::class, array('label' => 'Počet bodů v setu'));
        }

        if (in_array($sport->getId(), $this->timeSports)) {
            $builder
                ->add('time', TextType::class, array('required' => false, 'label' => 'Čas'));
        }

        self::$index++;
    }
}