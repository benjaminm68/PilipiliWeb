<?php

namespace App\Command;

use Symfony\Component\Mime\Email;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class ProductActivationCommand extends Command
{
    protected static $defaultName = 'app:product-activation';
    protected static $defaultDescription = 'Gérer l\'activation des produits';

    private $em;
    private $productRepository;
    private $mailer;

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->mailer = $mailer;


        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Activer un produit');
        $this->addArgument('id', InputArgument::OPTIONAL, 'ID du produit à activer');
        $this->addArgument('activated', InputArgument::OPTIONAL, 'Oui ou nan');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $output->writeln([
            '==================',
            'Activer un produit',
            '==================',
            '',
        ]);
        
        $helper = $this->getHelper('question');        

        $productIdQuestion = new Question('Indiquez l\'id du produit à modifier: ');
        $resultId = $helper->ask($input, $output, $productIdQuestion);

        if($this->productRepository->find($resultId)){
            //On indique que le produit demandé à bien été trouvé
            $output->writeln('Produit trouvé');

            //On propose de l'activer ou non
            $productActiveQuestion = new ChoiceQuestion(
                'Voulez vous activer le produit ? (Non par defaut) ',
                ['Non', 'Oui'],
                0
            );

            //On sctock le résultat dans la variable
            $resultActivation = $helper->ask($input, $output, $productActiveQuestion);
            
            //Selon le choix de l'utilisateur la valeur du résultat est changé
            if($resultActivation === "Oui"){
                $resultActivation = 1;
            }else{
                $resultActivation = 0;
            }

            // On recherche le produit avec l'id indiqué
            $productFind = $this->productRepository->find($resultId);

            // On change la valeur de enabled
            $productFind->setEnabled($resultActivation);
            $this->em->persist($productFind);
            $this->em->flush();
            
            if($resultActivation === 1){
                $output->writeln('Le produit a été activé');

                $email = (new Email())
                ->from('hello@example.com')
                ->to('testelan68@gmail.com')
                ->subject('Nouveau produit activé')
                ->text('Le produit '.$productFind->getName().' a été ajouté !
                http://localhost:8000/produits');

                $this->mailer->send($email);

            }else{
                $output->writeln('Le produit a été désactivé');
            }

        }else{
            $output->writeln('Produit non trouvé');
            die;
        }
        


        return Command::SUCCESS;
    }
}
