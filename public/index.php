<?php
namespace monochrome;

require_once '../src/functions.php';

require_once '../class/Autoload.php';
Autoload::on();

use DBconnection; 


// are we in DEV mode ?
define('DEBUGING', true);

/**
 *  the returned string contains a div containing an errormessage
 *
 * @param  string|NULL
 * @return string
 */
function showError( $mssg = 'Erreur non spécifiée.' ) : string
{
    return "<aside class='error'>\n<h2>Désolé&nbsp!</h2>\n<p>
        Une erreur est survenue.<br>
        Veuillez contacter l'opérateur du site.<br>"
        . ( null != $mssg ?
                "Message d'erreur&nbsp:<br>\n"
                . htmlspecialchars(addslashes($mssg))
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

    <!-- Latest compiled and minified boostrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">

    <link rel="stylesheet" href="default.css">

  </head>

  <body>
 
 <!-- TODO: ADD A BANNER IN CASE OF NOJS AND A NOJS.CSS -->

    <h1>Content of the SQL database</h1>
    <h2>obtained with PDO</h2>
<?php
if (! $conn->getSuccess() ) {
    // this part is shown in case of connection error
    echo showError(DEBUGING ? $conn->getLastMsg() : null);
} else {
    
    // TODO : add error handling
    $conn->doQuery();
?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Civilité</th>
                    <th>NOM et prénom</th>
                </tr>
            </thead>

            <tbody>
<?php
    while ( $data = $conn->fetchAsAssoc() )
    {
        echo formatTableRow(
            $data['civility'],
            fullName($data['firstName'], $data['lastName'])
        );
    }
}

?>
<!-- #TODO : ajouter <tfoot> si nbentrées > valeur -->

           </tbody>
        </table>
    </div>


    <hr>

    
    <!-- this div is used to have both its children occupy same space -->
    <div class="myContainer">
        <label for="unhider1" class="btn btn-primary pull-left" role="button">
            Afficher la section administrative &gt;&gt;&gt;
        </label>
     
        <!-- the only role of this input is to toggle the visibility of the
         following section -->
        <input type="checkbox" role="aria-hidden"
                id="unhider1" class="unhider">
        
        <!-- this section is shown/hidden without JS –
         but with a combination of CSS, and a checkbox -->
        <section class="toggableHiding">
            <!-- a SECOND label for the same input -->
            <label for="unhider1" class="btn btn-primary pull-left"
                    role="button">
                &lt;&lt;&lt; Cacher la section administrative
            </label>
            <br>

            <h2>Addition d'entrées</h2>


            <form method="POST" action="#" class="form-inline">
 
                <fieldset class="form-group">

                    <label for="civility">
                        Genre
                        <select name="civility" id="civility" required>
                            <option disabled selected>
                                choisir ...
                            </option>
                            <option value="Fem">Féminin</option>
                            <option value="Mal">Masculin</option>
                            <option value="oth">Autre</option>
                        </select>
                    </label>
                
                    <label for="firstName">
                        Prénom
                        <input name="firstName" id="firstName"
                            placeholder="Prénom ..." required>
                    </label>

                    <label for="lastName">
                        Nom
                        <input name="lastName" id="lastName"
                            placeholder="Nom de famille ..." required>
                    </label>

<!--TODO: make this work -->
                    <button class="needJS" value="removeEntry" disabled>
  <!--TODO: better label (bootstrap glyph)-->
                         Annuler cette entrée
                    </button>
                </fieldset>


                <div class="form-group">
<!--TODO: singulier/pluriel via php -->
                    <input type="submit" value="Stocker les entrées">
<!--TODO: make this work -->
                    <button class="needJS" value="addEntry" disabled>
<!--TODO: better label (bootstrap glyph)-->
                        Ajouter une entrée
                    </button>
                </div>

            </form>


        </section>

  </body>
</html>