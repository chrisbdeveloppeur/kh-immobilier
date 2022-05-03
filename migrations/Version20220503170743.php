<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503170743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB71EA7F144');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB71EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB71EA7F144');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB71EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
