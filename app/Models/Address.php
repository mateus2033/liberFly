<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'street',
        'number',
        'city',
        'cep',
        'user_id'
    ];

    private int $id;
    private string $street;
    private string $number;
    private string $city;
    private string $cep;
    private int $user_id;


    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Get the value of street
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Set the value of street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * Get the value of number
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Set the value of number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * Get the value of city
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Set the value of city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * Get the value of cep
     */
    public function getCep(): string
    {
        return $this->cep;
    }

    /**
     * Set the value of cep
     */
    public function setCep($cep): void
    {
        $this->cep = $cep;
    }

    /**
     * Get the value of user_id
     */
    public function getUser_id(): string
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     */
    public function setUser_id(int $user_id): void
    {
        $this->user_id = $user_id;
    }
}
