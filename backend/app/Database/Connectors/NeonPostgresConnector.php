<?php

namespace App\Database\Connectors;

use Illuminate\Database\Connectors\PostgresConnector;

class NeonPostgresConnector extends PostgresConnector
{
    /**
     * Create a DSN from configuration, appending Neon endpoint option if provided.
     */
    protected function createDsn(array $config)
    {
        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $sslmode = $config['sslmode'] ?? 'require';
        $endpointId = $config['endpoint_id'] ?? null;

        $dsn = "pgsql:host={$host};port={$port};dbname='{$database}';sslmode={$sslmode}";

        if ($endpointId) {
            // Append options parameter directly in DSN so libpq without SNI works
            $dsn .= ";options=endpoint={$endpointId}";
        }

        return $dsn;
    }
}
