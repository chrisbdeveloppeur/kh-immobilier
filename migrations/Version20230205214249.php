<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205214249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solde ADD quantity INT DEFAULT NULL, DROP month_paid, DROP echeance_pasted, DROP malus_added, DROP malus_quantity, CHANGE bien_immo_id bien_immo_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solde ADD month_paid TINYINT(1) NOT NULL, ADD echeance_pasted TINYINT(1) NOT NULL, ADD malus_added TINYINT(1) NOT NULL, ADD malus_quantity DOUBLE PRECISION DEFAULT NULL, DROP quantity, CHANGE bien_immo_id bien_immo_id INT DEFAULT NULL');
    }
}
