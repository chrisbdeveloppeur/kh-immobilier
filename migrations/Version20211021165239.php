<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021165239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo ADD copropriete_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB76B07769E FOREIGN KEY (copropriete_id) REFERENCES copropriete (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_174DAB76B07769E ON bien_immo (copropriete_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB76B07769E');
        $this->addSql('DROP INDEX UNIQ_174DAB76B07769E ON bien_immo');
        $this->addSql('ALTER TABLE bien_immo DROP copropriete_id');
    }
}
