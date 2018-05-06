<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180505191411 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE authorization (id INTEGER NOT NULL, resource_owner_id INTEGER NOT NULL, client_id INTEGER NOT NULL, scopes CLOB NOT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7A6D8BEFD0B0AB54 ON authorization (resource_owner_id)');
        $this->addSql('CREATE INDEX IDX_7A6D8BEF19EB6921 ON authorization (client_id)');
        $this->addSql('CREATE TABLE client (id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, redirect_uris CLOB DEFAULT NULL --(DC2Type:array)
        , token_endpoint_auth_method VARCHAR(255) DEFAULT NULL, grant_types CLOB DEFAULT NULL --(DC2Type:array)
        , response_types CLOB DEFAULT NULL --(DC2Type:array)
        , client_name VARCHAR(255) DEFAULT NULL, client_uri VARCHAR(255) DEFAULT NULL, logo_uri VARCHAR(255) DEFAULT NULL, scope CLOB DEFAULT NULL --(DC2Type:array)
        , contacts CLOB DEFAULT NULL --(DC2Type:array)
        , tos_uri VARCHAR(255) DEFAULT NULL, policy_uri VARCHAR(255) DEFAULT NULL, jwks_uri VARCHAR(255) DEFAULT NULL, jwks CLOB DEFAULT NULL --(DC2Type:array)
        , software_id VARCHAR(255) DEFAULT NULL, software_version VARCHAR(255) DEFAULT NULL, application_type VARCHAR(255) DEFAULT NULL, sector_identifier_uri VARCHAR(255) DEFAULT NULL, subject_type VARCHAR(255) DEFAULT NULL, id_token_signed_response_alg VARCHAR(255) DEFAULT NULL, id_token_encrypted_response_alg VARCHAR(255) DEFAULT NULL, id_token_encrypted_response_enc VARCHAR(255) DEFAULT NULL, userinfo_encrypted_response_alg VARCHAR(255) DEFAULT NULL, userinfo_encrypted_response_enc VARCHAR(255) DEFAULT NULL, userinfo_signed_response_alg VARCHAR(255) DEFAULT NULL, request_object_signing_alg VARCHAR(255) DEFAULT NULL, request_object_encryption_alg VARCHAR(255) DEFAULT NULL, request_object_encryption_enc VARCHAR(255) DEFAULT NULL, token_endpoint_auth_signing_alg VARCHAR(255) DEFAULT NULL, default_max_age INTEGER DEFAULT NULL, require_auth_time BOOLEAN DEFAULT NULL, default_acr_values CLOB DEFAULT NULL --(DC2Type:array)
        , initiate_login_uri VARCHAR(255) DEFAULT NULL, request_uris CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE access_token (id INTEGER NOT NULL, token VARCHAR(255) NOT NULL, scopes CLOB NOT NULL --(DC2Type:array)
        , client_identifier VARCHAR(255) NOT NULL, resource_owner_identifier VARCHAR(255) DEFAULT NULL, expires_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, authorization_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE authorization_code (id INTEGER NOT NULL, code VARCHAR(255) NOT NULL, scopes CLOB NOT NULL --(DC2Type:array)
        , client_identifier VARCHAR(255) NOT NULL, resource_owner_identifier VARCHAR(255) NOT NULL, requested_scopes CLOB DEFAULT NULL --(DC2Type:array)
        , redirect_uri VARCHAR(255) DEFAULT NULL, expires_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE refresh_token (id INTEGER NOT NULL, token VARCHAR(255) NOT NULL, scopes CLOB NOT NULL --(DC2Type:array)
        , client_identifier VARCHAR(255) NOT NULL, resource_owner_identifier VARCHAR(255) DEFAULT NULL, expires_at_date DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INTEGER NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE authorization');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE access_token');
        $this->addSql('DROP TABLE authorization_code');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE users');
    }
}
