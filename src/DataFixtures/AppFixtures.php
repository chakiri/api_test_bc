<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encrypt;

    public function __construct(UserPasswordEncoderInterface $encrypt)
    {
        $this->encrypt = $encrypt;
    }

    public function load(ObjectManager $manager)
    {
        $categories = ['Emploi', 'Automobile', 'Immobilier'];
        $attributes = [
            "{\"salaire\":\"3000\",\"type_contrat\":\"CDI\"}",
            "{\"type_carburant\":\"essence\",\"prix\":\"10000\"}",
            "{\"surface\":\"60\",\"prix\":\"100000\"}"
        ];

        $user = new User();

        $user->setEmail("dev@dev.fr");
        $user->setRoles(array('ROLE_USER'));
        $user->setFirstName("dev");
        $user->setLastName("test");

        $hash = $this->encrypt->encodePassword($user, 'test');

        $user->setPassword($hash);

        $manager->persist($user);

        $count = 0;

        for ($j = 0; $j<3; $j++) {
            $category = new Category();

            $category->setName($categories[$j]);

            $manager->persist($category);

            for ($i = 0; $i < 3; $i++){
                $advert = new Advert();

                $advert->setTitle("Mon annonce N° " . $count);
                $advert->setContent("Mon Contenu de l'annonce " . $count);
                $advert->setCategory($category);
                $advert->setUser($user);
                $advert->setAttributes($attributes[$j]);

                $manager->persist($advert);
                $count++;
            }
        }

        $manager->flush();
    }
}
