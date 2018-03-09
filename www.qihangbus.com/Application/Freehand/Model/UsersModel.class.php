<?php
namespace freehand\Model;
use Think\Model;
class UsersModel extends Model{
    //自动验证
    protected $_validate = array(
        array('username','require','用户名不能为空'),
        array('password','require','密码不能为空'),
    );
}