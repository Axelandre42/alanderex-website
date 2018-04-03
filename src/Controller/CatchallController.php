<?php

namespace App\Controller;

use App\TelegramCommand\StartCommand;
use Ialert\TelegramBotBundle\Telegram\Command\CommandChain;
use Ialert\TelegramBotBundle\Telegram\TelegramApi;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatchallController extends Controller
{
    private $telegramApi;
    private $commandChain;
    private $doctrine;
    
    public function __construct(TelegramApi $telegramApi, CommandChain $commandChain, RegistryInterface $doctrine)
    {
        $this->telegramApi = $telegramApi;
        $this->commandChain = $commandChain;
        $this->doctrine = $doctrine;
    }
    
    public function telegramUpdate(Request $request)
    {
        $this->commandChain->addCommand(new StartCommand($this->doctrine));
        
        return $this->telegramApi->handleUpdate($request);
    }
}