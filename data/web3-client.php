<?php
/*
    B"H
*/
namespace JewPaltz;


class Web3Client {

    public $url = '';

    function __construct() {
        $this->url = getenv('INFURA_JSONRPC_ROOT');
    }

    public function GetTransactionByHash($hash){
        return $this->call_function('eth_getTransactionByHash', [ $hash ]);
    }

    public function call_function($function_name, $params){
        global $logger;

        $payload = [
            "id" => 0,
            "jsonrpc" => "2.0",
            "method" => $function_name,
            "params" => $params
        ];
        $logger->debug("the transaction payload", $payload);

        $response = wp_remote_post( $this->url, [ "body"=> wp_json_encode( $payload ) ]);
        //print_r($response);
        $tx = json_decode( wp_remote_retrieve_body($response), true );
        $logger->debug("the transaction response", $tx);

        if(isset($tx['error'])){
            throw new \Exception('Php-Web3Client: ' . $tx['error']['message']);
        }else{
            return $tx['result'];
        }
    }
}