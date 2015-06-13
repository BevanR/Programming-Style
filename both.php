<?php

function main() {
  $fp = fopen(__FILE__ . '.csv', 'w');
  foreach (table() as $row) {
      fputcsv($fp, $row);
  }
  fclose($fp);
}

function table() {
  return array_merge([headers()], body());
}

function headers() {
  $total = total();
  $first = "$total field instances in total";
  return array_merge([$first], types());
}

function body() {
  $rows = [];
  foreach (fields() as $field) {
    $rows[] = row($field);
  }
  return $rows;
}

function row($field) {
  $cells = [$field];
  foreach (types() as $type) {
    $cells[] = (is_instance($field, $type) ? 'Y' : '');
  }
  return $cells;
}

function types() {
  static $cache = false;
  $cache = $cache ?: dimension('node_type', 'type', 'type_name');
  return $cache;
}

function fields() {
  return dimension('node_field', 'field_name');
}

function dimension($table, $field, $join = null) {
  $join = $join ?: $field;
  $field = "$table.$field";
  $sql = "SELECT $field
    FROM $table
      INNER JOIN node_field_instance i ON $field = i.$join
    GROUP BY $field
    ORDER BY count(*) DESC, $field
  ";
  return tbi_legacy_query($sql)->fetchCol();
}

function is_instance($field, $type) {
  static $instances = false;
  $instances = $instances ?: instances();
  return isset($instances[$field][$type]);
}

function total() {
  return results()->rowCount();
}

function instances() {
  $instances = [];
  while ($row = results()->fetchObject()) {
    $instances[$row->field][$row->type] = true;
  }
  return $instances;
}

function results() {
  static $cache = false;
  $cache = $cache ?: query();
  return $cache;
}

function query() {
  $sql = SELECT
      field_name AS field,
      type_name AS type
    FROM node_field_instance
  ';
  return tbi_legacy_query($sql);
}

main();
