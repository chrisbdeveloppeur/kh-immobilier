<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205214915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solde ADD payed TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE solde ADD CONSTRAINT FK_669183678AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solde DROP FOREIGN KEY FK_669183678AEFB514');
        $this->addSql('ALTER TABLE solde DROP payed');
    }
}
