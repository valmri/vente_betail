<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\AnimalRace;
use App\Entity\AnimalType;
use App\Entity\StatutVente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:load-datas',
    description: 'Permet de charger les données par default en base de données',
)]
class LoadDatasCommand extends Command
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $animauxTypes = ['Chien', 'Cheval', 'Brebis', 'Cochon'];
            $animauxTypesObjects = [];
            foreach ($animauxTypes as $typeNom) {
                if (!$this->entityManager->getRepository(AnimalType::class)->findOneBy(['nom' => $typeNom])) {
                    $type = new AnimalType();
                    $type->setNom($typeNom);
                    $this->entityManager->persist($type);
                    $animauxTypesObjects[$typeNom] = $type;
                }
            }
            $this->entityManager->flush();

            $animauxRaces = [
                ['type' => 'Chien', 'race' => 'Labrador'],
                ['type' => 'Cheval', 'race' => 'Frison'],
                ['type' => 'Cheval', 'race' => 'Pottok'],
                ['type' => 'Cheval', 'race' => 'Irish cob'],
                ['type' => 'Brebis', 'race' => 'Mérinos'],
                ['type' => 'Brebis', 'race' => 'Solognotes'],
            ];
            foreach ($animauxRaces as $animal) {
                if (!$this->entityManager->getRepository(AnimalRace::class)->findOneBy(['nom' => $animal['race']])) {
                    $race = new AnimalRace();
                    $race->setNom($animal['race']);
                    $race->setType($animauxTypesObjects[$animal['type']]);
                    $this->entityManager->persist($race);
                }
            }
            $this->entityManager->flush();

            $venteStatuts = ['En vente', 'Vendu'];
            foreach ($venteStatuts as $statutNom) {
                if (!$this->entityManager->getRepository(StatutVente::class)->findOneBy(['nom' => $statutNom])) {
                    $statut = new StatutVente();
                    $statut->setNom($statutNom);
                    $this->entityManager->persist($statut);
                }
            }
            $this->entityManager->flush();

            $user = new User();
            $password = '12345';
            $user->setUsername('admin')
                ->setPassword($this->passwordHasher->hashPassword($user, $password))
                ->setRoles(['ROLE_ADMIN']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $io->success('Les données ont été intégrées dans la base.');

            return Command::SUCCESS;
        } catch (\Exception $erreur) {
            $this->entityManager->rollback();
            $io->error('Une erreur est survenue : ' . $erreur->getMessage());

            return Command::FAILURE;
        }
    }
}
