<?php
declare (strict_types=1);

namespace Kernel\Validator;

use Kernel\Container\Di;
use SplFileObject;

class Validator
{

    protected static array $type = [];

    protected array $alias = [
        '>' => 'gt', '>=' => 'egt', '<' => 'lt', '<=' => 'elt', '=' => 'eq', 'same' => 'eq',
    ];

    protected array $rule = [];

    protected array $message = [];

    protected array $field = [];

    protected static array $typeMsg = [
        'require' => ':attribute不能为空',
        'number' => ':attribute必须是数字',
        'integer' => ':attribute必须是整数',
        'float' => ':attribute必须是浮点数',
        'boolean' => ':attribute必须是布尔值',
        'email' => ':attribute格式不符',
        'array' => ':attribute必须是数组',
        'accepted' => ':attribute必须是yes、on或者1',
        'date' => ':attribute格式不符合',
        'file' => ':attribute不是有效的上传文件',
        'image' => ':attribute不是有效的图像文件',
        'alpha' => ':attribute只能是字母',
        'alphaNum' => ':attribute只能是字母和数字',
        'alphaDash' => ':attribute只能是字母、数字和下划线_及破折号-',
        'activeUrl' => ':attribute不是有效的域名或者IP',
        'chs' => ':attribute只能是汉字',
        'chsAlpha' => ':attribute只能是汉字、字母',
        'chsAlphaNum' => ':attribute只能是汉字、字母和数字',
        'chsDash' => ':attribute只能是汉字、字母、数字和下划线_及破折号-',
        'url' => ':attribute不是有效的URL地址',
        'ip' => ':attribute不是有效的IP地址',
        'dateFormat' => ':attribute必须使用日期格式 :rule',
        'in' => ':attribute必须在 :rule 范围内',
        'notIn' => ':attribute不能在 :rule 范围内',
        'between' => ':attribute只能在 :1 - :2 之间',
        'notBetween' => ':attribute不能在 :1 - :2 之间',
        'length' => ':attribute长度不符合要求 :rule',
        'max' => ':attribute长度不能超过 :rule',
        'min' => ':attribute长度不能小于 :rule',
        'after' => ':attribute日期不能小于 :rule',
        'before' => ':attribute日期不能超过 :rule',
        'expire' => '不在有效期内 :rule',
        'allowIp' => '不允许的IP访问',
        'denyIp' => '禁止的IP访问',
        'confirm' => ':attribute和确认字段:2不一致',
        'different' => ':attribute和比较字段:2不能相同',
        'egt' => ':attribute必须大于等于 :rule',
        'gt' => ':attribute必须大于 :rule',
        'elt' => ':attribute必须小于等于 :rule',
        'lt' => ':attribute必须小于 :rule',
        'eq' => ':attribute必须等于 :rule',
        'regex' => ':attribute不符合指定规则',
        'method' => '无效的请求类型',
        'fileSize' => '上传文件大小不符',
        'fileExt' => '上传文件后缀不符',
        'fileMime' => '上传文件类型不符',
        'password' => ':attribute应为字母、数字、特殊符号(~!@#$%^&*()_.)，两种及以上组合，8-26位字符串',
        'notZero' => ':attribute必须大于0'
    ];

    protected ?array $currentScene = null;

    protected array $regex = [
        'alpha' => '/^[A-Za-z]+$/',
        'alphaNum' => '/^[A-Za-z0-9]+$/',
        'alphaDash' => '/^[A-Za-z0-9\-\_]+$/',
        'chs' => '/^[\x{4e00}-\x{9fa5}]+$/u',
        'chsAlpha' => '/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u',
        'chsAlphaNum' => '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u',
        'chsDash' => '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-]+$/u',
        'mobile' => '/^1[3-9][0-9]\d{8}$/',
        'idCard' => '/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$)/',
        'zip' => '/\d{6}/',
    ];

    protected array $filter = [
        
        'ip' => [FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6],
        'integer' => FILTER_VALIDATE_INT,
        'url' => FILTER_VALIDATE_URL,
        'macAddr' => FILTER_VALIDATE_MAC,
        'float' => FILTER_VALIDATE_FLOAT,
        'domain' => [FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME]
    ];

    protected array $scene = [];

    protected mixed $error = null;

    protected bool $batch = false;

    protected array $only = [];

    protected array $remove = [];

    protected array $append = [];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->rule = $rules + $this->rule;
        $this->message = array_merge($this->message, $message);
        $this->field = array_merge($this->field, $field);
    }

    public static function make($rules = [], $message = [], $field = [])
    {
        return new self($rules, $message, $field);
    }

    public function rule($name, $rule = '')
    {
        if (is_array($name)) {
            $this->rule = $name + $this->rule;
            if (is_array($rule)) {
                $this->field = array_merge($this->field, $rule);
            }
        } else {
            $this->rule[$name] = $rule;
        }

        return $this;
    }

    public static function extend($type, $callback = null)
    {
        if (is_array($type)) {
            self::$type = array_merge(self::$type, $type);
        } else {
            self::$type[$type] = $callback;
        }
    }

    public static function setTypeMsg($type, $msg = null)
    {
        if (is_array($type)) {
            self::$typeMsg = array_merge(self::$typeMsg, $type);
        } else {
            self::$typeMsg[$type] = $msg;
        }
    }

    public function message($name, $message = '')
    {
        if (is_array($name)) {
            $this->message = array_merge($this->message, $name);
        } else {
            $this->message[$name] = $message;
        }

        return $this;
    }

    public function scene($name)
    {
        
        $this->currentScene = $name;

        return $this;
    }

    public function hasScene($name)
    {
        return isset($this->scene[$name]) || method_exists($this, 'scene' . $name);
    }

    public function batch($batch = true)
    {
        $this->batch = $batch;

        return $this;
    }

    public function only($fields)
    {
        $this->only = $fields;

        return $this;
    }

    public function remove($field, $rule = true)
    {
        if (is_array($field)) {
            foreach ($field as $key => $rule) {
                if (is_int($key)) {
                    $this->remove($rule);
                } else {
                    $this->remove($key, $rule);
                }
            }
        } else {
            if (is_string($rule)) {
                $rule = explode('|', $rule);
            }

            $this->remove[$field] = $rule;
        }

        return $this;
    }

    public function append($field, $rule = null)
    {
        if (is_array($field)) {
            foreach ($field as $key => $rule) {
                $this->append($key, $rule);
            }
        } else {
            if (is_string($rule)) {
                $rule = explode('|', $rule);
            }

            $this->append[$field] = $rule;
        }

        return $this;
    }

    public function check($data, $rules = [], $scene = '')
    {
        $this->error = [];

        if (empty($rules)) {
            
            $rules = $this->rule;
        }

        $this->getScene($scene);

        foreach ($this->append as $key => $rule) {
            if (!isset($rules[$key])) {
                $rules[$key] = $rule;
            }
        }

        foreach ($rules as $key => $rule) {
            
            if (strpos($key, '|')) {
                
                list($key, $title) = explode('|', $key);
            } else {
                $title = isset($this->field[$key]) ? $this->field[$key] : $key;
            }

            if (!empty($this->only) && !in_array($key, $this->only)) {
                continue;
            }

            $value = $this->getDataValue($data, $key);

            if ($rule instanceof \Closure) {
                
                $result = call_user_func_array($rule, [$value, $data]);
            } elseif ($rule instanceof Rule) {
                
                $result = $this->checkItem($key, $value, $rule->getRule(), $data, $rule->getTitle() ?: $title, $rule->getMsg());
            } elseif (is_array($rule) && count($rule) === 2) {
                $result = call_user_func([Di::instance()->make($rule[0]), $rule[1]], $value, $data);
            } else {
                $result = $this->checkItem($key, $value, $rule, $data, $title);
            }

            if (true !== $result) {
                
                if (!empty($this->batch)) {
                    
                    if (is_array($result)) {
                        $this->error = array_merge($this->error, $result);
                    } else {
                        $this->error[$key] = $result;
                    }
                } else {
                    $this->error = $result;
                    return false;
                }
            }
        }

        return !empty($this->error) ? false : true;
    }

    public function checkRule($value, $rules)
    {
        if ($rules instanceof \Closure) {
            return call_user_func_array($rules, [$value]);
        } elseif ($rules instanceof Rule) {
            $rules = $rules->getRule();
        } elseif (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        foreach ($rules as $key => $rule) {
            if ($rule instanceof \Closure) {
                $result = call_user_func_array($rule, [$value]);
            } else {
                
                list($type, $rule) = $this->getValidateType($key, $rule);

                $callback = isset(self::$type[$type]) ? self::$type[$type] : [$this, $type];

                $result = call_user_func_array($callback, [$value, $rule]);
            }

            if (true !== $result) {
                return $result;
            }
        }

        return true;
    }

    protected function getValidateType($key, $rule)
    {
        
        if (!is_numeric($key)) {
            return [$key, $rule, $key];
        }

        if (strpos($rule, ':')) {
            list($type, $rule) = explode(':', $rule, 2);
            if (isset($this->alias[$type])) {
                
                $type = $this->alias[$type];
            }
            $info = $type;
        } elseif (method_exists($this, $rule)) {
            $type = $rule;
            $info = $rule;
            $rule = '';
        } else {
            $type = 'is';
            $info = $rule;
        }

        return [$type, $rule, $info];
    }

    protected function checkItem($field, $value, $rules, $data, $title = '', $msg = [])
    {
        if (isset($this->remove[$field]) && true === $this->remove[$field] && empty($this->append[$field])) {
            
            return true;
        }

        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        if (isset($this->append[$field])) {
            
            $rules = array_merge($rules, $this->append[$field]);
        }

        $i = 0;
        foreach ($rules as $key => $rule) {
            if ($rule instanceof \Closure) {
                $result = call_user_func_array($rule, [$value, $data]);
                $info = is_numeric($key) ? '' : $key;
            } else {
                
                list($type, $rule, $info) = $this->getValidateType($key, $rule);

                if (isset($this->append[$field]) && in_array($info, $this->append[$field])) {

                } elseif (isset($this->remove[$field]) && in_array($info, $this->remove[$field])) {
                    
                    $i++;
                    continue;
                }

                if ('must' == $info || 0 === strpos($info, 'require') || (!is_null($value) && '' !== $value)) {
                    
                    $callback = isset(self::$type[$type]) ? self::$type[$type] : [$this, $type];
                    
                    $result = call_user_func_array($callback, [$value, $rule, $data, $field, $title]);
                } else {
                    $result = true;
                }
            }

            if (false === $result) {
                
                if (!empty($msg[$i])) {
                    $message = $msg[$i];
                } else {
                    $message = $this->getRuleMsg($field, $title, $info, $rule);
                }

                return $message;
            } elseif (true !== $result) {
                
                if (is_string($result) && false !== strpos($result, ':')) {
                    $result = str_replace(
                        [':attribute', ':rule'],
                        [$title, (string)$rule],
                        $result);
                }

                return $result;
            }
            $i++;
        }

        return $result;
    }

    public function confirm($value, $rule, $data = [], $field = '')
    {
        if ('' == $rule) {
            if (strpos($field, '_confirm')) {
                $rule = strstr($field, '_confirm', true);
            } else {
                $rule = $field . '_confirm';
            }
        }

        return $this->getDataValue($data, $rule) === $value;
    }

    public function different($value, $rule, $data = [])
    {
        return $this->getDataValue($data, $rule) != $value;
    }

    public function egt($value, $rule, $data = [])
    {
        return $value >= $this->getDataValue($data, $rule);
    }

    public function gt($value, $rule, $data)
    {
        return $value > $this->getDataValue($data, $rule);
    }

    public function elt($value, $rule, $data = [])
    {
        return $value <= $this->getDataValue($data, $rule);
    }

    public function lt($value, $rule, $data = [])
    {
        return $value < $this->getDataValue($data, $rule);
    }

    public function eq($value, $rule)
    {
        return $value == $rule;
    }

    public function must($value, $rule = null)
    {
        return !empty($value) || '0' == $value;
    }

    public function is($value, $rule, $data = [])
    {
        $rule = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
            return strtoupper($match[1]);
        }, $rule);

        switch (lcfirst($rule)) {
            case 'require':
                
                $result = !empty($value) || '0' == $value;
                break;
            case 'accepted':
                
                $result = in_array($value, ['1', 'on', 'yes']);
                break;
            case 'date':
                
                $result = false !== strtotime($value);
                break;
            case 'activeUrl':
                
                $result = checkdnsrr($value);
                break;
            case 'boolean':
            case 'bool':
                
                $result = in_array($value, [true, false, 0, 1, '0', '1'], true);
                break;
            case 'number':
                $result = is_numeric($value);
                break;
            case 'array':
                
                $result = is_array($value);
                break;
            case 'file':
                $result = $value instanceof SplFileObject;
                break;
            case 'image':
                $result = $value instanceof SplFileObject && in_array($this->getImageType($value->getRealPath()), [1, 2, 3, 6]);
                break;
            case 'password':
                $result = (bool)preg_match('/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_.]+)$)^[\w~!@#$%^&*()_.]{8,26}$/', $value);
                break;
            case 'notZero':
                $result = $value > 0;
                break;
            case "email":
                $result = (bool)preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $value);
                break;
            default:
                if (isset(self::$type[$rule])) {
                    
                    $result = call_user_func_array(self::$type[$rule], [$value]);
                } elseif (isset($this->filter[$rule])) {
                    
                    $result = $this->filter($value, $this->filter[$rule]);
                } else {
                    
                    $result = $this->regex($value, $rule);
                }
        }

        return $result;
    }

    protected function getImageType($image)
    {
        if (function_exists('exif_imagetype')) {
            return exif_imagetype($image);
        } else {
            try {
                $info = getimagesize($image);
                return $info ? $info[2] : false;
            } catch (\Exception $e) {
                return false;
            }
        }
    }

    public function activeUrl($value, $rule = 'MX')
    {
        if (!in_array($rule, ['A', 'MX', 'NS', 'SOA', 'PTR', 'CNAME', 'AAAA', 'A6', 'SRV', 'NAPTR', 'TXT', 'ANY'])) {
            $rule = 'MX';
        }

        return checkdnsrr($value, $rule);
    }

    public function ip($value, $rule = 'ipv4')
    {
        if (!in_array($rule, ['ipv4', 'ipv6'])) {
            $rule = 'ipv4';
        }

        return $this->filter($value, [FILTER_VALIDATE_IP, 'ipv6' == $rule ? FILTER_FLAG_IPV6 : FILTER_FLAG_IPV4]);
    }

    protected function checkExt($file, $ext)
    {
        $extension = strtolower(pathinfo($file->getfilename(), PATHINFO_EXTENSION));

        if (is_string($ext)) {
            $ext = explode(',', $ext);
        }

        if (!in_array($extension, $ext)) {
            return false;
        }

        return true;
    }

    public function fileExt($file, $rule)
    {
        if (!($file instanceof SplFileObject)) {
            return false;
        }

        return $this->checkExt($file, $rule);
    }

    protected function getMime($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        return finfo_file($finfo, $file->getRealPath() ?: $file->getPathname());
    }

    protected function checkMime($file, $mime)
    {
        if (is_string($mime)) {
            $mime = explode(',', $mime);
        }

        if (!in_array(strtolower($this->getMime($file)), $mime)) {
            return false;
        }

        return true;
    }

    public function fileMime($file, $rule)
    {
        if (!($file instanceof SplFileObject)) {
            return false;
        }

        return $this->checkMime($file, $rule);
    }

    public function fileSize($file, $rule)
    {
        if (!($file instanceof SplFileObject)) {
            return false;
        }

        return $file->getSize() <= $rule;
    }

    public function image($file, $rule)
    {
        if (!($file instanceof SplFileInfo)) {
            return false;
        }

        if ($rule) {
            $rule = explode(',', $rule);

            list($width, $height, $type) = getimagesize($file->getRealPath());

            if (isset($rule[2])) {
                $imageType = strtolower($rule[2]);

                if ('jpeg' == $imageType) {
                    $imageType = 'jpg';
                }

                if (image_type_to_extension($type, false) != $imageType) {
                    return false;
                }
            }

            list($w, $h) = $rule;

            return $w == $width && $h == $height;
        }
        return in_array($this->getImageType($file->getRealPath()), [1, 2, 3, 6]);
    }

    public function method($value, $rule)
    {
        return strtoupper($rule) == $_SERVER['REQUEST_METHOD'];
    }

    public function dateFormat($value, $rule)
    {
        $info = date_parse_from_format($rule, $value);
        return 0 == $info['warning_count'] && 0 == $info['error_count'];
    }

    public function filter($value, $rule)
    {
        if (is_string($rule) && strpos($rule, ',')) {
            list($rule, $param) = explode(',', $rule);
        } elseif (is_array($rule)) {
            $param = isset($rule[1]) ? $rule[1] : null;
            $rule = $rule[0];
        } else {
            $param = 0;
        }

        return false !== filter_var($value, is_int($rule) ? $rule : filter_id($rule), $param);
    }

    public function requireIf($value, $rule, $data)
    {
        list($field, $val) = explode(',', $rule);

        if ($this->getDataValue($data, $field) == $val) {
            return !empty($value) || '0' == $value;
        } else {
            return true;
        }
    }

    public function requireCallback($value, $rule, $data)
    {
        $result = call_user_func_array($rule, [$value, $data]);

        if ($result) {
            return !empty($value) || '0' == $value;
        } else {
            return true;
        }
    }

    public function requireWith($value, $rule, $data)
    {
        $val = $this->getDataValue($data, $rule);

        if (!empty($val)) {
            return !empty($value) || '0' == $value;
        } else {
            return true;
        }
    }

    public function in($value, $rule)
    {
        return in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }

    public function notIn($value, $rule)
    {
        return !in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }

    public function between($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        list($min, $max) = $rule;

        return $value >= $min && $value <= $max;
    }

    public function notBetween($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        list($min, $max) = $rule;

        return $value < $min || $value > $max;
    }

    public function length($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string)$value);
        }

        if (strpos($rule, ',')) {
            
            list($min, $max) = explode(',', $rule);
            return $length >= $min && $length <= $max;
        } else {
            
            return $length == $rule;
        }
    }

    public function max($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string)$value);
        }

        return $length <= $rule;
    }

    public function min($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string)$value);
        }

        return $length >= $rule;
    }

    public function after($value, $rule)
    {
        return strtotime($value) >= strtotime($rule);
    }

    public function before($value, $rule)
    {
        return strtotime($value) <= strtotime($rule);
    }

    public function expire($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }

        list($start, $end) = $rule;

        if (!is_numeric($start)) {
            $start = strtotime($start);
        }

        if (!is_numeric($end)) {
            $end = strtotime($end);
        }

        return $_SERVER['REQUEST_TIME'] >= $start && $_SERVER['REQUEST_TIME'] <= $end;
    }

    public function allowIp($value, $rule)
    {
        return in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }

    public function denyIp($value, $rule)
    {
        return !in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }

    public function regex($value, $rule)
    {
        if (isset($this->regex[$rule])) {
            $rule = $this->regex[$rule];
        }

        if (0 !== strpos($rule, '/') && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
            
            $rule = '/^' . $rule . '$/';
        }

        return is_scalar($value) && 1 === preg_match($rule, (string)$value);
    }

    public function getError()
    {
        return $this->error;
    }

    protected function getDataValue($data, $key)
    {
        if (is_numeric($key)) {
            $value = $key;
        } elseif (strpos($key, '.')) {
            
            list($name1, $name2) = explode('.', $key);
            $value = isset($data[$name1][$name2]) ? $data[$name1][$name2] : null;
        } else {
            $value = isset($data[$key]) ? $data[$key] : null;
        }

        return $value;
    }

    protected function getRuleMsg($attribute, $title, $type, $rule)
    {
        if (isset($this->message[$attribute . '.' . $type])) {
            $msg = $this->message[$attribute . '.' . $type];
        } elseif (isset($this->message[$attribute][$type])) {
            $msg = $this->message[$attribute][$type];
        } elseif (isset($this->message[$attribute])) {
            $msg = $this->message[$attribute];
        } elseif (isset(self::$typeMsg[$type])) {
            $msg = self::$typeMsg[$type];
        } elseif (0 === strpos($type, 'require')) {
            $msg = self::$typeMsg['require'];
        } else {
            $msg = $title . '规则不符';
        }

        if (is_string($msg) && is_scalar($rule) && false !== strpos($msg, ':')) {
            
            if (is_string($rule) && strpos($rule, ',')) {
                $array = array_pad(explode(',', $rule), 3, '');
            } else {
                $array = array_pad([], 3, '');
            }
            $msg = str_replace(
                [':attribute', ':rule', ':1', ':2', ':3'],
                [$title, (string)$rule, $array[0], $array[1], $array[2]],
                $msg);
        }

        return $msg;
    }

    protected function getScene($scene = '')
    {
        if (empty($scene)) {
            
            $scene = $this->currentScene;
        }

        $this->only = $this->append = $this->remove = [];

        if (empty($scene)) {
            return;
        }

        if (method_exists($this, 'scene' . $scene)) {
            call_user_func([$this, 'scene' . $scene]);
        } elseif (isset($this->scene[$scene])) {
            
            $scene = $this->scene[$scene];

            if (is_string($scene)) {
                $scene = explode(',', $scene);
            }

            $this->only = $scene;
        }
    }

    public function __call($method, $args)
    {
        if ('is' == strtolower(substr($method, 0, 2))) {
            $method = substr($method, 2);
        }

        array_push($args, lcfirst($method));

        return call_user_func_array([$this, 'is'], $args);
    }
}
