<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609220645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE financement CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE date_investissement date_investissement DATE DEFAULT NULL, CHANGE montant montant DOUBLE PRECISION DEFAULT NULL, CHANGE taux taux DOUBLE PRECISION DEFAULT NULL, CHANGE mensualites mensualites DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE financement CHANGE type type VARCHAR(255) NOT NULL, CHANGE date_investissement date_investissement DATE NOT NULL, CHANGE montant montant DOUBLE PRECISION NOT NULL, CHANGE taux taux DOUBLE PRECISION NOT NULL, CHANGE mensualites mensualites DOUBLE PRECISION NOT NULL');
    }
}
