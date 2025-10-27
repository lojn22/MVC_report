<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rooms = [
            [
                'name' => 'Town',
                'stage' => 0,
                'dialogue' => 'Lane is always..',
                'actionText' => '',
                'fullnessGain' => 0,
                'image' => 'img/town.png',
            ],
            [
                'name' => 'Mrs. Kim’s House',
                'stage' => 1,
                'dialogue' => 'Kim: "Ah the Gilmores, happy Thanksgiving. Come in!" 
                Lor:"Happy thanksgiving. She is in good mood this year." 
                They are greating every one and get to the food table. 
                Lor:"Oh, mrs.Kim, just a beautiful table, as always." 
                Kim:"Try the tofurkey, turkey made from tofu." 
                Rory:"Oh, we definitely will"',
                'actionText' => '',
                'fullnessGain' => 0,
                'image' => 'img/kim.png',
            ],
            [
                'name' => 'Sookie’s House',
                'stage' => 2,
                'dialogue' => 'Lane is always..',
                'actionText' => '',
                'fullnessGain' => 0,
                'image' => 'img/sookie.png',
            ],
            [
                'name' => 'Luke’s Diner',
                'stage' => 3,
                'dialogue' => 'Lane is always..',
                'actionText' => '',
                'fullnessGain' => 0,
                'image' => 'img/luke.png',
            ],
            [
                'name' => 'Emily & Richard’s House',
                'stage' => 4,
                'dialogue' => 'Lane is always..',
                'actionText' => '',
                'fullnessGain' => 0,
                'image' => 'img/emily.png',
            ],
        ];

        foreach ($room as $data) {
            $rooom = new Room();
            $room->setName($data['name']);
            $room->setStage($data['stage']);
            $room->setDialogue($data['dialogue']);
            $room->setActionText($data['actionText']);
            $room->setFullnessGain($data['fullnessGain']);
            $room->setImage($data['image']);
            $manager->persist($room);
        }

        $manager->flush();
    }
}
