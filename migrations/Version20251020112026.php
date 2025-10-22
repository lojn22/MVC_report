<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020112026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, name, stage, dialogue, image, symbol, interactable_items, top, "left" FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, stage INTEGER NOT NULL, dialogue VARCHAR(300) NOT NULL, image VARCHAR(255) NOT NULL, symbol VARCHAR(255) DEFAULT NULL, action_choices CLOB DEFAULT NULL --(DC2Type:json)
        , top INTEGER DEFAULT NULL, "left" INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO room (id, name, stage, dialogue, image, symbol, action_choices, top, "left") SELECT id, name, stage, dialogue, image, symbol, interactable_items, top, "left" FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, name, stage, dialogue, image, symbol, action_choices, top, "left" FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, stage INTEGER NOT NULL, dialogue VARCHAR(300) NOT NULL, image VARCHAR(255) NOT NULL, symbol VARCHAR(255) DEFAULT NULL, interactable_items CLOB DEFAULT NULL --(DC2Type:json)
        , top INTEGER DEFAULT NULL, "left" INTEGER DEFAULT NULL, action_text VARCHAR(100) NOT NULL, fullness_gain INTEGER NOT NULL, has_choices BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO room (id, name, stage, dialogue, image, symbol, interactable_items, top, "left") SELECT id, name, stage, dialogue, image, symbol, action_choices, top, "left" FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
    }
}
