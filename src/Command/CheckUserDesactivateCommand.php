<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:check-user-desactivate',
    description: 'vérifie si l\'utilisateur est désactivé et le supprime s\'il dépasse une période donnée',
)]
class CheckUserDesactivateCommand extends Command
{
    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            ->addArgument('time', InputArgument::REQUIRED, 'Nombre d\'années depuis désactivation du compte');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //$io est le terminal, sert à "faire parler le terminal"
        $io = new SymfonyStyle($input, $output);
        //$time sera l'argument (le nombre d'année à vérifier)
        $duration = $input->getArgument('time');

        //récupérer les utilisateurs
        // Vérifier la date de désactivation
        //Boucler sur les utilisateurs
        //Si > $time , supprimer les utilisateurs désactivés
        try {
            $users = $this->userRepository->findBy(['isActive' => false]);
            $totalUsers = count($users);

            if ($totalUsers === 0) {
                $io->success('Aucun utilisateur n\'est désactivé');
                return Command::SUCCESS;
            }
            // La valeur de $totalUsers va remplacer %d 
            $io->section(sprintf('il y a %d utilisateur(s) à vérifier', $totalUsers));
            $io->text('Mise à jour des utilisateurs');
            //va afficher une barre de progression
            $io->progressStart($totalUsers);
            //convertir $time en date 
            $time = (new \DateTime('now'))->modify('+' . $duration . ' years');

            //$counter va calculer le nombre d'utilisateur supprimés
            $counter = 0;
            foreach ($users as $user) {
                if ($user->getDesactivateAt() >= $time) {
                    $this->entityManager->remove($user);
                    $counter++;
                    $io->progressAdvance();
                    $io->newLine();
                    $io->text('L\'utilisateur ' . $user->getFirstname() . ' ' . $user->getLastname() .' a été supprimé');
                }
            }
            $io->progressFinish();
            $io->text('Sur ' .$totalUsers . ' utilisateurs, ' . $counter . ' ont été supprimés' );
            $this->entityManager->flush();

            $io->success('Les utilisateurs désactivés depuis plus de ' . $duration . 'ans ont été supprimés');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Une erreur est survenue lors de la vérification des utilisateurs');
            return Command::FAILURE;
        }
    }
}
