<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404223421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE financement (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT NOT NULL, type VARCHAR(255) NOT NULL, date_investissement DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, taux DOUBLE PRECISION NOT NULL, mensualites DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_59895F568AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE financement ADD CONSTRAINT FK_59895F568AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE financement');
    }
}
