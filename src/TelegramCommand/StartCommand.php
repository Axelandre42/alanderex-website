<?php

namespace App\TelegramCommand;

use App\Entity\Telegram;
use Ialert\TelegramBotBundle\Event\Telegram\UpdateEvent;
use Ialert\TelegramBotBundle\Telegram\Command\AbstractCommand;
use Symfony\Bridge\Doctrine\RegistryInterface;
use TelegramBot\Api\BotApi;

class StartCommand extends AbstractCommand
{
    private $doctrine;
    
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    public function getName() : string
    {
        return '/start';
    }
    
    public function execute(BotApi $api, UpdateEvent $event)
    {
        $update = $event->getUpdate();
        $repository = $this->doctrine->getRepository(Telegram::class);
        $em = $this->doctrine->getEntityManager();
        
		dump(explode(' ', $update->getMessage()->getText()));
		
        $telegram = $repository->findOneBy(array('validationToken' => explode(' ', $update->getMessage()->getText())[1]));
        
        $user = $update->getMessage()->getFrom();
        $telegram->setDisplayName($user->getFirstName() . ' ' . $user->getLastName());
        $telegram->setUsername($user->getUsername());
        
        $telegram->setChatId($update->getMessage()->getChat()->getId());
        
        $em->flush();
        
        $api->sendMessage(
            $update->getMessage()->getChat()->getId(),
            sprintf("Hello %s %s,\nYou're now registered in the Alan Derex website via Telegram !", $user->getFirstName(), $user->getLastName())
        );
    }
}