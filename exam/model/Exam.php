<?php

namespace app\exam\model;

use think\Model;
use traits\model\SoftDelete;

class Exam extends Model
{
    protected $table="goods";
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}
