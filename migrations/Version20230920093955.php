<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230920093955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Book entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE book (id BLOB NOT NULL --(DC2Type:uuid)
        , title VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL, isbn VARCHAR(20) NOT NULL, price INTEGER DEFAULT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , plot CLOB DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE book');
    }
}
