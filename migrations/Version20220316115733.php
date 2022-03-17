<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316115733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competencia DROP FOREIGN KEY FK_842C498A7E735794');
        $this->addSql('DROP INDEX IDX_842C498A7E735794 ON competencia');
        $this->addSql('ALTER TABLE competencia CHANGE categoria_id_id categoria_id INT NOT NULL');
        $this->addSql('ALTER TABLE competencia ADD CONSTRAINT FK_842C498A3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('CREATE INDEX IDX_842C498A3397707A ON competencia (categoria_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competencia DROP FOREIGN KEY FK_842C498A3397707A');
        $this->addSql('DROP INDEX IDX_842C498A3397707A ON competencia');
        $this->addSql('ALTER TABLE competencia CHANGE categoria_id categoria_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE competencia ADD CONSTRAINT FK_842C498A7E735794 FOREIGN KEY (categoria_id_id) REFERENCES categoria (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_842C498A7E735794 ON competencia (categoria_id_id)');
    }
}
