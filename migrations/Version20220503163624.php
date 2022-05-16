<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503163624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_des_lieux ADD creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etat_des_lieux ADD CONSTRAINT FK_F721031261220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F721031261220EA6 ON etat_des_lieux (creator_id)');
        $this->addSql('ALTER TABLE user ADD etat_des_lieux_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491EA7F144 ON user (etat_des_lieux_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_des_lieux DROP FOREIGN KEY FK_F721031261220EA6');
        $this->addSql('DROP INDEX UNIQ_F721031261220EA6 ON etat_des_lieux');
        $this->addSql('ALTER TABLE etat_des_lieux DROP creator_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491EA7F144');
        $this->addSql('DROP INDEX UNIQ_8D93D6491EA7F144 ON user');
        $this->addSql('ALTER TABLE user DROP etat_des_lieux_id');
    }
}
