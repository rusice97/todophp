<?php
// +----------------------------------------------------------------------
// | TODOPHP [ A SIMPLE PHP FRAMEWORD ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 http://ruiblog.top All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: RuiZhaoLi <liruizhao1997@outlook.com>
// +----------------------------------------------------------------------

// HiPdo.php
// 2017/4/2

namespace core;


class HiPdo
{
    static private $configs;

    private $config_options;

    private $handle;

    private $sql;

    private $result;

    private $noSql = false;

    protected $tableName;

    public function __construct($option = [])
    {
        if (!class_exists('PDO', false)) {
            $this->noSql = true;
            die('环境不支持PDO,无法使用该系统!');
        }
        if (self::$configs == null) {
            self::$configs = Configs::get('DB');
        }

        $dbchar = self::$configs['DB_CHARSET'];

        $this->config_options = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '$dbchar'"
        ];
        if (!empty($option)) {
            $this->config_options = array_merge($option, $this->config_options);
        }
        $this->_content();
    }

    /**
     * 处理数据表名称
     * @param $table
     * @return $this->tableName
     */
    public function getTableName($table)
    {
        return $this->tableName = self::$configs['DB_PREFIX'] . $table;
    }

    private function _content()
    {
        try {
            $dbtype = self::$configs['DB_TYPE'];
            $dbhost = self::$configs['DB_HOST'];
            $dbname = self::$configs['DB_NAME'];
            $dbport = self::$configs['DB_PORT'];
            $dsn = "$dbtype:dbname=$dbname;host=$dbhost;port:$dbport";
            $this->handle = new \PDO($dsn, self::$configs['DB_USER'], self::$configs['DB_PASS'], $this->config_options);
        } catch (\PDOException $e) {
            $this->noSql = true;
            die($e->getMessage());
        }
    }

    /**
     * 执行一条SQL语句
     * @param $sql
     * @return mixed
     */
    public function query($sql)
    {
        $this->result = $this->handle->query($sql);
        return $this->result;
    }

    public function exec($sql)
    {
        return $this->handle->exec($sql);
    }

    public function getCount($field, $table, $where)
    {
        $sql = "SELECT COUNT($field) AS postCount FROM $table $where";
        $result = $this->query($sql);
        $this->result = $result->fetchColumn();
        return $this->result;
    }

    /**
     * 获取PDO操作出错信息
     * @return mixed
     */
    public function getError()
    {
        return $this->handle->errorCode();
    }

    public function getPk()
    {
        $sql = "SHOW FULL COLUMNS FROM {$this->tableName}";
        $this->result = $this->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($this->result as $res) {
            if ($res['Extra'] == 'auto_increment')
                $pk = $res['Field'];
        }
        return $pk;
    }

    /**
     * 统一更新
     * @param $rows
     * @return array
     */
    public function hb_Update($rows)
    {
        $pk = $this->getPk();
        $sql = "UPDATE {$this->tableName} SET ";
        $_sql = '';
        foreach ($rows as $col => $value) {
            $_sql .= "$col = '$value',";
        }
        $_sql = rtrim($_sql, ',');
        $sql .= $_sql . " WHERE `$pk` = {$rows[$pk]}";
        $res = $this->exec($sql);
        return returnMod($res, '0010');
    }

    /**
     * 统一删除
     * @param $rows
     * @return array
     */
    public function hb_Del($rows, $isHide = true)
    {
        $pk = $this->getPk();
        $ids = $rows['ids'];
        if (is_array($ids)) {
            $_sql = $pk . ' IN (' . join(',', $ids) . ')';
        } else {
            $_sql = $pk . ' = ' . $ids;
        }
        if ($isHide) {
            $col = $rows['col'];
            $sql = "UPDATE {$this->tableName} SET $col = 0 WHERE $_sql";
        } else {
            $sql = "DELETE FROM {$this->tableName} WHERE $ids $_sql";
        }
        $res = $this->exec($sql);
        return returnMod($res, '0020');
    }
}