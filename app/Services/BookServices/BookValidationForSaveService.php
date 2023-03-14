<?php
namespace App\Services\BookServices;

use App\Models\Book;
use App\Utils\ConstantMessage\ConstantMessage;

class BookValidationForSaveService extends Book {

    protected bool $isValid;
    public array $message;

    public function validateFormBook(array $data)
    {
        $this->validateUser($data);
        if ($this->isValid == true) {
            return $this->mountBook();
        }
        return $this;
    }

    private function mountBook(): array
    {
        return [
            'name' => $this->getName(),
            'author' => $this->getAuthor(),
            'edition' => $this->getEdition(),
            'publishing_company' => $this->getPublishingCompany()
        ];
    }

    private function validateUser(array $data)
    {
        $count = 0;
        $array = [];
        $error = [];

        $array['name']    = $this->_name($data);
        $array['author']  = $this->_author($data);
        $array['edition'] = $this->_edition($data);
        $array['publishing_company'] = $this->_publishing_company($data);

        foreach ($array as $key => $value) {
            if (!is_null($value)) {
                $error[$key] = $value;
                $count++;
            }
        }

        if ($count > 0) {
            $this->isValid = false;
            $this->message = $error;
        } else {
            $this->isValid = true;
            $this->message = $array;
        }
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function _name(array $data)
    {
        if (!isset($data['name']))
        {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['name']))
        {
            return ConstantMessage::ONLY_STRING;
        }

        $this->setName($data['name']);
        return null;
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function _author(array $data)
    {
        if (!isset($data['author']))
        {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['author']))
        {
            return ConstantMessage::ONLY_STRING;
        }

        $this->setAuthor($data['author']);
        return null;
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function _edition(array $data)
    {
        if (!isset($data['edition']))
        {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['edition']))
        {
            return ConstantMessage::ONLY_STRING;
        }

        $this->setEdition($data['edition']);
        return null;
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function _user_id(array $data)
    {
        $this->setUserId(null);
        return null;
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function _publishing_company(array $data)
    {
        if (!isset($data['publishing_company']))
        {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['publishing_company']))
        {
            return ConstantMessage::ONLY_STRING;
        }

        $this->setPublishingCompany($data['publishing_company']);
        return null;
    }
}
