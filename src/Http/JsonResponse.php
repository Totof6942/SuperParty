<?php

namespace Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class JsonResponse extends Response
{

    public function __construct($content, $statusCode = 200, array $headers = array())
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setCallbacks(array(
                'createdAt' => function($date) { return $date->format('Y-m-d H:i:s'); },
                'date'      => function($date) { return $date->format('Y-m-d H:i:s'); },
            ));
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers, $encoders);

        parent::__construct($serializer->serialize($content, 'json'), $statusCode, array_merge(array('Content-Type' => 'application/json'), $headers));
    }

}
