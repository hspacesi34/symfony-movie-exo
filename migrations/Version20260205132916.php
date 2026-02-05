<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205132916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name_cat VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_64C19C19A4B4B46 (name_cat), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE director (id INT AUTO_INCREMENT NOT NULL, name_director VARCHAR(50) NOT NULL, firstname_director VARCHAR(50) NOT NULL, day_of_birth DATE DEFAULT NULL, country_director VARCHAR(50) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title_movie VARCHAR(255) NOT NULL, synopsis_movie LONGTEXT NOT NULL, image_cover VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_1D5EF26FA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE movie_director (movie_id INT NOT NULL, director_id INT NOT NULL, INDEX IDX_C266487D8F93B6FC (movie_id), INDEX IDX_C266487D899FB366 (director_id), PRIMARY KEY (movie_id, director_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE movie_category (movie_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_DABA824C8F93B6FC (movie_id), INDEX IDX_DABA824C12469DE2 (category_id), PRIMARY KEY (movie_id, category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name_user VARCHAR(50) NOT NULL, firstname_user VARCHAR(50) NOT NULL, email_user VARCHAR(50) NOT NULL, password_user VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D64912A5F6CC (email_user), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE movie_director ADD CONSTRAINT FK_C266487D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_director ADD CONSTRAINT FK_C266487D899FB366 FOREIGN KEY (director_id) REFERENCES director (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_category ADD CONSTRAINT FK_DABA824C8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_category ADD CONSTRAINT FK_DABA824C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26FA76ED395');
        $this->addSql('ALTER TABLE movie_director DROP FOREIGN KEY FK_C266487D8F93B6FC');
        $this->addSql('ALTER TABLE movie_director DROP FOREIGN KEY FK_C266487D899FB366');
        $this->addSql('ALTER TABLE movie_category DROP FOREIGN KEY FK_DABA824C8F93B6FC');
        $this->addSql('ALTER TABLE movie_category DROP FOREIGN KEY FK_DABA824C12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE director');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_director');
        $this->addSql('DROP TABLE movie_category');
        $this->addSql('DROP TABLE user');
    }
}
