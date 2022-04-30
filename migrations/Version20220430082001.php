<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220430082001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etat_des_lieux (id INT AUTO_INCREMENT NOT NULL, bailleur_id INT DEFAULT NULL, sens_circuit TINYINT(1) NOT NULL, date DATETIME NOT NULL, INDEX IDX_F721031257B5D0A2 (bailleur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_des_lieux_form_field (etat_des_lieux_id INT NOT NULL, form_field_id INT NOT NULL, INDEX IDX_C6705C591EA7F144 (etat_des_lieux_id), INDEX IDX_C6705C59F50D82F4 (form_field_id), PRIMARY KEY(etat_des_lieux_id, form_field_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_field (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etat_des_lieux ADD CONSTRAINT FK_F721031257B5D0A2 FOREIGN KEY (bailleur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE etat_des_lieux_form_field ADD CONSTRAINT FK_C6705C591EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etat_des_lieux_form_field ADD CONSTRAINT FK_C6705C59F50D82F4 FOREIGN KEY (form_field_id) REFERENCES form_field (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bien_immo ADD etat_des_lieux_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB71EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id)');
        $this->addSql('CREATE INDEX IDX_174DAB71EA7F144 ON bien_immo (etat_des_lieux_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB71EA7F144');
        $this->addSql('ALTER TABLE etat_des_lieux_form_field DROP FOREIGN KEY FK_C6705C591EA7F144');
        $this->addSql('ALTER TABLE etat_des_lieux_form_field DROP FOREIGN KEY FK_C6705C59F50D82F4');
        $this->addSql('DROP TABLE etat_des_lieux');
        $this->addSql('DROP TABLE etat_des_lieux_form_field');
        $this->addSql('DROP TABLE form_field');
        $this->addSql('DROP INDEX IDX_174DAB71EA7F144 ON bien_immo');
        $this->addSql('ALTER TABLE bien_immo DROP etat_des_lieux_id');
    }
}
