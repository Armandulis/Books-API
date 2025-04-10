<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410082325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_author_name ON author (name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_book_title ON book (title)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_isbn_isbn ON isbn (isbn)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX idx_author_name ON author
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_book_title ON book
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_isbn_isbn ON isbn
        SQL);
    }
}
