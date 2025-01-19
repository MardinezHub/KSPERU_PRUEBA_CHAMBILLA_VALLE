<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118024047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__matricula AS SELECT id, fecha_inscripcion FROM matricula');
        $this->addSql('DROP TABLE matricula');
        $this->addSql('CREATE TABLE matricula (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id INTEGER NOT NULL, curso_id INTEGER NOT NULL, fecha_inscripcion DATETIME NOT NULL, CONSTRAINT FK_15DF1885DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_15DF188587CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO matricula (id, fecha_inscripcion) SELECT id, fecha_inscripcion FROM __temp__matricula');
        $this->addSql('DROP TABLE __temp__matricula');
        $this->addSql('CREATE INDEX IDX_15DF1885DB38439E ON matricula (usuario_id)');
        $this->addSql('CREATE INDEX IDX_15DF188587CB4A1F ON matricula (curso_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__usuario AS SELECT id, nombre, apellido_paterno, apellido_materno, dni, contrasenia FROM usuario');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('CREATE TABLE usuario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, rol_id INTEGER NOT NULL, nombre VARCHAR(100) NOT NULL, apellido_paterno VARCHAR(100) NOT NULL, apellido_materno VARCHAR(100) NOT NULL, dni VARCHAR(8) NOT NULL, contrasenia VARCHAR(100) NOT NULL, CONSTRAINT FK_2265B05D4BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO usuario (id, nombre, apellido_paterno, apellido_materno, dni, contrasenia) SELECT id, nombre, apellido_paterno, apellido_materno, dni, contrasenia FROM __temp__usuario');
        $this->addSql('DROP TABLE __temp__usuario');
        $this->addSql('CREATE INDEX IDX_2265B05D4BAB96C ON usuario (rol_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__matricula AS SELECT id, fecha_inscripcion FROM matricula');
        $this->addSql('DROP TABLE matricula');
        $this->addSql('CREATE TABLE matricula (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fecha_inscripcion DATETIME NOT NULL)');
        $this->addSql('INSERT INTO matricula (id, fecha_inscripcion) SELECT id, fecha_inscripcion FROM __temp__matricula');
        $this->addSql('DROP TABLE __temp__matricula');
        $this->addSql('CREATE TEMPORARY TABLE __temp__usuario AS SELECT id, nombre, apellido_paterno, apellido_materno, dni, contrasenia FROM usuario');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('CREATE TABLE usuario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, apellido_paterno VARCHAR(100) NOT NULL, apellido_materno VARCHAR(100) NOT NULL, dni VARCHAR(8) NOT NULL, contrasenia VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO usuario (id, nombre, apellido_paterno, apellido_materno, dni, contrasenia) SELECT id, nombre, apellido_paterno, apellido_materno, dni, contrasenia FROM __temp__usuario');
        $this->addSql('DROP TABLE __temp__usuario');
    }
}
