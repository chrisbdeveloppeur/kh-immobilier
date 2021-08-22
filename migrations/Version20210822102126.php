<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210822102126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE locataire ADD logement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE locataire ADD CONSTRAINT FK_C47CF6EB58ABF955 FOREIGN KEY (logement_id) REFERENCES bien_immo (id)');
        $this->addSql('CREATE INDEX IDX_C47CF6EB58ABF955 ON locataire (logement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE locataire DROP FOREIGN KEY FK_C47CF6EB58ABF955');
        $this->addSql('DROP INDEX IDX_C47CF6EB58ABF955 ON locataire');
        $this->addSql('ALTER TABLE locataire DROP logement_id');
    }
}
