<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913121730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE solde (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT DEFAULT NULL, month_paid TINYINT(1) NOT NULL, echeance_pasted TINYINT(1) NOT NULL, malus_added TINYINT(1) NOT NULL, malus_quantity DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_669183678AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE solde ADD CONSTRAINT FK_669183678AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE bien_immo DROP solde, DROP pasted_echeance, DROP current_month_paid, DROP malus_solde');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE solde');
        $this->addSql('ALTER TABLE bien_immo ADD solde DOUBLE PRECISION DEFAULT NULL, ADD pasted_echeance TINYINT(1) NOT NULL, ADD current_month_paid TINYINT(1) NOT NULL, ADD malus_solde TINYINT(1) NOT NULL');
    }
}
