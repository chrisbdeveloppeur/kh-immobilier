<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027111605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A264808AEFB514');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A264808AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A264808AEFB514');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A264808AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
