<?php

use GuzzleHttp\Client;
require_once Soccerama_dir . 'Exceptions/ApiRequestException.php';

class SocceramaClient {
    /* @var $client Client */

    protected $client;
    protected $apiToken;
    protected $withoutData;
    protected $include = [ ];

    public function __construct ( $apiToken , $include = [ ] , $withoutData = false ) {
        $options = [
            'base_uri' => 'https://soccer.sportmonks.com/api/v2.0/' ,
            'verify' => false ,
            // 'verify' => ENVIRONMENT === 'testing' ? false : true ,
        ];
        $this->client = new Client($options);

        $this->apiToken = $apiToken;
        if ( empty($this->apiToken) ) {
            throw new \InvalidArgumentException('No API token set');
        }
        if ( count($include) )
            $this->setInclude($include);
        $this->withoutData = $withoutData;
    }

    protected function call ( $url , $hasData = false ) {
        $query = ['api_token' => $this->apiToken ];
        if ( count($this->include) ) {
            $query['include'] = $this->include;
        }

        $response = $this->client->get($url , ['query' => $query ]);
        $body = json_decode($response->getBody()->getContents());

        if ( property_exists($body , 'error') ) {
            if ( is_object($body->error) ) {
                throw new ApiRequestException($body->error->message , $body->error->code);
            }
            else {
                throw new ApiRequestException($body->error , 500);
            }

            return $response;
        }

        if ( $hasData && $this->withoutData ) {
            return $body->data;
        }

        return $body;
    }

    protected function callData ( $url ) {
        return $this->call($url , true);
    }

    /**
     * @param $include - string or array of relations to include with the query
     */
    public function setInclude ( $include ) {
        if ( is_array($include) ) {
            $include = implode(',' , $include);
        }
        $this->include = $include;

        return $this;
    }

}
