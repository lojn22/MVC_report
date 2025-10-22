<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020093827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__player AS SELECT id, name, current_stage, visited_rooms, inventory FROM player');
        $this->addSql('DROP TABLE player');
        $this->addSql('CREATE TABLE player (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, current_stage INTEGER NOT NULL, visited_rooms CLOB NOT NULL --(DC2Type:json)
        , inventory CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO player (id, name, current_stage, visited_rooms, inventory) SELECT id, name, current_stage, visited_rooms, inventory FROM __temp__player');
        $this->addSql('DROP TABLE __temp__player');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player ADD COLUMN fullness INTEGER NOT NULL');
    }
}
