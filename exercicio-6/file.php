<h2 class="title is-2">Exercício 6</h2>
<p class="content">Explorar o uso de argumentos dinâmicos para as funções e organizar uma fluxo entre elas</p>
<?php
//function
//require_once

use Fagoc\Calculadora;

$calculadora = new Calculadora('William');

var_dump($calculadora);
var_dump(post('operacao'));
