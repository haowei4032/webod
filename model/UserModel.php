<?php

namespace model;

class UserModel extends \Model
{
    protected $tableName = 'wd_user';

    public function getPosts()
    {
        return $this->hasMany(PostsModel::class, 'user_id', 'user_id');
    }
}