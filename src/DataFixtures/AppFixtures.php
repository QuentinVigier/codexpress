<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Like;
use App\Entity\Network;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slug = null;
    private $hash = null;

    public function __construct(
        private SluggerInterface $slugger,
        private UserPasswordHasherInterface $hasher
    ) {
        $this->slug = $slugger;
        $this->hash = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        # Tableau contenant les catégories
        $categories = [
            'HTML' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/html5/html5-plain.svg',
            'CSS' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/css3/css3-plain.svg',
            'JavaScript' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/javascript/javascript-plain.svg',
            'PHP' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/php/php-plain.svg',
            'SQL' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/postgresql/postgresql-plain.svg',
            'JSON' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/json/json-plain.svg',
            'Python' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/python/python-plain.svg',
            'Ruby' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/ruby/ruby-plain.svg',
            'C++' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/cplusplus/cplusplus-plain.svg',
            'Go' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/go/go-wordmark.svg',
            'bash' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/bash/bash-plain.svg',
            'Markdown' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/markdown/markdown-original.svg',
            'Java' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/java/java-original-wordmark.svg',
        ];

        $categoriesArray = [];  // Ce tableau nous servira pour conserver les objets Category
        $usersArray = [];  // Nouveau tableau pour stocker les utilisateurs créés
        $notesArray = [];  // Tableau pour stocker les notes créées

        foreach ($categories as $title => $icon) {
            $category = new Category();
            $category
                ->setTitle($title)
                ->setIcon($icon)
            ;
            array_push($categoriesArray, $category);  // Ajout de l'objet
            $manager->persist($category);
        }

        // 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $username = $faker->userName;  // Génère un nom d'utilisateur
            $usernameFinal = $this->slug->slug($username);  // UserName en slug
            $user = new User();
            $user
                ->setEmail($usernameFinal . '@' . $faker->freeEmailDomain)
                ->setUsername($username)
                ->setPassword(
                    $this->hash->hashPassword($user, 'admin')
                )
                ->setRoles(['ROLE_USER'])
            ;
            $manager->persist($user);

            $usersArray[] = $user;  // Stocker chaque utilisateur dans le tableau

            // Ajout de 10 notes par utilisateur
            for ($j = 0; $j < 10; $j++) {
                $note = new Note();
                $note
                    ->setTitle($faker->sentence())
                    ->setSlug($this->slug->slug($note->getTitle()))
                    ->setContent($faker->paragraphs(4, true))
                    ->setPublic($faker->boolean(50))
                    ->setViews($faker->numberBetween(100, 10000))
                    ->setCreator($user)
                    ->setCategory($faker->randomElement($categoriesArray))
                ;
                $manager->persist($note);

                $notesArray[] = $note;  // Stocker chaque note dans le tableau pour réutiliser dans les likes
            }

            // Ajout de 5 likes par utilisateur, en likant des notes au hasard
            for ($k = 0; $k < 5; $k++) {
                $like = new Like();
                $like
                    ->setCreator($user)
                    ->setNote($faker->randomElement($notesArray))  // On prend une note au hasard
                ;
                $manager->persist($like);
            }
        }

        // Génération de 20 réseaux sociaux fictifs
        for ($l = 0; $l < 20; $l++) {
            $network = new Network();
            $network
                ->setName($faker->company)  // Utilisation de "company" pour simuler un nom de réseau social
                ->setUrl($faker->url)  // Générer une URL
                ->setCreator($faker->randomElement($usersArray))  // Assigner un utilisateur aléatoire comme créateur
            ;

            $manager->persist($network);
        }

        $manager->flush();
    }
}
