# BASE Coding Standard

## Unused function parameters
All parameters in a functions signature should be used within the function.
  <table>
   <tr>
    <th>Valid: All the parameters are used.</th>
    <th>Invalid: One of the parameters is not being used.</th>
   </tr>
   <tr>
<td>

    function addThree($a, $b, $c)
    {
        return $a + $b + $c;
    }

</td>
<td>

    function addThree($a, $b, $c)
    {
        return $a + $b;
    }

</td>
   </tr>
  </table>

## Closure Linter
All javascript files should pass basic Closure Linter tests.
  <table>
   <tr>
    <th>Valid: Valid JS Syntax is used.</th>
    <th>Invalid: Trailing comma in a javascript array.</th>
   </tr>
   <tr>
<td>

    var foo = [1, 2];

</td>
<td>

    var foo = [1, 2,];

</td>
   </tr>
  </table>

## Line Endings
Unix-style line endings are preferred (&quot;\n&quot; instead of &quot;\r\n&quot;).

## Line Length
It is recommended to keep lines at approximately 80 characters long for better code readability.

## Space After Casts
Exactly one space is allowed after a cast.
  <table>
   <tr>
    <th>Valid: A cast operator is followed by one space.</th>
    <th>Invalid: A cast operator is not followed by whitespace.</th>
   </tr>
   <tr>
<td>

    $foo = (string) 1;

</td>
<td>

    $foo = (string)1;

</td>
   </tr>
  </table>

## Space After NOT operator
Exactly one space is allowed after the NOT operator.
  <table>
   <tr>
    <th>Valid: A NOT operator followed by one space.</th>
    <th>Invalid: A NOT operator not followed by whitespace or followed by too much whitespace.</th>
   </tr>
   <tr>
<td>

    if (! $someVar || ! $x instanceOf stdClass) {};

</td>
<td>

    if (!$someVar || !$x instanceOf stdClass) {};
    
    if (!     $someVar || !
        $x instanceOf stdClass) {};

</td>
   </tr>
  </table>

## Cyclomatic Complexity
Functions should not have a cyclomatic complexity greater than 20, and should try to stay below a complexity of 10.

## Nesting Level
Functions should not have a nesting level greater than 10, and should try to stay below 5.

## Forbidden Functions
The forbidden functions sizeof() and delete() should not be used.
  <table>
   <tr>
    <th>Valid: count() is used in place of sizeof().</th>
    <th>Invalid: sizeof() is used.</th>
   </tr>
   <tr>
<td>

    $foo = count($bar);

</td>
<td>

    $foo = sizeof($bar);

</td>
   </tr>
  </table>

## Silenced Errors
Suppressing Errors is not allowed.
  <table>
   <tr>
    <th>Valid: isset() is used to verify that a variable exists before trying to use it.</th>
    <th>Invalid: Errors are suppressed.</th>
   </tr>
   <tr>
<td>

    if (isset($foo) && $foo) {
        echo "Hello\n";
    }

</td>
<td>

    if (@$foo) {
        echo "Hello\n";
    }

</td>
   </tr>
  </table>

## Unnecessary String Concatenation
Strings should not be concatenated together.
  <table>
   <tr>
    <th>Valid: A string can be concatenated with an expression.</th>
    <th>Invalid: Strings should not be concatenated together.</th>
   </tr>
   <tr>
<td>

    echo '5 + 2 = ' . (5 + 2);

</td>
<td>

    echo 'Hello' . ' ' . 'World';

</td>
   </tr>
  </table>

## Arbitrary Parentheses Spacing
Arbitrary sets of parentheses should have no spaces inside.
  <table>
   <tr>
    <th>Valid: no spaces on the inside of a set of arbitrary parentheses.</th>
    <th>Invalid: spaces or new lines on the inside of a set of arbitrary parentheses.</th>
   </tr>
   <tr>
<td>

    $a = (null !== $extra);

</td>
<td>

    $a = ( null !== $extra );
    
    $a = (
        null !== $extra
    );

</td>
   </tr>
  </table>

## Scope Indentation
Indentation for control structures, classes, and functions should be 4 spaces per level.
  <table>
   <tr>
    <th>Valid: 4 spaces are used to indent a control structure.</th>
    <th>Invalid: 8 spaces are used to indent a control structure.</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if ($test) {
            $var = 1;
    }

</td>
   </tr>
  </table>

## Function Calls
Functions should be called with no spaces between the function name, the opening parenthesis, and the first parameter; and no space between the last parameter, the closing parenthesis, and the semicolon.
  <table>
   <tr>
    <th>Valid: spaces between parameters</th>
    <th>Invalid: additional spaces used</th>
   </tr>
   <tr>
<td>

    $var = foo($bar, $baz, $quux);

</td>
<td>

    $var = foo ( $bar, $baz, $quux ) ;

</td>
   </tr>
  </table>

## Foreach Loop Declarations
There should be a space between each element of a foreach loop and the as keyword should be lowercase.
  <table>
   <tr>
    <th>Valid: Correct spacing used.</th>
    <th>Invalid: Invalid spacing used.</th>
   </tr>
   <tr>
<td>

    foreach ($foo as $bar => $baz) {
        echo $baz;
    }

</td>
<td>

    foreach ( $foo  as  $bar=>$baz ) {
        echo $baz;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Lowercase as keyword.</th>
    <th>Invalid: Uppercase as keyword.</th>
   </tr>
   <tr>
<td>

    foreach ($foo as $bar => $baz) {
        echo $baz;
    }

</td>
<td>

    foreach ($foo AS $bar => $baz) {
        echo $baz;
    }

</td>
   </tr>
  </table>

## For Loop Declarations
In a for loop declaration, there should be no space inside the brackets and there should be 0 spaces before and 1 space after semicolons.
  <table>
   <tr>
    <th>Valid: Correct spacing used.</th>
    <th>Invalid: Invalid spacing used inside brackets.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        echo $i;
    }

</td>
<td>

    for ( $i = 0; $i < 10; $i++ ) {
        echo $i;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Correct spacing used.</th>
    <th>Invalid: Invalid spacing used before semicolons.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        echo $i;
    }

</td>
<td>

    for ($i = 0 ; $i < 10 ; $i++) {
        echo $i;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Correct spacing used.</th>
    <th>Invalid: Invalid spacing used after semicolons.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        echo $i;
    }

</td>
<td>

    for ($i = 0;$i < 10;$i++) {
        echo $i;
    }

</td>
   </tr>
  </table>
Documentation generated on Tue, 02 May 2023 12:34:14 +0000 by [PHP_CodeSniffer 3.7.2](https://github.com/squizlabs/PHP_CodeSniffer)
