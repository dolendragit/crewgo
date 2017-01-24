<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . "third_party/json-schema-validator/vendor/autoload.php";

class My_jsonschema_validator {

    protected $key = '';
    public $error = NULL;

    function __construct($config = array()) {
//        $this->key = $config['key'];
    }

    public function validate($schema, $data) {        
        // Get the schema and data as objects
        $retriever = new JsonSchema\Uri\UriRetriever;
        $schema = $retriever->retrieve($schema);
        $data = json_decode($data);
        
        // If you use $ref or if you are unsure, resolve those references here
        // This modifies the $schema object
        //$refResolver = new JsonSchema\RefResolver($retriever);
        //$refResolver->resolve($schema, 'file://' . __DIR__);
        // Validate
        $validator = new JsonSchema\Validator();
        $validator->check($data, $schema);

        if ($validator->isValid()) {
            return TRUE;
        } else {
            $this->error = "JSON does not validate. Violations:\n";
            foreach ($validator->getErrors() as $error) {
                $this->error .= sprintf("[%s] %s\n", $error['property'], $error['message']);
            }
            return FALSE;
        }
        
    }

}
