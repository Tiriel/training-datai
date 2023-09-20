<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230920094437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Comment entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL, posted_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , content CLOB NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE comment');
    }
}
