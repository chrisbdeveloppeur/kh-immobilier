<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220528190629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quittance DROP loyer_ttc, CHANGE loyer_ht loyer_ht INT DEFAULT NULL, CHANGE charges charges INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD adresse VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quittance ADD loyer_ttc DOUBLE PRECISION DEFAULT NULL, CHANGE loyer_ht loyer_ht DOUBLE PRECISION DEFAULT NULL, CHANGE charges charges DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP adresse');
    }
}
