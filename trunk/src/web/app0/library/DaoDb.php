<?php
/**
 * Dao基类 - Db
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */
class DaoDb
{
    protected $objectName;
    protected $objectFields;
    
    // Table primary key and value
    protected $primaryKey = 'id';
    protected $servRouteInstance = NULL;
    protected $dbInstance = NULL;
    
    // Current object
    protected $object  = array();
    protected $changed = array();
    protected $loaded  = FALSE;
    protected $saved   = FALSE;
    
    // Stores column information for Dao
    protected static $fieldsCache = array();
    
    /**
     * Creates and returns a new model.
     *
     * @chainable
     * @param   string  model name
     * @param   mixed   parameter for find()
     * @return  DaoDb
     */
    public static function factory($dao, $dbInstance = NULL , $idSet = NULL)
    {
        // Set class name
        $dao = $dao.'_Dao';

        return new $dao($dbInstance,$idSet);
    }
    
    /**
     * Prepares the model database connection and loads the object.
     *
     * @param   mixed  parameter for find or object to load
     * @return  void
     */
    function __construct ($dbInstance = NULL,$idSet = NULL)
    {
        $this->objectName   = substr(get_class($this), 0, -4); // -4 = '_Dao'
        $this->__initialize($dbInstance,$idSet);
        // Clear the object
        $this->clear();

        if (!empty($idSet))
        {
            // Find an object
            $this->get($idSet);
        }
    }
    
    public function getServRouteInstance(){
        return $this->servRouteInstance;
    }
    public function setServRouteInstance($servRouteInstance=NULL){
        if($servRouteInstance!==NULL){
            $this->servRouteInstance = $servRouteInstance;
        }
    }
    public function getDbInstance(){
        return $this->dbInstance;
    }
    public function setDbInstance($dbInstance=NULL){
        if($dbInstance!==NULL){
            $this->dbInstance = $dbInstance;
        }
    }
    
    function __initialize($dbInstance = NULL,$idSet = NULL){
        if($dbInstance!==NULL){
            $this->dbInstance = $dbInstance;
        }else{
            if($this->dbInstance===NULL){
                $this->servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
                if(empty($idSet)){
                    $this->dbInstance = $this->servRouteInstance->getDbInstance($this->objectName)->getInstance();
                }else{
                    if(!is_array($idSet)){
                        $routeSet = array('id'=>$idSet);
                    }else{
                        $routeSet = $idSet;
                    }
                    $this->dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,$routeSet)->getInstance();
                }
                //$this->dbInstance = $this->servRouteInstance->getDbInstance()->getInstance();
            }
        }
        // Load field information
        $this->reloadFields();
    }
    function clear(){
        // Create an array with all the Fields set to NULL
        $fields = array_keys($this->objectFields);
        $values  = array_combine($fields, array_fill(0, count($fields), NULL));
        // Replace the current object with an empty one
        $this->loadValues($values);
        return $this;
    }
    function loadValues(array $values){
        if (array_key_exists($this->primaryKey, $values))
        {
            // Replace the object and reset the object status
            $this->object = $this->changed = array();
            // Set the loaded and saved object status based on the primary key
            $this->loaded = $this->saved = ($values[$this->primaryKey] !== NULL);
        }
    
        foreach ($values as $field => $value)
        {
                if (isset($this->objectFields[$field]))
                {
                    // The type of the value can be determined, convert the value
                    $value = $this->loadType($field, $value);
                }
                $this->object[$field] = $value;
        }
        return $this;
    }
    
    /**
     * Reload column definitions.
     *
     * @chainable
     * @param   boolean  force reloading
     * @return  DaoDb
     */
    public function reloadFields($force = FALSE)
    {
        if ($force === TRUE OR empty($this->objectFields))
        {
            if (isset(DaoDb::$fieldsCache[$this->objectName]))
            {
                // Use cached column information
                $this->objectFields = DaoDb::$fieldsCache[$this->objectName];
            }
            else
            {
                // Load table columns
                DaoDb::$fieldsCache[$this->objectName] = $this->objectFields = $this->listFields();
            }
        }
        return $this;
    }

    /**
     * Proxy method to Database list_fields.
     *
     * @param   string  table name or NULL to use this table
     * @return  array
     */
    public function listFields($objectName = NULL)
    {
        if ($objectName === NULL)
        {
            $objectName = $this->objectName;
        }
        $result = NULL;
        $fieldsAssoc = $this->dbInstance->get_results("SHOW COLUMNS FROM ".$objectName, OBJECT);
        foreach ($fieldsAssoc as $row){
            // Make an associative array
            $result[$row->Field] = $this->sqlType($row->Type);
            if ($row->Key === 'PRI' AND $row->Extra === 'auto_increment')
            {
                // For sequenced (AUTO_INCREMENT) tables
                $result[$row->Field]['sequenced'] = TRUE;
            }

            if ($row->Null === 'YES')
            {
                // Set NULL status
                $result[$row->Field]['null'] = TRUE;
            }
        }
        if (!isset($result)){
            throw new LemonRuntimeException(_('database.table_not_found:').$objectName,500);
        }
        return $result;
    }

    protected function sqlType($str){
        static $sqlTypes;

        if ($sqlTypes === NULL)
        {
            // Load SQL data types
            $sqlTypes = Lemon::config('sql_types');
        }
        $str = strtolower(trim($str));

        if (($open  = strpos($str, '(')) !== FALSE)
        {
            // Find closing bracket
            $close = strpos($str, ')', $open) - 1;

            // Find the type without the size
            $type = substr($str, 0, $open);
        }
        else
        {
            // No length
            $type = $str;
        }

        empty($sqlTypes[$type]) and exit
        (
            'Unknown field type: '.$type
        );

        // Fetch the field definition
        $field = $sqlTypes[$type];

        switch ($field['type'])
        {
            case 'string':
            case 'float':
                if (isset($close))
                {
                    // Add the length to the field info
                    $field['length'] = substr($str, $open + 1, $close - $open);
                }
            break;
            case 'int':
                // Add unsigned value
                $field['unsigned'] = (strpos($str, 'unsigned') !== FALSE);
            break;
        }
        return $field;
    }

    /**
     * Loads a value according to the types defined by the column metadata.
     *
     * @param   string  column name
     * @param   mixed   value to load
     * @return  mixed
     */
    protected function loadType($field, $value)
    {
        $type = gettype($value);
        if ($type == 'object' OR $type == 'array' OR ! isset($this->objectFields[$field]))
            return $value;

        // Load column data
        $field = $this->objectFields[$field];

        if ($value === NULL AND ! empty($field['null']))
            return $value;

        if ( ! empty($field['binary']) AND ! empty($field['exact']) AND (int) $field['length'] === 1)
        {
            // Use boolean for BINARY(1) fields
            $field['type'] = 'boolean';
        }

        switch ($field['type'])
        {
            case 'int':
                if ($value === '' AND ! empty($field['null']))
                {
                    // Forms will only submit strings, so empty integer values must be null
                    $value = NULL;
                }
                elseif ((float) $value > PHP_INT_MAX)
                {
                    // This number cannot be represented by a PHP integer, so we convert it to a string
                    $value = (string) $value;
                }
                else
                {
                    $value = (int) $value;
                }
            break;
            case 'float':
                $value = (float) $value;
            break;
            case 'boolean':
                $value = (bool) $value;
            break;
            case 'string':
                $value = (string) $value;
            break;
        }

        return $value;
    }
    
    /**
     * Escapes any input value.
     *
     * @param   mixed   value to escape
     * @return  string
     */
    public function escapeValue($value)
    {
        switch (gettype($value))
        {
            case 'string':
                $value = '\''.$this->dbInstance->escape($value).'\'';
            break;
            case 'boolean':
                $value = (int) $value;
            break;
            case 'double':
                // Convert to non-locale aware float to prevent possible commas
                $value = sprintf('%F', $value);
            break;
            default:
                $value = ($value === NULL) ? 'NULL' : $value;
            break;
        }
        return (string) $value;
    }

    
    /**
     * Reloads the current object from the database.
     *
     * @chainable
     * @return  ORM
     */
    public function reload()
    {
        return $this->get($this->object[$this->primaryKey]);
    }
    /**
     * Returns whether or not primary key is empty
     *
     * @return bool
     */
    protected function emptyPrimaryKey()
    {
        return (empty($this->object[$this->primaryKey]) AND $this->object[$this->primaryKey] !== '0');
    }
    
    /**
     * Returns the unique key for a specific value. This method is expected
     * to be overloaded in models if the model has other unique columns.
     *
     * @param   mixed   unique value
     * @return  string
     */
    public function uniqueKey($id)
    {
        return $this->primaryKey;
    }

    public function asArray()
    {
        $object = array();

        foreach ($this->object as $key => $val)
        {
            // Reconstruct the array (calls __get)
            $object[$key] = $this->$key;
        }
        return $object;
    }
    
    public function __get($field){
        if (array_key_exists($field, $this->object))
        {
            return $this->object[$field];
        }
        elseif ($field === 'primaryKeyValue')
        {
            return $this->object[$this->primaryKey];
        }
        elseif (in_array($field, array
            (
                'objectName', 'objectFields', 'primaryKey', // Object
                'loaded', 'saved', // Status
                'servRouteInstance','dbInstance',
            )))
        {
            // Model meta information
            return $this->$field;
        }
        else
        {
            throw new LemonRuntimeException(_('core.invalid_property:').$field.' '.get_class($this),500);
        }
    }
    public function __set($field, $value){
        if (isset($this->object[$field]) OR array_key_exists($field, $this->object))
        {
            
            if (isset($this->objectFields[$field]))
            {
                // Data has changed
                $this->changed[$field] = $field;

                // Object is no longer saved
                $this->saved = FALSE;
            }
            $this->object[$field] = $this->loadType($field, $value);
        }
        else
        {
            throw new LemonRuntimeException(_('core.invalid_property:').$field.' '.get_class($this),500);
        }
    }
    
    public function __isset($field)
    {
        return isset($this->object[$field]);
    }
    
    public function __unset($field)
    {
        unset($this->object[$field], $this->changed[$field]);
    }
    
    public function __toString()
    {
        return (string) $this->object[$this->primaryKey];
    }
    
    /**
     * Saves the current object.
     *
     * @chainable
     * @return  ORM
     */
    public function save()
    {
        if ( ! empty($this->changed))
        {
            $data = array();
            foreach ($this->changed as $field)
            {
                // Compile changed data
                $data[$field] = $this->object[$field];
            }

            if ($this->loaded === TRUE)
            {
                //update sql
                $valstr = array();
                foreach($data as $key => $val){
                    $valstr[] = $key.' = '.$this->escapeValue($val);
                }
                $sql = 'UPDATE '.$this->objectName.' SET '.implode(', ', $valstr). ' WHERE '.$this->primaryKey. ' = '. $this->object[$this->primaryKey] .' LIMIT 1';
                $query = $this->dbInstance->query($sql);
                // Object has been saved
                $this->saved = TRUE;
            }
            else
            {
                //insert sql
                $fields = array_keys($data);
                $values = array_values($data);

                //array_walk($values,array($this,'escapeValue'));
                $values = array_map(array($this,'escapeValue'),$values);
                $sql = 'INSERT INTO '.$this->objectName.' (`'.implode('`, `',$fields).'`) VALUES ('.implode(', ', $values).')';
                $query = $this->dbInstance->query($sql);

                if ($query > 0)
                {
                    if (empty($this->object[$this->primaryKey]))
                    {
                        // Load the insert id as the primary key
                        $this->object[$this->primaryKey] = $this->dbInstance->insert_id;
                    }

                    // Object is now loaded and saved
                    $this->loaded = $this->saved = TRUE;
                }
            }

            if ($this->saved === TRUE)
            {
                // All changes have been saved
                $this->changed = array();
            }
        }elseif(count($this->objectFields)==1 && array_key_exists('sequenced',$this->objectFields[$this->primaryKey]) && $this->objectFields[$this->primaryKey]['sequenced']==TRUE){
            $sql = 'INSERT INTO '.$this->objectName.' SET '.$this->primaryKey.' = NULL ';
            $query = $this->dbInstance->query($sql);
            if ($query > 0)
            {
                if (empty($this->object[$this->primaryKey]))
                {
                    // Load the insert id as the primary key
                    $this->object[$this->primaryKey] = $this->dbInstance->insert_id;
                }

                // Object is now loaded and saved
                $this->loaded = $this->saved = TRUE;
            }
            if ($this->saved === TRUE)
            {
                // All changes have been saved
                $this->changed = array();
            }
        }
        return $this;
    }
    
    /**
     * Finds and loads a single database row into the object.
     *
     * @chainable
     * @param   mixed  primary key or an array of clauses
     * @return  ORM
     */
    public function get($idSet = NULL)
    {
        if ($idSet !== NULL)
        {
            if(!is_array($idSet)){
                $queryStruct = array(
                    'conditionKey'=>array('id'=>$idSet),
                );
            }else{
                $queryStruct = array(
                    'conditionKey'=>array($idSet),
                );
            }
            $resultObject = $this->queryRow($queryStruct);
            if(!empty($resultObject)){
                $this->loadValues($resultObject);
            }
        }
        return $this;
    }

    public function insert($queryStruct = NULL){
        if ($queryStruct === NULL){
            if(! empty($this->changed) && $this->loaded === FALSE){
                $data = array();
                foreach ($this->changed as $field)
                {
                    // Compile changed data
                    $data[$field] = $this->object[$field];
                }
                $valstr = array();
                foreach($data as $key => $val){
                    $valstr[] = $key.' = '.$this->escapeValue($val);
                }
                // 插入数据
                $queryStruct = array(
                    'querySchema' => array($this->objectName),
                    'queryField' => $valstr,
                );
                $sql = $this->compileSqlInsert($queryStruct);
                $query = $this->dbInstance->query($sql);
                if ($query > 0)
                {
                    if (empty($this->object[$this->primaryKey]))
                    {
                        // Load the insert id as the primary key
                        $this->object[$this->primaryKey] = $this->dbInstance->insert_id;
                    }
                    // Object is now loaded and saved
                    $this->loaded = $this->saved = TRUE;
                }
            }
        }else{
            $sql = $this->compileSqlInsert($queryStruct);
            $query = $this->dbInstance->query($sql);
            if ($query > 0)
            {
                $this->clear();
                if (empty($this->object[$this->primaryKey]))
                {
                    // Load the insert id as the primary key
                    $this->object[$this->primaryKey] = $this->dbInstance->insert_id;
                }
                // Object is now loaded and saved
                //$this->loaded = $this->saved = TRUE;
            }
            
        }
        return $this;
    }
    
    public function update($queryStruct = NULL){
        if ($queryStruct === NULL){
            if(!empty($this->changed) && $this->loaded === TRUE){
                $data = array();
                foreach ($this->changed as $field)
                {
                    // Compile changed data
                    $data[$field] = $this->object[$field];
                }
                $valstr = array();
                foreach($data as $key => $val){
                    $valstr[] = $key.' = '.$this->escapeValue($val);
                }
                // 更新
                $queryStruct = array(
                    'querySchema' => array($this->objectName),
                    'queryField' => $valstr,
                    'conditionKey'=>array($this->primaryKey => $this->object[$this->primaryKey]),
                    'limitOffset'=> array('limit'=>1),
                );
                $sql = $this->compileSqlUpdate($queryStruct);
                $query = $this->dbInstance->query($sql);
                $this->saved = TRUE;
                $this->changed = array();
            }
        }else{
            $sql = $this->compileSqlUpdate($queryStruct);
            $query = $this->dbInstance->query($sql);
            if(isset($queryStruct['conditionKey']) && isset($queryStruct['conditionKey'][$this->primaryKey]) && $queryStruct['conditionKey'][$this->primaryKey]==$this->object[$this->primaryKey]){
                $this->clear();
            }
            if(isset($queryStruct['conditionIn']) && isset($queryStruct['conditionIn'][$this->primaryKey]) && in_array($this->object[$this->primaryKey],$queryStruct['conditionKey'][$this->primaryKey])){
                $this->clear();
            }
        }
        return $this;
    }
    /**
     * Deletes the current object from the database. This does NOT destroy
     * relationships that have been created with other objects.
     *
     * @chainable
     * @return  ORM
     */
    public function delete($queryStruct = NULL)
    {
        if ($queryStruct === NULL && $this->loaded){
            // 删除自身数据
            $queryStruct = array(
                'querySchema' => array($this->objectName),
                'conditionKey'=>array($this->primaryKey => $this->object[$this->primaryKey]),
                'limitOffset'=> array('limit'=>1),
            );
            $sql = $this->compileSqlDelete($queryStruct);
            $query = $this->dbInstance->query($sql);
            return $this->clear();
        }else{
            $sql = $this->compileSqlDelete($queryStruct);
            $query = $this->dbInstance->query($sql);
            if(isset($queryStruct['conditionKey']) && isset($queryStruct['conditionKey'][$this->primaryKey]) && $queryStruct['conditionKey'][$this->primaryKey]==$this->object[$this->primaryKey]){
                $this->clear();
            }
            if(isset($queryStruct['conditionIn']) && isset($queryStruct['conditionIn'][$this->primaryKey]) && in_array($this->object[$this->primaryKey],$queryStruct['conditionKey'][$this->primaryKey])){
                $this->clear();
            }
        }
        return $this;
    }

    public function queryVar($queryStruct=array()){
        try {
            //支持的查询结构关键字
            //$queryKeys = array('quote','queryField','querySchema','conditionKey','conditionIn','conditionLike','orderSet','limitOffset');
            !array_key_exists('queryField', $queryStruct) && $queryStruct['queryField'] = array('id');
            !array_key_exists('querySchema', $queryStruct) && $queryStruct['querySchema'] = array($this->objectName);
            $queryStruct['limitOffset'] = array('limit'=>1);
            $sql = $this->compileSqlSelect($queryStruct);
            $resultRow = $this->dbInstance->get_var($sql);
            return $resultRow;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    public function queryRow($queryStruct=array()){
        try {
            //支持的查询结构关键字
            //$queryKeys = array('quote','queryField','querySchema','conditionKey','conditionIn','conditionLike','orderSet','limitOffset');
            !array_key_exists('queryField', $queryStruct) && $queryStruct['queryField'] = array('*');
            !array_key_exists('querySchema', $queryStruct) && $queryStruct['querySchema'] = array($this->objectName);
            $queryStruct['limitOffset'] = array('limit'=>1);
            $sql = $this->compileSqlSelect($queryStruct);
            $resultRow = $this->dbInstance->get_row($sql,ARRAY_A);
            return $resultRow;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    public function queryAssoc($queryStruct=array()){
        try {
            //支持的查询结构关键字
            //$queryKeys = array('quote','queryField','querySchema','conditionKey','conditionIn','conditionLike','orderSet','limitOffset');
            !array_key_exists('queryField', $queryStruct) && $queryStruct['queryField'] = array('*');
            !array_key_exists('querySchema', $queryStruct) && $queryStruct['querySchema'] = array($this->objectName);
            $sql = $this->compileSqlSelect($queryStruct);
            $resultAssoc = $this->dbInstance->get_results($sql,ARRAY_A);
            return $resultAssoc;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    public function queryCount($queryStruct=array()){
        try {
            //支持的查询结构关键字
            //$queryKeys = array('quote','queryField','querySchema','conditionKey','conditionIn','conditionLike','orderSet','limitOffset');
            $queryStruct['queryField'] = array('COUNT(*) AS resultCount');
            !array_key_exists('querySchema', $queryStruct) && $queryStruct['querySchema'] = array($this->objectName);
            if(array_key_exists('orderSet', $queryStruct)){ unset($queryStruct['orderSet']); }
            if(array_key_exists('limitOffset', $queryStruct)){ unset($queryStruct['limitOffset']); }
            $sql = $this->compileSqlSelect($queryStruct);
            // TODO 根据$queryStruct组织特征向量修改dbInstance;
            $resultVar = $this->dbInstance->get_var($sql);
            return $resultVar;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    /**
     * Determines if the string has an arithmetic operator in it.
     *
     * @param   string   string to check
     * @return  boolean
     */
    public static function hasOperator($string){
        return (bool) preg_match('/[<>!=]|\sIS(?:\s+NOT\s+)?\b|BETWEEN/i', trim($string));
    }
    
    public function compileSqlCondition($queryStruct=array()){
        //* 处理输入条件
        $quote = isset($queryStruct['quote'])?(bool)$queryStruct['quote']:TRUE;
        // 查询的关键字条件
        $sqlConditionKey = '';
        if(isset($queryStruct['conditionKey']) && !empty($queryStruct['conditionKey'])){
            foreach($queryStruct['conditionKey'] as $key=>$value){
                if ($value === NULL)
                {
                    if ( ! self::hasOperator($key))
                    {
                        $key .= ' IS';
                    }
                    $value = ' NULL';
                }
                elseif (is_bool($value))
                {
                    if ( ! self::hasOperator($key))
                    {
                        $key .= ' =';
                    }
                    $value = ($value == TRUE) ? ' 1' : ' 0';
                }
                else
                {
                    if ( ! self::hasOperator($key) AND ! empty($key))
                    {
                        $key = $key.' =';
                    }
                    else
                    {
                        preg_match('/^(.+?)([<>!=]+|\bIS(?:\s+NULL))\s*$/i', $key, $matches);
                        if (isset($matches[1]) AND isset($matches[2]))
                        {
                            $key = trim($matches[1]).' '.trim($matches[2]);
                        }
                    }
                    $value = ' '.(($quote == TRUE) ? $this->escapeValue($value) : $value);
                }
                $sqlConditionKey .= ' AND '. $key . $value;
            }
            $sqlConditionKey = substr($sqlConditionKey,5);//strlen(' AND ') = 5
        }
        
        // 查询的in条件
        $sqlConditionIn = '';
        if(isset($queryStruct['conditionIn']) && !empty($queryStruct['conditionIn'])){
            foreach ($queryStruct['conditionIn'] as $key=>$value){
                if (is_array($value))
                {
                    $escapedValue = array();
                    foreach ($value as $v)
                    {
                        if (is_numeric($v))
                        {
                            $escapedValue[] = $v;
                        }
                        else
                        {
                            $escapedValue[] = "'".$this->dbInstance->escape($v)."'";
                        }
                    }
                    $value = implode(",", $escapedValue);
                }
                $sqlConditionIn .= ' AND '.$key.' IN ( '.$value.')';
            }
            $sqlConditionIn = substr($sqlConditionIn,5);//strlen(' AND ') = 5
        }
        
        // 查询的like条件
        $sqlConditionLike = '';
        if(isset($queryStruct['conditionLike']) && !empty($queryStruct['conditionLike'])){
            foreach ($queryStruct['conditionLike'] as $key=>$value){
                $value = $this->dbInstance->escape($value);
                $value = '%'.str_replace('%', '\\%', $value).'%';
                $sqlConditionLike .= ' AND '.$key.' LIKE \''.$value . '\'';
            }
            $sqlConditionLike = substr($sqlConditionLike,5);//strlen(' AND ') = 5
        }
        
        // 查询条件（where）整合
        $sqlCondition = '';
        //如果有任意一个条件非空
        if(!empty($sqlConditionKey)
        || !empty($sqlConditionIn)
        || !empty($sqlConditionLike)
        ){
            $sqlCondition .= ' WHERE ';
            $sqlConditionRow = array();
            !empty($sqlConditionKey) && $sqlConditionRow[]=$sqlConditionKey;
            !empty($sqlConditionIn) && $sqlConditionRow[]=$sqlConditionIn;
            !empty($sqlConditionLike) && $sqlConditionRow[]=$sqlConditionLike;
            $sqlCondition .= ' '.implode(' AND ',$sqlConditionRow);
        }
        return $sqlCondition;
    }

    public function compileSqlInsert($queryStruct=array()){
        //* 处理输入条件
        $quote = isset($queryStruct['quote'])?(bool)$queryStruct['quote']:TRUE;
        //最终的查询语句。
        $sqlString = 'INSERT INTO ';
        // 查询的库表
        $sqlSchema = '';
        if(isset($queryStruct['querySchema']) && !empty($queryStruct['querySchema'])){
            $sqlSchema .= implode(',',$queryStruct['querySchema']).' SET ';
        }
        // 查询的域
        $sqlField = '';
        if(isset($queryStruct['queryField']) && !empty($queryStruct['queryField'])){
            $sqlField = implode(',',$queryStruct['queryField']);
        }
        $sqlString .= $sqlSchema.$sqlField;
        return $sqlString;
    }
    public function compileSqlUpdate($queryStruct=array()){
        //* 处理输入条件
        $quote = isset($queryStruct['quote'])?(bool)$queryStruct['quote']:TRUE;
        //最终的查询语句。
        $sqlString = 'UPDATE ';
        // 查询的库表
        $sqlSchema = '';
        if(isset($queryStruct['querySchema']) && !empty($queryStruct['querySchema'])){
            $sqlSchema .= implode(',',$queryStruct['querySchema']).' SET ';
        }
        // 查询的域
        $sqlField = '';
        if(isset($queryStruct['queryField']) && !empty($queryStruct['queryField'])){
            $sqlField = implode(',',$queryStruct['queryField']);
        }
        $sqlCondition = $this->compileSqlCondition($queryStruct);
        // Limit分页条件
        $sqlLimitOffset = '';
        if(isset($queryStruct['limitOffset']) && !empty($queryStruct['limitOffset']) && isset($queryStruct['limitOffset']['limit'])){
            $sqlLimitOffset .= ' LIMIT '.$queryStruct['limitOffset']['limit'];
        }
        $sqlString .= $sqlSchema.$sqlField.$sqlCondition.$sqlLimitOffset;
        return $sqlString;
    }
    
    public function compileSqlDelete($queryStruct=array()){
        //* 处理输入条件
        $quote = isset($queryStruct['quote'])?(bool)$queryStruct['quote']:TRUE;
        //最终的查询语句。
        $sqlString = 'DELETE ';
        // 查询的库表
        $sqlSchema = '';
        if(isset($queryStruct['querySchema']) && !empty($queryStruct['querySchema'])){
            $sqlSchema .= ' FROM '.implode(',',$queryStruct['querySchema']);
        }
        $sqlCondition = $this->compileSqlCondition($queryStruct);
        // Limit分页条件
        $sqlLimitOffset = '';
        if(isset($queryStruct['limitOffset']) && !empty($queryStruct['limitOffset']) && isset($queryStruct['limitOffset']['limit'])){
            $sqlLimitOffset .= ' LIMIT '.$queryStruct['limitOffset']['limit'];
        }
        $sqlString .= $sqlSchema.$sqlCondition.$sqlLimitOffset;
        return $sqlString;
    }
    
    /**
     * 编译sql select 语句
     * @param array $queryStruct
     * $queryStruct=array(
     *      'quote'=>TRUE,
     *      'queryField'=>array('id', ),
     *      'querySchema'=>array('Temp', ),
     *      'conditionKey'=>array(
     *          'id >'=>0,
     *          'key'=>'tmpval',
     *      ),
     *      'conditionIn'=>array(
     *          'id'=>array(1,3,5,7,8,9),
     *      ),
     *      'conditionLike'=>array(
     *          'key'=>'tmpstr',
     *      ),
     *      'orderSet'=>array(
     *          array('createTimestamp'=>'DESC'),
     *          array('id'=>'ASC'),
     *      ),
     *      'limitOffset'=>array(
     *          'offset'=>0,
     *          'limit'=>5,
     *      ),
     * );
     */
    public function compileSqlSelect($queryStruct=array()){
        //* 处理输入条件
        $quote = isset($queryStruct['quote'])?(bool)$queryStruct['quote']:TRUE;

        //最终的查询语句。
        $sqlString = 'SELECT ';
        //FIXME 目前只做了对业务日常需求所需要用到的key的对应构建，有空应该补齐对应的完整逻辑。
        
        // 查询的域
        $sqlField = '';
        if(isset($queryStruct['queryField']) && !empty($queryStruct['queryField'])){
            $sqlField = implode(',',$queryStruct['queryField']);
        }
        
        // 查询的库表
        $sqlSchema = '';
        if(isset($queryStruct['querySchema']) && !empty($queryStruct['querySchema'])){
            $sqlSchema .= ' FROM '.implode(',',$queryStruct['querySchema']);
        }
        

        $sqlCondition = $this->compileSqlCondition($queryStruct);
        
        // 排序条件
        $sqlOrder = '';
        if(isset($queryStruct['orderSet']) && !empty($queryStruct['orderSet'])){
            $sqlSortArray = array();
            foreach ($queryStruct['orderSet'] as $row=>$set){
                foreach($set as $field=>$direction){
                    $direction = strtoupper(trim($direction));
                    ! in_array($direction, array('ASC', 'DESC', 'RAND()', 'RANDOM()', 'NULL')) && $direction = 'ASC';
                    $sqlSortArray[] = $field.' '.$direction;
                }
            }
            $sqlOrder = ' ORDER BY '.implode(', ', $sqlSortArray);
        }
        
        // Limit分页条件
        $sqlLimitOffset = '';
        if(isset($queryStruct['limitOffset']) && !empty($queryStruct['limitOffset'])){
            if(isset($queryStruct['limitOffset']['offset']) && isset($queryStruct['limitOffset']['limit'])){
                $sqlLimitOffset .= ' LIMIT '.$queryStruct['limitOffset']['offset'].','.$queryStruct['limitOffset']['limit'];
            }elseif (!isset($queryStruct['limitOffset']['offset']) && isset($queryStruct['limitOffset']['limit'])){
                $sqlLimitOffset .= ' LIMIT '.$queryStruct['limitOffset']['limit'];
            }
        }
        $sqlString .= $sqlField.$sqlSchema.$sqlCondition.$sqlOrder.$sqlLimitOffset;
        return $sqlString;
    }
}