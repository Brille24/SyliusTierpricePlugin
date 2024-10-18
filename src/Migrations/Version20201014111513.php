<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201014111513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Migration adaptée pour PostgreSQL
        $this->addSql('CREATE TABLE brille24_tierprice (
            id SERIAL NOT NULL,
            channel_id INT DEFAULT NULL,
            product_variant_id INT DEFAULT NULL,
            customer_group_id INT DEFAULT NULL,
            price INT NOT NULL,
            qty INT NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE INDEX IDX_BA5254F872F5A1AA ON brille24_tierprice (channel_id)');
        $this->addSql('CREATE INDEX IDX_BA5254F8A80EF684 ON brille24_tierprice (product_variant_id)');
        $this->addSql('CREATE INDEX IDX_BA5254F8D2919A68 ON brille24_tierprice (customer_group_id)');
        $this->addSql('CREATE UNIQUE INDEX no_duplicate_prices ON brille24_tierprice (qty, channel_id, product_variant_id, customer_group_id)');

        $this->addSql('ALTER TABLE brille24_tierprice ADD CONSTRAINT FK_BA5254F872F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE brille24_tierprice ADD CONSTRAINT FK_BA5254F8A80EF684 FOREIGN KEY (product_variant_id) REFERENCES sylius_product_variant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE brille24_tierprice ADD CONSTRAINT FK_BA5254F8D2919A68 FOREIGN KEY (customer_group_id) REFERENCES sylius_customer_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // Revert the changes
        $this->addSql('DROP TABLE brille24_tierprice');
    }
}
