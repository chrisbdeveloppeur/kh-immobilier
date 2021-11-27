<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127000046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072888AEFB514');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288D8A38199');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072888AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288D8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288D8A38199');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072888AEFB514');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288D8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072888AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON UPDATE NO ACTION ON DELETE SET NULL');
    }
}
