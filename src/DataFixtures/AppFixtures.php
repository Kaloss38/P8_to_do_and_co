<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;    
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("anonymous");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getUsername()));
        $user->setEmail("anonymous@email.com");
        
        $manager->persist($user);

        $useradmin = new User();
        $useradmin->setUsername("admin");
        $useradmin->setPassword($this->passwordHasher->hashPassword($useradmin, $useradmin->getUsername()));
        $useradmin->setEmail("admin@email.com");
        $useradmin->setRoles(["ROLE_ADMIN"]);
     
        $manager->persist($useradmin);

        $simpleuser = new User();
        $simpleuser->setUsername("simple_user");
        $simpleuser->setPassword($this->passwordHasher->hashPassword($simpleuser, $simpleuser->getUsername()));
        $simpleuser->setEmail("user@email.com");
       
        $manager->persist($simpleuser);

        $simpleuser2 = new User();
        $simpleuser2->setUsername("simple_user_2");
        $simpleuser2->setPassword($this->passwordHasher->hashPassword($simpleuser2, $simpleuser2->getUsername()));
        $simpleuser2->setEmail("user2@email.com");
        
        $manager->persist($simpleuser2);

        $task1 = new Task();
        $task1->setTitle("Première tâche");
        $task1->setContent("J'appartiens à l'utilisateur anonyme");
        $task1->setAuthor($user);
        ;
        $manager->persist($task1);

        $task2 = new Task();
        $task2->setTitle("Deuxième tâche");
        $task2->setContent("J'appartiens aussi à l'utilisateur anonyme");
        $task2->setAuthor($user);
        
        $manager->persist($task2);

        $task3 = new Task();
        $task3->setTitle("Tâche facile");
        $task3->setContent("Créée par l'administrateur");
        $task3->setAuthor($useradmin);
        
        $manager->persist($task3);

        $task4 = new Task();
        $task4->setTitle("Une autre tâche");
        $task4->setContent("Créée par l'administrateur");
        $task4->setAuthor($useradmin);
        
        $manager->persist($task4);

        $task5 = new Task();
        $task5->setTitle("La tâche d'un utilisateur classique");
        $task5->setContent("Retroussez-vous les manches");
        $task5->setAuthor($simpleuser);
        
        $manager->persist($task5);

        $task6 = new Task();
        $task6->setTitle("Tâche");
        $task6->setContent('A faire pour demain /!\ créé par utilisateur classique');
        $task6->setAuthor($simpleuser);
        
        $manager->persist($task6);

        $task7 = new Task();
        $task7->setTitle("Une autre tâche 3");
        $task7->setContent("Créée par l'administrateur");
        $task7->setAuthor($useradmin);
        
        $manager->persist($task7);

        $task8 = new Task();
        $task8->setTitle("Une autre tâche 4");
        $task8->setContent("Créée par l'administrateur");
        $task8->setAuthor($useradmin);
        
        $manager->persist($task8);

        $manager->flush();
    }
}
