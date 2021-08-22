<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210822122318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE locataire DROP FOREIGN KEY FK_C47CF6EB58ABF955');
        $this->addSql('ALTER TABLE locataire ADD CONSTRAINT FK_C47CF6EB58ABF955 FOREIGN KEY (logement_id) REFERENCES bien_immo (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE locataire DROP FOREIGN KEY FK_C47CF6EB58ABF955');
        $this->addSql('ALTER TABLE locataire ADD CONSTRAINT FK_C47CF6EB58ABF955 FOREIGN KEY (logement_id) REFERENCES bien_immo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
