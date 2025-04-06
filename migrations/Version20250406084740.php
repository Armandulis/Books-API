<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406084740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created main entities (Author, Book, AlternativeName, Language) for the project';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE alternative_name (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_18FE88FDF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, date_of_birth DATE DEFAULT NULL, external_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, work_count INT NOT NULL, ratings_average DOUBLE PRECISION NOT NULL, ratings_count INT NOT NULL, ratings_count1 INT NOT NULL, ratings_count2 INT NOT NULL, ratings_count3 INT NOT NULL, ratings_count4 INT NOT NULL, ratings_count5 INT NOT NULL, want_to_read_count INT NOT NULL, already_read_count INT NOT NULL, currently_reading_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE author_book (author_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_2F0A2BEEF675F31B (author_id), INDEX IDX_2F0A2BEE16A2B381 (book_id), PRIMARY KEY(author_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, external_id VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, first_publish_year INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE book_language (book_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_CD2467EC16A2B381 (book_id), INDEX IDX_CD2467EC82F1BAF4 (language_id), PRIMARY KEY(book_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE alternative_name ADD CONSTRAINT FK_18FE88FDF675F31B FOREIGN KEY (author_id) REFERENCES author (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_book ADD CONSTRAINT FK_2F0A2BEEF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_book ADD CONSTRAINT FK_2F0A2BEE16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_language ADD CONSTRAINT FK_CD2467EC16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_language ADD CONSTRAINT FK_CD2467EC82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE alternative_name DROP FOREIGN KEY FK_18FE88FDF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_book DROP FOREIGN KEY FK_2F0A2BEEF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_book DROP FOREIGN KEY FK_2F0A2BEE16A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_language DROP FOREIGN KEY FK_CD2467EC16A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_language DROP FOREIGN KEY FK_CD2467EC82F1BAF4
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE alternative_name
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
            DROP TABLE book_language
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE language
        SQL);
    }
}
