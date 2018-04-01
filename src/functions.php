<?php
namespace monochrome;

/**
 * Common text formating functions
 */


/**
 * multibytes ucfirst()
 *
 * @param  string $str
 * @return string
 */
function mb_strfirsttoupper(string $str) : string
{
    return mb_strtoupper(mb_substr($str, 0, 1))
           . mb_strtolower(mb_substr($str, 1));
}


/**
 * @param string $firstName
 * @param string $lastName
 * @return string
 */
function fullName(
    string $firstName = '',
    string $lastName = ''
) {
    return mb_strtoupper($firstName)
        .' '
        . mb_strfirsttoupper($lastName);
}

/**
 * returns a table row to include in a ... table
 *
 * @param  string $str1
 * @param  string $str2
 * @return string
 */
function formatTableRow(string $str1 = '', string $str2 = '') : string
{
    return "<tr>\n<td>" . $str1 ."</td>\n<td>" . $str2 . "</td>\n</tr>";
}

/**
 *  the returned string contains a div containing an errormessage
 * @param  string|NULL
 * @return string
 */
function formatError($mssg = 'Erreur non spécifiée.') : string
{
    return "<aside class='error'>\n<h2>Désolé&nbsp!</h2>\n<p>
        Une erreur est survenue.<br>
        Veuillez contacter l'opérateur du site.<br>"
        . ( null != $mssg ?
                "Message d'erreur&nbsp:<br>\n<span class='norm'>"
                . htmlspecialchars(addslashes($mssg)) . '</span>'
              :
                ''
        )
        . "\n</p>\n</aside>\n";
}
