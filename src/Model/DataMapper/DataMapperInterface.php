<?php

namespace Model\DataMapper;

interface DataMapperInterface
{

    /**
     * @param Object $object
     */
    public function persist($object);

    /**
     * @param Object $object
     */
    public function remove($object);

}
