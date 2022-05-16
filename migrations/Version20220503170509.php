<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503170509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_des_lieux DROP FOREIGN KEY FK_F721031261220EA6');
        $this->addSql('ALTER TABLE etat_des_lieux ADD CONSTRAINT FK_F721031261220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_des_lieux DROP FOREIGN KEY FK_F721031261220EA6');
        $this->addSql('ALTER TABLE etat_des_lieux ADD CONSTRAINT FK_F721031261220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
