<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'email', 'api_token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'passwd',
    ];

    public function schools()
    {
        $users = $this->select('school_id')->where('cpf', $this->cpf)->get();
        $school_ids = $users->map(function ($user) {
            return $user->school_id;
        });
        return $school_ids;
    }

    public function students()
    {
        return DB::table('students_users')->select(
            'students.id',
            'students.first_name',
            'students.last_name',
            'students.cpf',
            'students.email',
            'students.school_id',
            'students.photo',
            'teams.name as class_name',
            'teams.type_education as class_type'
        )->where('user_id', $this->id)->join(
            'students',
            'students_users.student_id',
            '=',
            'students.id'
        )->join(
            'teams',
            'students.team_id',
            '=',
            'teams.id'
        )->get();
    }
}
