<?php

require_once dirname(__FILE__) . '/../config/connection.php';

class QueryBuilder {

    private $table_name;
    private $model_name;
    private $conditions;
    private $fields;
    private $db;
    private $limit;
    private $offset;
    private $joins;
    private $order_clauses;


    function __construct($table_name, $model_name) {
        $this->conditions    = [];
        $this->fields        = "${table_name}.*";
        $this->table_name    = $table_name;
        $this->model_name    = $model_name;
        $this->joins         = [];
        $this->order_clauses = [];
        $this->db            = DB::connect();
    }

    public function where($condition, $values = []) {
        $this->add_condition($condition, (array)$values);
        return $this;
    }

    public function or_where($condition, $values = []) {
        $this->add_condition($condition, (array)$values, 'OR');
        return $this;
    }

    public function select($fields) {
        $this->fields = $fields;
        return $this;
    }

    public function count($field = '*') {
        $prev_fields     = $this->fields;
        $prev_conditions = $this->conditions;
        $this->fields    = "COUNT(${field})";
        list($sql, $values) = $this->build_query_string();
        $stmt = $this->db->prepare($sql);

        $stmt->execute($values);

        $this->fields     = $prev_fields;
        $this->conditions = $prev_conditions;

        return $stmt->fetchColumn();
    }

    public function order_by($clauses) {
        $clauses = (array)$clauses;
        foreach ($clauses as $clause => $ord) {
            if (is_int($clause)) {
                array_push($this->order_clauses, [$ord => 'ASC']);
            } else {
                array_push($this->order_clauses, [$clause => $ord]);
            }
        }
        return $this;
    }

    public function limit($limit) {
        $this->limit = (int)$limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = (int)$offset;
        return $this;
    }

    public function joins($join_clause) {
        array_push($this->joins, $join_clause);
        return $this;
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM $this->table_name WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->fetchObject($this->model_name);
        } else {
            return $stmt->errorInfo();
        }
    }

    public function get() {
        list($sql, $values) = $this->build_query_string();

        $stmt = $this->db->prepare($sql);

        $stmt->execute($values);

        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->model_name);
    }

    /**
     * @return array
     */
    private function build_query_string() {
        $values = [];
        $sql    = "SELECT $this->fields FROM $this->table_name ";

        $this->join_tables($sql);

        if (!empty($this->conditions)) {
            $this->add_conditions($sql, $values);
        }

        if (!empty($this->order_clauses)) {
            $this->order_results($sql);
        }

        if (!empty($this->limit)) {
            $this->set_limit($sql, $values);

            if (!empty($this->offset)) {
                $this->set_offset($sql, $values);
            }
        }

        return [$sql, $values];
    }

    private function join_tables(&$sql) {
        $sql .= implode(' ', $this->joins) . ' ';
    }

    private function add_conditions(&$sql, &$values) {
        $first  = array_shift($this->conditions);
        $values = array_merge($values, array_pop($first));
        $sql .= 'WHERE (' . array_pop($first) . ')';
        foreach ($this->conditions as $condition) {
            $operator         = key($condition);
            $values           = array_merge($values, array_pop($condition));
            $string_condition = array_pop($condition);
            $sql .= " ${operator} (${string_condition}) ";
        }
    }

    private function order_results(&$sql) {
        $sql .= ' ORDER BY ';
        $order_clauses = [];
        // PDO does not support bound variables on order by clauses so some magic is needed
        foreach ($this->order_clauses as $index => $clause) {
            $field     = substr($this->db->quote(key($clause)), 1, -1);
            $direction = array_pop($clause);
            if (strcasecmp(trim($direction), 'DESC') !== 0) {
                $direction = 'ASC';
            }
            array_push($order_clauses, "${field} ${direction}");
        }
        $sql .= implode(', ', $order_clauses);
    }

    private function set_limit(&$sql, &$values) {
        $sql .= " LIMIT :limit";
        $values[':limit'] = $this->limit;
    }

    private function set_offset(&$sql, &$values) {
        $sql .= " OFFSET :offset";
        $values[':offset'] = $this->offset;
    }

    private function add_condition($condition, $values, $operator = 'AND') {
        array_push($this->conditions, [$operator => $condition, 'values' => $values]);
    }
}