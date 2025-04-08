<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408071701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, external_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE author_book (author_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_2F0A2BEEF675F31B (author_id), INDEX IDX_2F0A2BEE16A2B381 (book_id), PRIMARY KEY(author_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, external_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, first_publish_year INT DEFAULT NULL, UNIQUE INDEX UNIQ_CBE5A3319F75D7B0 (external_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE isbn (id INT AUTO_INCREMENT NOT NULL, book_id INT NOT NULL, isbn VARCHAR(255) NOT NULL, INDEX IDX_CC1CF4E616A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_book ADD CONSTRAINT FK_2F0A2BEEF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_book ADD CONSTRAINT FK_2F0A2BEE16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE isbn ADD CONSTRAINT FK_CC1CF4E616A2B381 FOREIGN KEY (book_id) REFERENCES book (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE author_book DROP FOREIGN KEY FK_2F0A2BEEF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_book DROP FOREIGN KEY FK_2F0A2BEE16A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE isbn DROP FOREIGN KEY FK_CC1CF4E616A2B381
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE author
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE author_book
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE isbn
        SQL);
    }
}
