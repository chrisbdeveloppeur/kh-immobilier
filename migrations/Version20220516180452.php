<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516180452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form_section (id INT AUTO_INCREMENT NOT NULL, etat_des_lieux_id INT NOT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_1C1F1AD41EA7F144 (etat_des_lieux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE form_section ADD CONSTRAINT FK_1C1F1AD41EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id)');
        $this->addSql('ALTER TABLE form_field ADD form_section_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE form_field ADD CONSTRAINT FK_D8B2E19BD1E4AFEE FOREIGN KEY (form_section_id) REFERENCES form_section (id)');
        $this->addSql('CREATE INDEX IDX_D8B2E19BD1E4AFEE ON form_field (form_section_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_field DROP FOREIGN KEY FK_D8B2E19BD1E4AFEE');
        $this->addSql('DROP TABLE form_section');
        $this->addSql('DROP INDEX IDX_D8B2E19BD1E4AFEE ON form_field');
        $this->addSql('ALTER TABLE form_field DROP form_section_id');
    }
}
