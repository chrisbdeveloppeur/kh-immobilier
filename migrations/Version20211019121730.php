<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211019121730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE copropriete');
        $this->addSql('ALTER TABLE bien_immo ADD copro_name VARCHAR(255) DEFAULT NULL, ADD copro_email VARCHAR(255) DEFAULT NULL, ADD copro_adresse VARCHAR(255) DEFAULT NULL, ADD copro_contact VARCHAR(255) DEFAULT NULL, ADD copro_phone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE copropriete (id INT AUTO_INCREMENT NOT NULL, logement_id INT DEFAULT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, contact_first_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, contact_last_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_17FC511558ABF955 (logement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE copropriete ADD CONSTRAINT FK_17FC511558ABF955 FOREIGN KEY (logement_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE bien_immo DROP copro_name, DROP copro_email, DROP copro_adresse, DROP copro_contact, DROP copro_phone');
    }
}
