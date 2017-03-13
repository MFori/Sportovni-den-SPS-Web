<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 23.12.2016
 * Time: 22:32
 */

namespace RestBundle\Model;

use Symfony\Component\HttpFoundation\JsonResponse;

class RestResponse extends JsonResponse
{
    public function __construct($data = array(), $httpStatus = 200, $errors = array())
    {
        $data = $this->dataToArray($data);
        $errors = $this->dataToArray($errors);

        parent::__construct(array(
            'data' => $data,
            //'status' => $httpStatus,
            'errors' => $errors
        ), $httpStatus);
    }

    public function setData($data = array())
    {
        return parent::setData($this->dataToArray($data));
    }

    /**
     * Convert objects to data
     *
     * @param $data array
     * @return array
     */
    private function dataToArray($data)
    {
        foreach ($data as $k => $item) {
            if($item instanceof RestSerializable) {
                $data[$k] = $this->dataToArray($item->restSerialize());
            } elseif(is_array($item)) {
                $data[$k] = $this->dataToArray($item);
            }
        }

        return $data;
    }

}