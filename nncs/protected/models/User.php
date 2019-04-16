<?php

class User extends CActiveRecord {

    public $confirm_password;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{users}}';
    }

    public function rules() {
        return array(
            array('email,password', 'required'),
            array('email', 'email'),
            array('display_name,email', 'unique'),
            array('display_name,email,password,confirm_password', 'safe'),
        );
    }

    public function relations() {
        return array(
            'profile' => array(self::HAS_ONE, 'UserProfile', 'user_id'),
            'access_item' => array(self::HAS_ONE, 'Permission', 'user_id'),
                //'logins' => array(self::HAS_ONE, 'UserLogin', 'user_id'),
        );
    }

    public function displayname($id = null) {
        $displayName = "";
        if ($id != null) {
            $data = User::model()->findByPk($id);
            if (!empty($data->display_name)) {
                $displayName = $data->display_name;
            } else {
                $displayName = $data->email;
            }
        } else {
            if (!empty($this->display_name)) {
                $displayName = $this->display_name;
            } else {
                $displayName = $this->email;
            }
        }

        return $displayName;
    }

    public function getCount() {
        $criteria = new CDbCriteria();
        $criteria->condition = "deletable = 1";
        return User::model()->count($criteria);
    }

    public function getList() {
        $criteria = new CDbCriteria();
        $criteria->condition = "status=1";
        $criteria->addCondition("deletable=1");
        $_dataset = User::model()->findAll($criteria);
        return $_dataset;
    }

    public function validatePassword($password) {
        return crypt($password, $this->password) === $this->password || $password === $this->password;
    }

    public function verified() {
        return $this->status == AppConstant::USER_STATUS_ACTIVE;
    }

    public function hashPassword($password) {
        return crypt($password, $this->generateSalt());
    }

    protected function generateSalt($cost = 10) {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
            throw new CException(Yii::t('Cost parameter must be between 4 and 31.'));
        }
        // Get some pseudo-random data from mt_rand().
        $rand = '';
        for ($i = 0; $i < 8; ++$i) {
            $rand.=pack('S', mt_rand(0, 0xffff));
        }
        // Add the microtime for a little more entropy.
        $rand.=microtime();
        // Mix the bits cryptographically.
        $rand = sha1($rand, true);
        // Form the prefix that specifies hash algorithm type and cost parameter.
        $salt = '$2a$' . str_pad((int) $cost, 2, '0', STR_PAD_RIGHT) . '$';
        // Append the random salt string in the required base64 format.
        $salt.=strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
        return $salt;
    }

    public function schemaInfo() {
        $exclude = ['id', 'password', 'ip', 'role', 'lastlogin', 'activation_token', 'activation_ip', 'activation_time', 'password_token', 'password_request_ip', 'password_request_time', 'password_reset_ip', 'password_reset_time', 'created', 'created_by', 'modified', 'modified_by', 'deletable', '_key'];
        $schemaInfo = Yii::app()->db->schema->getTable($this->tableName());

        foreach ($exclude as $k => $v) {
            unset($schemaInfo->columns[$v]);
        }

        return $schemaInfo;
    }

}
