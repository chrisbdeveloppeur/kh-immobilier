<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028142708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quittance DROP FOREIGN KEY FK_D57587DDD8A38199');
        $this->addSql('ALTER TABLE quittance ADD CONSTRAINT FK_D57587DDD8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quittance DROP FOREIGN KEY FK_D57587DDD8A38199');
        $this->addSql('ALTER TABLE quittance ADD CONSTRAINT FK_D57587DDD8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
