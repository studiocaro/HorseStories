<?php
namespace HorseStories\Models\Horses;

use DateTime;
use HorseStories\Models\Disciplines\Discipline;
use HorseStories\Models\Palmares\Palmares;
use HorseStories\Models\Pedigrees\Pedigree;
use HorseStories\Models\Pictures\Picture;
use HorseStories\Models\Statuses\Status;
use HorseStories\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    const FEMALE = 2;

    /**
     * The table name used by the entity
     *
     * @var string
     */
    protected $table = 'horses';

    /**
     * The fillable fields in the database
     *
     * @var array
     */
    protected $fillable = [
        'name', 'gender', 'breed', 'height', 'date_of_birth', 'color', 'life_number', 'user_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return bool
     */
    public function hasOwner()
    {
        return $this->user_id !== null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'horse_id', 'user_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function palmares()
    {
        return $this->hasMany(Palmares::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pedigree()
    {
        return $this->hasMany(Pedigree::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function family()
    {
        return $this->hasManyThrough(Horse::class, Pedigree::class, 'family_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disciplines()
    {
        return $this->hasMany(Discipline::class);
    }

    /**
     * @return \HorseStories\Models\Horses\Horse
     */
    public function father()
    {
        $pedigree =  $this->pedigree->filter(function ($family) {
            return $family->type == 1;
        })->first();

        return $pedigree ? $pedigree->originalHorse : null;
    }

    /**
     * @return bool
     */
    public function hasFather()
    {
        return $this->father() !== null;
    }

    /**
     * @return \HorseStories\Models\Horses\Horse
     */
    public function fathersFather()
    {
        return $this->father()->father();
    }

    /**
     * @return \HorseStories\Models\Horses\Horse
     */
    public function fathersMother()
    {
        return $this->father()->mother();
    }

    /**
     * @return \HorseStories\Models\Horses\Horse
     */
    public function mother()
    {
        $pedigree =  $this->pedigree->filter(function ($family) {
            return $family->type == 2;
        })->first();

        return $pedigree ? $pedigree->originalHorse : null;
    }

    /**
     * @return bool
     */
    public function hasMother()
    {
        return $this->mother() !== null;
    }

    /**
     * @return \HorseStories\Models\Horses\Horse
     */
    public function mothersFather()
    {
        return $this->mother()->father();
    }

    /**
     * @return \HorseStories\Models\Horses\Horse
     */
    public function mothersMother()
    {
        return $this->mother()->mother();
    }

    /**
     * @return \HorseStories\Models\Pictures\Picture
     */
    public function getProfilePicture()
    {
        return $this->pictures->filter(function ($picture) {
            return $picture->profile_pic == true;
        })->first();
    }

    /**
     * @return DateTime
     */
    public function getBirthDay()
    {
        $result = new DateTime($this->date_of_birth);

        return $result->format('d/m/Y');
    }

    /**
     * @return \HorseStories\Models\Pedigrees\Pedigree
     */
    public function sons()
    {
        return $this->pedigree->filter(function($family) {
            return $family->type == 3;
        })->all();
    }

    /**
     * @return \HorseStories\Models\Pedigrees\Pedigree
     */
    public function Daughters()
    {
        return $this->pedigree->filter(function($family) {
            return $family->type == 4;
        })->all();
    }

    /**
     * @param int $disciplineId
     * @return bool
     */
    public function performsDiscipline($disciplineId)
    {
        return $this->disciplines->filter(function($discipline) use ($disciplineId) {
           return $discipline->discipline === $disciplineId;
        })->first();
    }

    /**
     * @return bool
     */
    public function isFemale()
    {
        return $this->gender == self::FEMALE;
    }
}
