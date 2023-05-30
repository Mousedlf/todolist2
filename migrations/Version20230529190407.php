<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529190407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "check_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "check" (id INT NOT NULL, task_id INT DEFAULT NULL, author_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3C8EAC138DB60186 ON "check" (task_id)');
        $this->addSql('CREATE INDEX IDX_3C8EAC13F675F31B ON "check" (author_id)');
        $this->addSql('ALTER TABLE "check" ADD CONSTRAINT FK_3C8EAC138DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "check" ADD CONSTRAINT FK_3C8EAC13F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "check_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "check" DROP CONSTRAINT FK_3C8EAC138DB60186');
        $this->addSql('ALTER TABLE "check" DROP CONSTRAINT FK_3C8EAC13F675F31B');
        $this->addSql('DROP TABLE "check"');
    }
}
