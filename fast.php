<?php
function dimension($table, $field, $join = null) {
  $join = $join ?: $field;
  $field = "$table.$field";

  $sql = "SELECT $field FROM $table
      INNER JOIN node_field_instance i ON $field = i.$join
    GROUP BY $field
    ORDER BY count(*) DESC, $field";

  return tbi_legacy_query($sql)->fetchCol();
}

$result = tbi_legacy_query('SELECT field_name AS field, type_name AS type FROM node_field_instance');

$instances = [];
while ($row = $result->fetchObject()) {
  $instances[$row->field][$row->type] = true;
}

$total = $result->rowCount();
$first = "$total field instances in total";
$types = dimension('node_type', 'type', 'type_name');
$headers = array_merge([$first], $types);
$table = [$headers];
foreach (dimension('node_field', 'field_name') as $field) {
  $row = [$field];
  foreach ($types as $type) {
    $row[] = isset($instances[$field][$type]) ? 'Y' : '';
  }
  $table[] = $row;
}

$fp = fopen(__FILE__ . '.csv', 'w');
foreach ($table as $row) {
    fputcsv($fp, $row);
}
fclose($fp);
