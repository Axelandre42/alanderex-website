<?php
namespace App\Command;

use App\TelegramCommand\StartCommand;
use Ialert\TelegramBotBundle\Telegram\Command\CommandChain;
use Ialert\TelegramBotBundle\Telegram\TelegramApi;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use TelegramBot\Api\BotApi;

class UpdateCommand extends Command
{
    private $botApi;
    private $telegramApi;
    private $dataDirectory;
    private $commandChain;
    private $doctrine;
    
    public function __construct($dataDirectory, BotApi $botApi, TelegramApi $telegramApi, CommandChain $commandChain, RegistryInterface $doctrine)
    {
        $this->botApi = $botApi;
        $this->telegramApi = $telegramApi;
        $this->dataDirectory = $dataDirectory;
        $this->commandChain = $commandChain;
        $this->doctrine = $doctrine;

        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setName('app:telegram:update')
            ->setDescription('Trigger a Telegram update')
            ->setHelp('This command allows you to trigger a Telegram update...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $fs = new Filesystem();
        
        $this->commandChain->addCommand(new StartCommand($this->doctrine));
        
        $offset = 0;
        if ($fs->exists($this->dataDirectory . '/telegram_offset.txt'))
        {
            $offset = intval(file_get_contents($this->dataDirectory . '/telegram_offset.txt'));
            $io->text(
                sprintf('Current offset %s.', $offset)
            );
        }
        
        $updates = $this->botApi->getUpdates($offset);
        
        $io->success('Update performed. Processing...');
        
        foreach ($updates as $update)
        {
            $this->telegramApi->processUpdate($update);
            $io->text(
                sprintf('Processed update %s.', $update->getUpdateId())
            );
        }
        
        if (!$fs->exists($this->dataDirectory))
        {
            $fs->mkdir($this->dataDirectory);
        }
        
        if ($updates)
        {
            $fs->dumpFile($this->dataDirectory . '/telegram_offset.txt', $updates[count($updates) - 1]->getUpdateId() + 1);
        }
    }
}