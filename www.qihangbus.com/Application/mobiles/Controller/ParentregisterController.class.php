<?php
namespace mobiles\Controller;
use Think\Controller;
class ParentregisterController extends Controller
{
    public function index()
    {
        $location = session('location');
        $region = M('region');
        if($location){
            $p_name = $location['province'];
            $p_id = $region->where(['region_name'=>$p_name])->getField('region_id');
            if($p_id){//省
                $city_list = $region->where(['parent_id'=>$p_id])->select();
                $c_name = $location['city'];
                $c_id = $region->where(['region_name'=>$c_name])->getField('region_id');
            }
            if($c_id){//市
                $zone_list = $region->where(['parent_id'=>$c_id])->select();
                $z_name = $location['zone'];
                $z_id = $region->where(['region_name'=>$z_name])->getField('region_id');
            }
            if($z_id){//区
                $school_list = M('schools')->where(['district_id'=>$z_id])->select();
            }
        }
        $province_list = $region->where(array('region_level'=>1))->select();
        $this->assign('p_name',$p_name);
        $this->assign('c_name',$c_name);
        $this->assign('z_name',$z_name);
        $this->assign('province_list',$province_list);
        $this->assign('city_list',$city_list);
        $this->assign('zone_list',$zone_list);
        $this->assign('school_list',$school_list);
        $this->display('parent_register/register');
    }

    //=============异步请求地区信息
    public function address()
    {
        $region_level = I('param.region_level',1);
        $region_id = I('param.region_id',0);
        $region_list = M('region')->where(array('region_level'=>$region_level,'parent_id'=>$region_id))->select();
        echo json_encode($region_list);
    }


    //=============获取学校 年级 班级 学生信息
    public function schools()
    {
        $school_id = I('param.region_id',0);
        $school_list = M('schools')->where(array('district_id'=>$school_id))->select();
        echo json_encode($school_list);
    }

    public function grade()
    {
        $school_id = I('param.school_id',0);
        $grade_list = M('grade')->where(array('school_id'=>$school_id))->select();
        echo json_encode($grade_list);
    }
    public function cla()
    {
        $grade_id = I('param.grade_id');
        $class_list = M('class')->where(array('grade_id'=>$grade_id))->select();
        echo json_encode($class_list);

    }

    //后台注册验证 信息
    public function register()
    {
        $s = M('students');
        $p = M('students_parent');


        $mobile = I('mobile');
        $code = I('code');
        $cache = S($mobile);
        //判断手机号是否已注册
        $access = M('students_parent')->where(array('parent_mobile'=>$mobile))->find()
        || $access = M('teacher')->where(array('teacher_mobile'=>$mobile))->find()
        || $access = M('schools')->where(array('leader_mobile'=>$mobile))->find()
        || $access = M('schools')->where(array('teacher_mobile'=>$mobile))->find()
        || $access = M('admins')->where(array('admin_mobile'=>$mobile))->find();
        if($access){
            echo 5;
            exit();
        }

        if ($code == $cache) {       //验证码是否正确
            $student = array(
                "school_id" => I('school'),
                "grade_id" => I('grade'),
                "class_id" => I('cla'),
                "student_name" => I('student')
            );
            $stu_name = $s->where($student)->getField('student_id');
            if (empty($stu_name)){
                echo 3;
            }else{
                $parent = array(
                    "student_id"=>$stu_name,
                    "parent_mobile"=>$mobile,
                    "pwd" => md5('123456'),
                    "parent_name" => '家长',
                    "school_id" => $student['school_id'],
                    "grade_id" => $student['grade_id'],
                    "class_id" => $student['class_id']
                );
                $openid = session('openid');
                if($openid){
                    $parent['wx_id'] = $openid;
                }
                $result = $p->where(['student_id'=>$stu_name])->find();
                if(!$result){
                    $parent['flag'] = 1;
                }
                $p->data($parent)->add();
                echo 1;
            }
        }else{
            echo 4;
        }
    }

    public function login()
    {
        $mobile = I('get.mobile',0);
        $res = M('config')
            ->where(['parent_id' => 13])
            ->field('code,value')
            ->select();
        $weixin_options = ['appid', 'appsecret', 'token'];
        foreach ($res as $key => $value) {
            if (in_array($value['code'], $weixin_options)) {
                $options[$value['code']] = $value['value'];
            }
        }

        $weObj = new \Vendor\Weixin\Wechat($options);

        if ($_GET['code']) {
            $json = $weObj->getOauthAccessToken();
            if ($json['openid']) {
                $wxid = $json['openid'];
                if (strlen($wxid) > 28) {
                    $wxid = substr($wxid, 0, 28);
                }
                $info = M('weixin_user')->where(array('fake_id' => $wxid))->find();
                    M('students_parent')
                    ->where(array('parent_mobile' => $mobile))
                    ->save(['parent_avatar' => $info['headimgurl'], 'wx_id' => $wxid]);
                $this->redirect('Parentlogin/select_manage',['fack_id'=>$wxid]);
            }
        }

        $url_base = 'http://' . $_SERVER['HTTP_HOST'] . "/mobile.php/Parentregister/login/";
        $url = $weObj->getOauthRedirect($url_base, 1, 'snsapi_base');
        header("Location:$url");
        exit;
    }

    public function getLocation()
    {
        if(session('location')){
            $this->redirect('index');
        }else{
            $wechat = new \Org\Util\Wechat();
            $signPackage = $wechat->getShareSignPackage();
            $this->assign('signPackage',$signPackage);
            $this->display('parent_register/getLocation');
        }
    }

    public function setLocation()
    {
        $latitude = I('post.latitude');
        $longitude = I('post.longitude');
        if($latitude){
            session('location',"$longitude,$latitude");
            $location = $latitude.','.$longitude;
            $addressComponent = file_get_contents("http://api.map.baidu.com/geocoder/v2/?location=$location&output=json&pois=0&ak=MGRwFurXq9RsHR1l9nyio49Z0j1lci8f");
            $addressComponent = json_decode($addressComponent,true);
            $province = $addressComponent['result']['addressComponent']['province'];
            $city = $addressComponent['result']['addressComponent']['city'];
            $zone = $addressComponent['result']['addressComponent']['district'];
            session('location',['province'=>$province,'city'=>$city,'zone'=>$zone]);
        }
    }

    //发送验证码
    public function sendSMS($mobile)
    {
        $sendNums = S('a'.$mobile) ? S('a'.$mobile) : 0;
        if($sendNums > 3){
            $data = array(
                'status' => 0,
                'msg' => '发送已超4次,请使用最后一个验证码',
            );
            echo json_encode($data,true);
            exit();
        }
        $smsbao = C('smsbao');
        $url = $smsbao['url'];  //短信网关
        $username = $smsbao['username'];	//短信平台帐号
        $password = md5($smsbao['password']);	//短信平台密码
        //获取手机号是否存在表中?
        $parent_mobile = M('students_parent')->where(array('parent_mobile'=>$mobile))->find()
            || $teacher_mobile = M('teacher')->where(array('teacher_mobile'=>$mobile))->find()
                || $leader_mobile = M('schools')->where(array('leader_mobile'=>$mobile))->find()
                    || $school_mobile = M('schools')->where(array('teacher_mobile'=>$mobile))->find()
                        || $admins_mobile = M('admins')->where(array('admin_mobile'=>$mobile))->find();
        if (!$parent_mobile && !$teacher_mobile && !$leader_mobile && !$school_mobile && !$admins_mobile) {
            $smscode = rand(1000,9999);	//随机生成四位验证码
            $smscode = str_replace('1989', '1 9 8 9', $smscode);			//过滤黑字典
            $smscode = str_replace('1259', '1 2 5 9', $smscode);
            S($mobile, $smscode, 1800);
            S('a'.$mobile, $sendNums+1, 86400);
            $content = "【启航巴士】您的验证码为{$smscode}，在20分钟内有效。";	//要发送的短信内容
            $sendurl = $url."sms?u=".$username."&p=".$password."&m=".$mobile."&c=".urlencode($content);
            $result =file_get_contents($sendurl) ;
            if(intval($result) == 0){
                $data = array(
                    'status' => 1,
                    'msg' => '发送成功',
                );
            }else{
                $data = array(
                    'status' => 0,
                    'msg' => '发送失败',
                );
            }
            echo json_encode($data,true);
        }else{
            $data = array(
                'status' => 0,
                'msg' => '该手机号已注册',
            );
            echo json_encode($data,true);
        }
    }

    public function test()
    {
        session('location',null);
    }


}