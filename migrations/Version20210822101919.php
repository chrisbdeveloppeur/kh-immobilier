<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210822101919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien_immo (id INT AUTO_INCREMENT NOT NULL, building VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, loyer_hc NUMERIC(10, 2) NOT NULL, charges NUMERIC(10, 2) NOT NULL, loyer_ttc NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locataire (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, building VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, mode VARCHAR(255) NOT NULL, solde NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bien_immo');
        $this->addSql('DROP TABLE locataire');
    }
}
