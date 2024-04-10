<?php

namespace PhpThalla\DB2\Database\Connectors;

/**
 * Class ODBCConnector
 *
 * @package PhpThalla\DB2\Database\Connectors
 */
class ODBCConnector extends DB2Connector
{
    /**
     * @param array $config
     *
     * @return string
     */
    protected function getDsn(array $config)
    {
        $ssl = $config['ssl'];
        $sslcert = $config['sslcert'];
        
        $dsnParts = [
            'odbc:DRIVER=%s',
            'Hostname=%s',
            'Database=%s',
            'UID=%s',
            'PWD=%s',
            'port=%s',
        ];

        $dsnConfig = [
            $config['driverName'],
            $config['host'],
            $config['database'],
            $config['username'],
            $config['password'],
            $config['port']
        ];

        if (array_key_exists('odbc_keywords', $config)) {
            $odbcKeywords = $config['odbc_keywords'];
            $parts = array_map(function($part) {
                return $part . '=%s';
            }, array_keys($odbcKeywords));
            $config = array_values($odbcKeywords);

            $dsnParts = array_merge($dsnParts, $parts);
            $dsnConfig = array_merge($dsnConfig, $config);
        }

        if ($ssl == 1){
               return sprintf(implode(';', $dsnParts), ...$dsnConfig) .";SECURITY=SSL;PROTOCOL=TCPIP;SecurityTransportMode=SSL;SSLServerCertificate=$sslcert";
            }
        else{
                return sprintf(implode(';', $dsnParts), ...$dsnConfig);
            }
    }
}
