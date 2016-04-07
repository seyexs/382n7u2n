<?php
$fixture = Array( );
assertTrue(sizeof($fixture) == 0);

$fixture[] = "element";
assertTrue(sizeof($fixture) == 1);

function assertTrue($condition) {
 if (!$condition) {
  throw new Exception("Assertion failed.");
 }
}
?>
