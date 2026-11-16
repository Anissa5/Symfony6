<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261116173211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

       public function up(Schema $schema): void
    {

        $this->addSql(
        'ALTER TABLE program
         CHANGE poster image VARCHAR(500) DEFAULT NULL,
         CHANGE title name VARCHAR(255) NOT NULL,
         CHANGE summary description LONGTEXT NOT NULL,
         ADD nb_seasons INT DEFAULT NULL'
        );

        $this->addSql(
        'UPDATE program
         SET nb_seasons = (
             SELECT COUNT(*)
             FROM season
             WHERE season.program_id = program.id
         )'
        );

        $this->addSql(
        'ALTER TABLE program MODIFY nb_seasons INT NOT NULL'
        );
    }


    public function down(Schema $schema): void
    {
    $this->addSql(
        'ALTER TABLE program
         CHANGE image poster VARCHAR(255) DEFAULT NULL,
         CHANGE name title VARCHAR(255) NOT NULL,
         CHANGE description summary LONGTEXT NOT NULL,
         DROP nb_seasons'
    );
    }
}
