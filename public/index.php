<?php
namespace monochrome;

require_once '../src/functions.php';

require_once '../class/Autoload.php';
Autoload::on();

use DBconnection; 


#are we in DEV mode ?
define( 'DEBUGING', true );

/**
*  the returned string contains a div containing an errormessage
* @param string|NULL
* @return string
*/
function showError( $mssg = 'Erreur non spécifiée.' ) : string
{
    return "<aside class='error'>\n<h2>Désolé&nbsp!</h2>\n<p>
        Une erreur est survenue.<br>
        Veuillez contacter l'opérateur du site.<br>"
        . ( NULL != $mssg ?
                "Message d'erreur&nbsp:<br>\n"
                . htmlspecialchars( addslashes( $mssg ) )
              :
                ''
        )
        . "\n</p>\n</aside>\n";
}


$conn = new PDOconnection();


?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>checkpoint n°1</title>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--to deactivate the old "compatibility with that old IE" mode of edge -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="default.css">


    <!-- bootstrap links -->


    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">

    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous" defer></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous" defer></script>
  </head>

  <body>
    <h1>Content of the SQL database</h1>
    <h2>obtained with PDO</h2>
<?php
if ( ! $conn->getSuccess() )
    #this part is shown in case of connection error
    echo showError( DEBUGING ? $conn->getLastMsg() : NULL );
else {
    
    #TODO : add error handling
    $conn->doQuery();
?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td>Civilité</td>
                    <td>NOM et prénom</td>
                </tr>
            </thead>

            <tbody>
<?php
    while ( $data = $conn->fetchAsAssoc() )
    {
        echo formatTableRow($data['civility'],
                    fullName( $data['firstName'], $data['lastName'] )
             );
    }
}

?>
    <!-- #TODO : ajouter <tfoot> si nbentrées > valeur -->

           </tbody>
        </table>
    </div>

    <hr>

    <h2>Addition d'entrées</h2>

    <form method="POST" action="#" >
        <input type="submit" value="Ajouter une nouvelle entrée">

        <fieldset name="gender">
            <legend>Genre de la personne</legend>
            <select name="civility">
                <option value="Fem" selected>Féminin</option>
                <option value="Mal" selected>Masculin</option>
                <option value="oth" selected>Autre</option>
            </select>
        </fieldset>

        <fieldset name="firstName">
            <legend>Prénom</legend>
            <input name="firstName" placeholder="Prénom ..." required>
          </fieldset>

        <fieldset name="lastName">
            <legend>Nom</legend>
            <input name="lastName" placeholder="Nom de famille ..." required>
        </fieldset>

    </form>

  </body>
</html>