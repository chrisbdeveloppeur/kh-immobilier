<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113130515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo ADD libelle VARCHAR(255) DEFAULT NULL, CHANGE loyer_hc loyer_hc INT NOT NULL, CHANGE charges charges INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo DROP libelle, CHANGE loyer_hc loyer_hc NUMERIC(10, 2) NOT NULL, CHANGE charges charges NUMERIC(10, 2) NOT NULL');
    }
}
