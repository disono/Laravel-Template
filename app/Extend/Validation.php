<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Extend;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class Validation extends Validator
{
    // custom messages
    private $_custom_messages = [
        "alpha_dash_spaces" => "The :attribute may only contain letters, spaces, and dashes.",
        "alpha_num_spaces" => "The :attribute may only contain letters, numbers, and spaces.",
        "alpha_num_dash_spaces" => "The :attribute may only contain letters, numbers, dash, and spaces.",
        "check_date" => "The :attribute is greater than the second date.",
        "current_password" => "The :attribute is not valid password.",
        "password_complex" => ":attribute must contain at least 8 alphanumeric characters, including an uppercase letter, and a special character.",
        "birthday" => "The :attribute must be a valid date.",
        "is_owner" => "The :attribute must have a valid ownership.",
        "phone_number" => "The :attribute must be valid.",
        "string_list" => "The :attribute is invalid format."
    ];

    public function __construct($translator, $data, $rules, $messages)
    {
        parent::__construct($translator, $data, $rules, $messages);
        // set custom messages
        $this->_set_custom_stuff();
    }

    /**
     * Setup any customizations etc
     *
     * @return void
     */
    protected function _set_custom_stuff()
    {
        $this->setCustomMessages($this->_custom_messages);
    }

    /**
     * Alphabets, spaces and dashes (hyphens and underscores)
     *
     * @param string $attribute
     * @param mixed $value
     * @param $parameters
     * @return bool
     */
    protected function validateAlphaDashSpaces($attribute, $value, $parameters)
    {
        return (bool)preg_match("/^[A-Za-z\s\-_]+$/", $value);
    }

    /**
     * Alphabets, numbers, and spaces
     *
     * @param string $attribute
     * @param mixed $value
     * @param $parameters
     * @return bool
     */
    protected function validateAlphaNumSpaces($attribute, $value, $parameters)
    {
        return (bool)preg_match("/^[A-Za-z0-9\s]+$/", $value);
    }

    /**
     * Allow only alphabets, numbers, and spaces
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateAlphaNumDashSpaces($attribute, $value)
    {
        return (bool)preg_match("/^[A-Za-z0-9\s-_]+$/", $value);
    }

    /**
     * Check if 1st date is not greater then 2nd
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function validateCheckDate($attribute, $value, $parameters)
    {
        if (strtotime($parameters[0]) > strtotime($parameters[1])) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Check if user current password is valid
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function validateCurrentPassword($attribute, $value, $parameters)
    {
        $user = __me() ? User::find(__me()->id) : NULL;

        if (!$user) {
            return FALSE;
        }

        if (!Hash::check($value, $user->password)) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Allow only if current password match against provided password
     * password_complex:max,min
     *
     * @param string $attribute
     * @param mixed $value
     * @param $parameters
     * @return bool
     */
    protected function validatePasswordComplex($attribute, $value, $parameters)
    {
        // alphanumeric special char string min
        $r0 = '/^(?=.*[A-Z])(?=.*[!@#$%^&*()_=+{};:,<.>-])(?=.*[0-9])(?=.*[a-z]).{' .
            ((isset($parameters[1])) ? $parameters[1] : 8) . ',' . ((isset($parameters[0])) ? $parameters[0] : 32) . '}$/';

        // uppercase
        $r1 = '/[A-Z]{1}/';

        // lowercase
        $r2 = '/[a-z]{1}/';

        // whatever you mean by 'special char'
        $r3 = '/[!@#$%^&*()_=+{};:,<.>-]{1}/';

        // numbers
        $r4 = '/[0-9]{1}/';

        $found = array();

        // alphanumeric special char string min
        if (!preg_match_all($r0, $value, $found)) {
            $this->_custom_messages['password_complex'] = 'Password must contain at least ' .
                ((isset($parameters[1])) ? $parameters[1] : 8) .
                ' alphanumeric characters, including an uppercase letter, and a special character.';
            $this->setCustomMessages($this->_custom_messages);
            return FALSE;
        }

        // upper case
        if (!preg_match_all($r1, $value, $found)) {
            $this->_custom_messages['password_complex'] = 'The :attribute must contain uppercase letter.';
            $this->setCustomMessages($this->_custom_messages);
            return FALSE;
        }

        // lower case
        if (!preg_match_all($r2, $value, $found)) {
            $this->_custom_messages['password_complex'] = 'The :attribute must contain lowercase letter.';
            $this->setCustomMessages($this->_custom_messages);
            return FALSE;
        }

        // special characters
        if (!preg_match_all($r3, $value, $found)) {
            $this->_custom_messages['password_complex'] = 'The :attribute must contain special characters.';
            $this->setCustomMessages($this->_custom_messages);
            return FALSE;
        }

        // numbers
        if (!preg_match_all($r4, $value, $found)) {
            $this->_custom_messages['password_complex'] = 'The :attribute must contain numbers.';
            $this->setCustomMessages($this->_custom_messages);
            return FALSE;
        }

        // check if password max length is valid
        if (isset($parameters[0])) {
            if (strlen($value) > $parameters[0]) {
                $this->_custom_messages['password_complex'] = 'The :attribute must have maximum length of ' . $parameters[0] . ' characters.';
                $this->setCustomMessages($this->_custom_messages);
                return FALSE;
            }
        }

        // check if password min length is valid
        if (isset($parameters[1])) {
            if (strlen($value) < $parameters[1]) {
                $this->_custom_messages['password_complex'] = 'The :attribute must have at least ' . $parameters[1] . ' characters in length.';
                $this->setCustomMessages($this->_custom_messages);
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Validate birthday (with min and max age, accept on date)
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function validateBirthday($attribute, $value, $parameters)
    {
        // check if date is valid
        $date = date('Y-m-d', strtotime($value));
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        $is_valid_date = $d && $d->format('Y-m-d') == $date;

        if (!$is_valid_date) {
            return FALSE;
        }

        // check if min age is not valid
        if (isset($parameters[0])) {
            if (countYears($value) < $parameters[0]) {
                $this->_custom_messages['birthday'] = 'The :attribute is not allowed age.';
                $this->setCustomMessages($this->_custom_messages);
                return FALSE;
            }
        }

        // check if max age is not valid
        if (isset($parameters[1])) {
            if (countYears($value) > $parameters[0]) {
                $this->_custom_messages['birthday'] = 'The :attribute is too old.';
                $this->setCustomMessages($this->_custom_messages);
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Check if resource is owned by the user
     * is_owner:table_name,column_name_owner(optional),column_name_search(optional),column_name_search_value(optional),owner_id(optional)
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function validateIsOwner($attribute, $value, $parameters)
    {
        if (!isset($parameters[0])) {
            $this->_custom_messages['is_owner'] = 'The :attribute must have a valid table name to validate.';
            $this->setCustomMessages($this->_custom_messages);
            return FALSE;
        }

        $column_name_owner = 'user_id';
        if (isset($parameters[1])) {
            if ($parameters[1] !== NULL) {
                $column_name_owner = $parameters[1];
            }
        }

        $column_name_search = $attribute;
        if (isset($parameters[2])) {
            if ($parameters[2] !== NULL) {
                $column_name_search = $parameters[2];
            }
        }

        if (isset($parameters[3])) {
            if ($parameters[3] !== NULL) {
                $value = $parameters[3];
            }
        }

        $user_id = 0;
        if (isset($parameters[4])) {
            $user_id = $parameters[4];
        } else if (__me()) {
            $user_id = __me()->id;
        }

        if ($value === NULL) {
            return TRUE;
        }

        if (!$user_id) {
            $this->_custom_messages['is_owner'] = 'The :attribute must have a valid authenticated user.';
            $this->setCustomMessages($this->_custom_messages);
            return FALSE;
        }

        if (!DB::table($parameters[0])->where($column_name_owner, $user_id)->where($column_name_search, $value)->count()) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Check if phone number is valid
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function validatePhoneNumber($attribute, $value, $parameters)
    {
        return preg_match("/^[0-9]{7,15}$/", $value);
    }

    /**
     * Validate tags e.g. tag1, tag2, tag3
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function validateStringList($attribute, $value, $parameters)
    {
        if ($value) {
            foreach (explode(',', $value) as $tag) {
                if (!preg_match("/^[a-zA-Z0-9\s]+$/", $tag)) {
                    $this->_custom_messages['tags'] = 'The :attribute must be alphabet, number and spaces only.';
                    $this->setCustomMessages($this->_custom_messages);
                    return FALSE;
                }
            }
        }

        return TRUE;
    }
}