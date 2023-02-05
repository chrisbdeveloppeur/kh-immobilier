<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205215804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge ADD bien_immo_id INT NOT NULL');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA4348AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('CREATE INDEX IDX_556BA4348AEFB514 ON charge (bien_immo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA4348AEFB514');
        $this->addSql('DROP INDEX IDX_556BA4348AEFB514 ON charge');
        $this->addSql('ALTER TABLE charge DROP bien_immo_id');
    }
}
