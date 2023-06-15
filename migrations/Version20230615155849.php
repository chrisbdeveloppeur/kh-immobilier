<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615155849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien_immo_frais (bien_immo_id INT NOT NULL, frais_id INT NOT NULL, INDEX IDX_8E6DB9CD8AEFB514 (bien_immo_id), INDEX IDX_8E6DB9CDBF516DC4 (frais_id), PRIMARY KEY(bien_immo_id, frais_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien_immo_frais ADD CONSTRAINT FK_8E6DB9CD8AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bien_immo_frais ADD CONSTRAINT FK_8E6DB9CDBF516DC4 FOREIGN KEY (frais_id) REFERENCES frais (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo_frais DROP FOREIGN KEY FK_8E6DB9CD8AEFB514');
        $this->addSql('ALTER TABLE bien_immo_frais DROP FOREIGN KEY FK_8E6DB9CDBF516DC4');
        $this->addSql('DROP TABLE bien_immo_frais');
    }
}
