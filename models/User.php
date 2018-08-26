<?php
/**
 * @link http://mqc.lcbint.com
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * 用户模型，用于登录、个人更改自已资料以及权限验证
 * @author ZDC
 * @since 1.0
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $id;
    public $username;
    public $realname;
    public $password;
    public $authKey;
    public $accessToken;
    public $enabled;
    public $updated_at;
    public $created_at;

    //登录时记住我和验证码
    public $rememberMe = false;
    public $verifyCode;

    //用户更改资料时使用
    public $newPassword;
    public $verifyNewPassword;

    public static function tableName()
    {
        return 'user';
    }

    public function attributes()
    {
        return [
            'username' => '用户名',
            'realname' => '姓名',
            'password' => '密码',
            'newPassword' => '新密码',
            'verifyNewPassword' => '确认新密码'
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
            {
                $this->auth_key = \Yii::$app->security->generateRandomString();//自动添加随机auth_key
                $this->password = \Yii::$app->security->generatePasswordHash($this->password);//密码进行加密
                $this->created_at = time();
                $this->updated_at = time();
                $this->enabled = 1;
            }
            return true;
        }
        return false;
    }

    public function rules()
    {
        return [
            //通用场景
            [['username','password','newPassword','verifyNewPassword'],'trim'], //去两端空格

            //登录场景
            [['username','password'],'required','on' => 'login'], //必填项
            ['verifyCode','captcha','on' => 'login'], //验证码
            ['password','validatePassword','on' => 'login'], //调用validatePassword验证密码
            ['username','string','length' => [2,20],'on' => 'login'], //长度验证,
            ['password','string','length' => [4,12],'on' => 'login'],
            ['rememberMe','boolean','on' => 'login'],

            //修改资料
            [['username','realname','password','newPassword','verifyNewPassword'],'required','on' => 'editProfile'], //必填项
            ['username','string','length' => [2,20], 'on' => 'editProfile'],
            ['realname','string','length' => [4,20], 'on' => 'editProfile'],
            [['password','newPassword','verifyNewPassword'],'string','length' => [4,12], 'on' => 'editProfile'],
            ['verifyNewPassword','compare', 'compareAttribute' => 'newPassword','message' => '请重复输入新密码','on' => 'editProfile'], //newPassword 与 verifyNewPassword 是否相同
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['login'] = ['username','password','rememberMe','verifyCode'];
        $scenarios['editProfile'] = ['password','newPassword','verifyNewPassword'];
        return $scenarios;
    }

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'auth_key' => 'test100key',
            'access_token' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'auth_key' => 'test101key',
            'access_token' => '101-token',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * 密码验证
     */
    public function validatePassword($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $user = static::findByUsername($this->username);
            if(!$user || !\Yii::$app->security->validatePassword($this->password,$user->password))
            {
                $this->addError($attribute,'用户名或者密码错误');
            }

            if($user && $user->enabled === false)
            {
                $this->addError($attribute,'账户已经被禁用');
            }
        }
    }

    /**
     * 登录
     */
    public function login()
    {
        if($this->validate())
        {
            return \Yii::$app->user->login(static::findByUsername($this->username),$this->rememberMe? 3600*24*30 : 0);
        }else{
            return false;
        }
    }

    public function editProfile($id)
    {
        $user = User::findIdentity($id);
        if($user){
            if($this->validate()){
                if(\Yii::$app->security->validatePassword($this->password,$user->password)){
                    if($user->save()){
                        return true;
                    }else{
                        $this->addError('username','更新数据出错');
                        return false;
                    }
                }else{
                    $this->addError('password','旧密码错误');
                    return false;
                }
            }else{
                return false;
            }
        }else {
            $this->addError('username','用户不存在');
            return false;
        }
    }
}
