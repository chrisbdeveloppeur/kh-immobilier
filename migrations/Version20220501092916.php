<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220501092916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_des_lieux DROP FOREIGN KEY FK_F721031257B5D0A2');
        $this->addSql('DROP INDEX IDX_F721031257B5D0A2 ON etat_des_lieux');
        $this->addSql('ALTER TABLE etat_des_lieux DROP bailleur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_des_lieux ADD bailleur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etat_des_lieux ADD CONSTRAINT FK_F721031257B5D0A2 FOREIGN KEY (bailleur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F721031257B5D0A2 ON etat_des_lieux (bailleur_id)');
    }
}
