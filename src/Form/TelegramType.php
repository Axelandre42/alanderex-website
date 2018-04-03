<?php

namespace App\Form;

use App\Entity\Telegram;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelegramType extends AbstractType
{
    private $doctrine;
    private $session;
    
    public function __construct(RegistryInterface $doctrine, SessionInterface $session)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$builder->getData())
        {
            if ($this->session->has('telegram_request') && ($telegram = $this->doctrine->getRepository(Telegram::class)->findOneBy(array(
                'validationToken' => $this->session->get('telegram_request')))))
            {
                $builder->setData($telegram);
            }
            else
            {
                $em = $this->doctrine->getEntityManager();
                
                $telegram = new Telegram();
                $telegram->setValidationIssued(new \DateTime());
                $telegram->setValidationTimeToLive(3600);
                $telegram->setValidationToken(uniqid());
                
                $builder->setData($telegram);
                
                $this->session->set('telegram_request', $telegram->getValidationToken());
                
                $em->persist($telegram);
                $em->flush();
            }
        }

        $builder->add('connect', LinkType::class, array('href' => 'https://telegram.me/AlanDerex_bot?start=' . $builder->getData()->getValidationToken()));
        $builder->add('username', TextType::class, array('disabled' => true, 'data' => $builder->getData()->getUsername()));
        $builder->add('displayName', TextType::class, array('disabled' => true, 'data' => $builder->getData()->getDisplayName()));
        
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function(FormEvent $event) {
                $telegram = $this->doctrine->getRepository(Telegram::class)->findOneBy(array('validationToken' => $this->session->get('telegram_request')));
                $telegram->setUser($event->getForm()->getParent()->getData());
                $event->setData($telegram);
            }
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Telegram::class
        ));
    }
}