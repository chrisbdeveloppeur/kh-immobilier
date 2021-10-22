<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211022053418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE copropriete (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien_immo ADD copropriete_id INT DEFAULT NULL, DROP copro_name, DROP copro_email, DROP copro_adresse, DROP copro_contact, DROP copro_phone');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB76B07769E FOREIGN KEY (copropriete_id) REFERENCES copropriete (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_174DAB76B07769E ON bien_immo (copropriete_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB76B07769E');
        $this->addSql('DROP TABLE copropriete');
        $this->addSql('DROP INDEX UNIQ_174DAB76B07769E ON bien_immo');
        $this->addSql('ALTER TABLE bien_immo ADD copro_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD copro_email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD copro_adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD copro_contact VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD copro_phone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP copropriete_id');
    }
}
