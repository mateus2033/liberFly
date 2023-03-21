<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Book",
 *     description="Book model",
 *     @OA\Xml(
 *         name="Book"
 *     )
 * )
 */
class Book extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $fillable = [
        'id',
        'name',
        'author',
        'edition',
        'publishing_company',
        'user_id'
    ];

    private string $id;
    private string $name;
    private string $author;
    private string $edition;
    private string $publishing_company;
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
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Get the value of author
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set the value of author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * Get the value of Edtion
     */
    public function getEdition(): string
    {
        return $this->edition;
    }

    /**
     * Set the value of author
     */
    public function setEdition($edition): void
    {
        $this->edition = $edition;
    }

    /**
     * Get the value of publishingCompany
     */
    public function getPublishingCompany(): string
    {
        return $this->publishing_company;
    }

    /**
     * Set the value of publishingCompany
     */
    public function setPublishingCompany($publishing_company)
    {
        $this->publishing_company = $publishing_company;
    }

    /**
     * Get the value of user_id
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
