<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129121107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, locataire_id INT DEFAULT NULL, bien_immo_id INT DEFAULT NULL, created_date DATETIME NOT NULL, file_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_A2B07288D8A38199 (locataire_id), INDEX IDX_A2B072888AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288D8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072888AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE documents');
    }
}
