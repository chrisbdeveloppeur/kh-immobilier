<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615155351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_frais DROP FOREIGN KEY FK_7AC559EBAD26311');
        $this->addSql('ALTER TABLE tag_frais DROP FOREIGN KEY FK_7AC559EBF516DC4');
        $this->addSql('DROP TABLE tag_frais');
        $this->addSql('ALTER TABLE frais DROP FOREIGN KEY FK_25404C988AEFB514');
        $this->addSql('DROP INDEX IDX_25404C988AEFB514 ON frais');
        $this->addSql('ALTER TABLE frais DROP title, CHANGE quantity quantity NUMERIC(10, 0) DEFAULT NULL, CHANGE bien_immo_id tag_id INT NOT NULL');
        $this->addSql('ALTER TABLE frais ADD CONSTRAINT FK_25404C98BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('CREATE INDEX IDX_25404C98BAD26311 ON frais (tag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_frais (tag_id INT NOT NULL, frais_id INT NOT NULL, INDEX IDX_7AC559EBF516DC4 (frais_id), INDEX IDX_7AC559EBAD26311 (tag_id), PRIMARY KEY(tag_id, frais_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tag_frais ADD CONSTRAINT FK_7AC559EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_frais ADD CONSTRAINT FK_7AC559EBF516DC4 FOREIGN KEY (frais_id) REFERENCES frais (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE frais DROP FOREIGN KEY FK_25404C98BAD26311');
        $this->addSql('DROP INDEX IDX_25404C98BAD26311 ON frais');
        $this->addSql('ALTER TABLE frais ADD title VARCHAR(255) NOT NULL, CHANGE quantity quantity INT DEFAULT NULL, CHANGE tag_id bien_immo_id INT NOT NULL');
        $this->addSql('ALTER TABLE frais ADD CONSTRAINT FK_25404C988AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('CREATE INDEX IDX_25404C988AEFB514 ON frais (bien_immo_id)');
    }
}
