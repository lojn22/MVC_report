<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rooms = [
            [
                'id' => 1,
                'name' => 'Mrs. Kim’s House',
                'stage' => 1,
                'dialogue' => 'Kim: "Ah the Gilmores, happy Thanksgiving. Come in!" 
                Lorelai:"Happy thanksgiving. She is in good mood this year."

                They are greeting everyone and get to the food table.
                 
                Lorelai:"Oh, mrs.Kim, just a beautiful table, as always." 
                Kim:"Try the tofurkey, turkey made from tofu." 
                Rory:"Oh, we definitely will"',
                'image' => 'img/kim.png',
                'symbol' => 'img/kim-loga.png',
                'action_choices' => '{
                    "napkin": { "image": "napkin.png", "top": "80%", "left": "1%" },
                    "turkey": { "image": "turkey.png", "top": "75%", "left": "18%" }
                    }',
                'top' => 5,
                'left' => 45,
            ],
            [
                'id' => 2,
                'name' => 'Sookie’s House',
                'stage' => 2,
                'dialogue' => 'Lorelai:"Hi Hon. Happy Thanksgiving."
                Sookie:"Thank god civilization has arrived!"
                Loerlai:"What is wrong?" 
                Sookie:"Jackson is gonna deep-fry the turkey.
                I tried to talk him out of it but he is excited about it." 
                Rory:"Maybe it will not be that bad" 
                Lorelai:"Yeah, Deep-frying is kind of in now" 
                Sookie:"I do not care. You do not deep-fry turkey."',
                'image' => 'img/sookieI.png',
                'symbol' => 'img/sookie-loga.png',
                'action_choices' => '{
                    "sookieI": { "image": "sookieI.png", "top": "29%", "left": "80%" },
                    "jackson": { "image": "jackson.png", "top": "2%", "left": "60%" }
                    }',
                'top' => 45,
                'left' => 80,
            ],
            [
                'id' => 3,
                'name' => 'Luke’s Diner',
                'stage' => 3,
                'dialogue' => 'Lorelai:"Hey Everybody"
                Luke:"Happy Thanksgiving"
                "I will be right back. That is our table over there."
                
                The girls get to the table and Jess is joining them for dinner. 
                
                Luke: "Where are you guys in your day?" 
                Rory:"We hit the Kims, we hit Sookies, and we go to the grandparents from here." 
                Lorelai: "Full day" 
                Luke: "You can skip eating this one if you want. It is no bigg deal" 
                Lorelai: "No way. You are the main event today my friend."
                Luke: "Good."',
                'image' => 'img/luke.png',
                'symbol' => 'img/luke-loga.png',
                'action_choices' => '{
                    "coffee": { "image": "coffee.png", "top": "68%", "left": "51%" },
                    "soda": { "image": "soda.png", "top": "68%", "left": "62%" }
                    }',
                'top' => 45,
                'left' => 5,
            ],
            [
                'id' => 4,
                'name' => 'Emily & Richard’s House',
                'stage' => 4,
                'dialogue' => 'Emily: "Hello" 
                Rory: "Hi, Grandma. Happy Thanksgiving." 
                Emily:"Thank you, Rory. Happy Thanksgiving, Lorelai." 
                Lorelai: "Happy Thanksgiving." 
                
                Later at the dinner table, Rory is asked which colleges she applied to. 
                Lorelai assumes she only applied to Harvard. Rory has not told anyone else yet.
                
                Douglas: "You did not apply to just Harvard, Did you?" 
                Natalie: "Chilton would not allow that."
                Rory: "Well no." 
                Lor: "No?" We applied elsewhere?!" 
                Rory:"Princeton, um.. Yale" 
                
                Lorelai gets furious, thinking har parents are influencing Rorys choices, since they both went to Yale and hoped Rory would follow in their footsteps. 
                
                Emily:"You can not even let Rory have one piece of our lives even if it is her choise. You hate us that much."',
                'image' => 'img/emily.png',
                'symbol' => 'img/emily-loga.png',
                'action_choices' => '{
                    "fork": { "image": "fork.png", "top": "63%", "left": "29%" },
                    "dessert": { "image": "dessert.png", "top": "74%", "left": "64%" }
                    }',
                'top' => 85,
                'left' => 45,
            ],
        ];

        foreach ($rooms as $data) 
        {
            $room = new Room();
            $room->setName($data['id']);
            $room->setName($data['name']);
            $room->setStage($data['stage']);
            $room->setDialogue($data['dialogue']);
            $room->setImage($data['image']);
            $room->setImage($data['symbol']);
            $room->setActionText($data['action_choices']);
            $room->setFullnessGain($data['top']);
            $room->setFullnessGain($data['left']);
            $manager->persist($room);
        }

        $manager->flush();
    }
}
